<?
class table{

	//protected $relations=array('genre'=>'posts_genre','type'=>'posts_type');
	protected $relations=array();

	private $tableName;
	protected $dbRules=array();
	private $with=array();
	public function __construct($table=NULL){
		if($table===NULL){
			$this->tableName=end(explode("\\",get_called_class()));
		}else{
				$this->tableName=$table;
		}		
	}
	protected function getExtraFields($rel,& $data, & $columns){
		$extraFields=array();
		$extraFields2=array();
		if(!is_array($columns))$columns=array();
		foreach($data as $key=>$value){
			if(preg_match("/^".$rel."(|_[a-zA-Z_0-9]+)$/iu",$key,$matches)){
				if($matches[1]){
					$col=mb_substr($matches[1],1);
					$extraFields[$col]=$value;
					$columns[]=$col;
				}else{
					$extraFields[$rel."_id"]=array_diff(array_unique($value),array(NULL,0,""));
					$columns[]=$rel."_id";
				}
				unset($data[$key]);
			}
		}
		foreach($extraFields[$rel."_id"] as $num=>$id){
			foreach($extraFields as $field=>$values){
				$extraFields2[$num][$field]=$values[$num];
			}
		}
		return $extraFields2;
	}
	
	
	/*
	дополнительные поля через relation_field, в базе просто field
	
	*/
	public function insert($data){
		$relsData=array();
		foreach($this->relations as $table=>$relsTable){
			if($data[$table]){
				$relsData[$table]=$this->getExtraFields($table,$data,$extraColumns[$table]);
			}
		}
		
		db()->insert($this->tableName,$data);
		$id=db()->insertId();
		if(count($relsData)){
			
			foreach($relsData as $table=>$relsTable){
				$columns=array($this->tableName."_id");
				$columns=array_merge($extraColumns[$table],$columns);
				$values=array();
				foreach($relsTable as $key=>$value){
					$value[$this->tableName."_id"]=$id;
					$values[]=$value;
				}
				
				db()->inserts($this->relations[$table],$columns,$values);
			}
		}
		return $id;
	}

	
	
	public function with($array=array()){
		if(is_array($array)){
			foreach($array as $key=>$value){
				if($this->relations[$key]){
					$this->with[$key]=$value;
				}
			}
			
		}
		return $this;
	}
	
	private function doWith($data,$many=true){
		if(count($this->with)){
			if($many===true){
				$ndata=array();
				foreach($data as $key=>$row){
					$ids[$row['id']]=$row['id'];
					$ndata[$row['id']]=$row;
					foreach($this->with as $rel=>$cols){
						$ndata[$row['id']][$rel]=array();
					}
				}
				$data=$ndata;
			}else{
				$ids[$row['id']]=$data['id'];
				foreach($this->with as $rel=>$cols){
					$data[$rel]=array();
				}
			}
			if(count($ids)){
				foreach($this->with as $rel=>$cols){
					if($cols){
						db()->select($cols);
					}else{
						db()->select("$rel.*",true);
					}
					$res=db()
					->select('`'.$this->relations[$rel].'`.`'.$this->tableName.'_id`',true)
					->leftJoin($this->relations[$rel],"$rel.id",$this->relations[$rel].".$rel"."_id")
					->whereIn(array($this->relations[$rel].".".$this->tableName."_id"=>$ids))
					->getAll($rel);
					foreach($res as $row){
						if($many===true){
							$id=$row[$this->tableName.'_id'];
							unset($row[$this->tableName.'_id']);
							$data[$id][$rel][]=$row;
						}else{
							unset($row[$this->tableName.'_id']);
							$data[$rel][]=$row;
						}
						
					}
				}
			}
		}
		$this->with=array();
		return $data;
	}
	protected function loadRules(){
		foreach($this->dbRules as $rule){
			list($method,$params)=each($rule);
			call_user_func_array(array(db(),$method),$params);
		}
		$this->dbRules=array();
	}
	
	public function delete($id){
		if(count($this->dbRules) && count($this->relations)){
				db()->select('id')->where(array('id'=>$id));
				$this->loadRules();			
				$data=db()->getall($this->tableName);
				$ids=array();
				foreach($data as $row){
					$ids[]=$row['id'];
				}
				if(count($ids)){
					db()->wherein(array('id'=>$ids))->delete($this->tableName);
					foreach($this->relations as $relsTable){
						db()->wherein(array($this->tableName.'_id'=>$ids))->delete($relsTable);
					}
				}
			}else{
				
				db()->where(array('id'=>$id));
				
				$this->loadRules();
				db()->delete($this->tableName);
				
				foreach($this->relations as $relsTable){
					db()->where(array($this->tableName.'_id'=>$id))->delete($relsTable);
				}
			}
			
	}
	

	public function deleteByFields($fields){
		foreach($fields as $field=>$value){
			db()->where(array($field=>$value));
		}
		$this->loadRules();			
		$data=db()->getall($this->tableName);
		$ids=array();
		foreach($data as $row){
			$ids[]=$row['id'];
		}
		if(count($ids)){
			db()->wherein(array('id'=>$ids))->delete($this->tableName);
			foreach($this->relations as $relsTable){
				db()->wherein(array($this->tableName.'_id'=>$ids))->delete($relsTable);
			}
		}
	}
	
	public function update($id,$data){
		
		$relsData=array();
		foreach($this->relations as $table=>$relsTable){
			if($data[$table]){
				$relsData[$table]=$this->getExtraFields($table,$data,$extraColumns[$table]);
			}
		}
		$this->loadRules();	
		db()->where(array('id'=>$id))->update($this->tableName,$data);
		if(count($relsData)){
			foreach($relsData as $table=>$relsTable){
				$columns=array($this->tableName."_id");
				$columns=array_merge($extraColumns[$table],$columns);
				$values=array();
				foreach($relsTable as $key=>$value){
					$value[$this->tableName."_id"]=$id;
					$values[]=$value;
				}
				db()->where(array($this->tableName."_id"=>$id))->delete($this->relations[$table]);
				db()->inserts($this->relations[$table],$columns,$values);
			}
		}
		return $id;
	}
	
	public function get($id){
		db()->where(array(''.$this->tableName.'.id'=>$id));
		$this->loadRules();
		return $this->doWith(db()->get($this->tableName),false);
	}
	
	public function getByFields($fields){
		foreach($fields as $field=>$value){
			db()->where(array($field=>$value));
		}
		return $this->doWith(db()->get($this->tableName),false);
	}
	
	public function getAll($offset=0,$count=NULL,& $num=false){
		if($offset || $count)db()->limit($offset,$count);
		$this->loadRules();
		return  $this->doWith(db()->getAll($this->tableName,$num));
	}
	public function getAllByPage($page,$perPage,& $num=false){
		if($page<1)$page=1;
		
		return $this->getAll(($page-1)*$perPage,$perPage,$num);
	}
	
	public function numrows(){
		$this->loadRules();
		return db()->numrows($this->tableName);
	}
	public function search($field,$search,$offset=0,$count=NULL,& $num=false){
		$search=trim($search);
		$search=preg_replace('/[^a-zA-Zа-яА-Я0-9]+/iu','%',$search);
		$search="%".$search."%";
		db()->where(array(''.$field.' like'=>$search));
		return $this->getAll($offset,$count,$num);
	}
	public function searchByPage($field,$search,$page,$perPage,& $num=false){
		if($page<1)$page=1;
		return $this->search($field,$search,($page-1)*$perPage,$perPage,$num);
	}



	
	public function perelink($id,$count){
		db()->where(array('id <'=>$id))->orderby(array('id'=>'desc'))->limit($count);
		$dbRules=$this->dbRules;
		$data=$this->getAll();
		$fCount=count($data);
		if($fCount<$count){
			db()->where(array('id >'=>$id))->orderby(array('id'=>'desc'))->limit($count-$fCount);
			$this->dbRules=$dbRules;
			$data=array_merge($data,$this->getAll());
		}
		if($data[0]['id']){
			$ids=array();
			foreach($data as $row){
				if(!$ids[$row['id']]){
					$ids[$row['id']]=1;
					$result[]=$row;
				}
			}
		}else{
			$result=$data;
		}
		return $result;
	}
	
	public function getAllByRel($rel,$id,$offset=0,$count=NULL,& $num=false){
		if($this->relations[$rel]){
			db()->join($this->relations[$rel],$this->relations[$rel].'.'.$this->tableName.'_id',$this->tableName.".id")->where(array($this->relations[$rel].'.'.$rel.'_id'=>$id));
			return $this->getAll($offset,$count,$num);
		}else{
			return array();
		}
	}
	public function getAllByRP($rel,$id,$page,$perPage,& $num=false){
		if($page<1)$page=1;
		return $this->getAllByRel($rel,$id,($page-1)*$perPage,$perPage,$num);
	}
	
	
	public function getRels($id,$rel,$offset=0,$count=NULL,& $num=false){
		if($this->relations[$rel]){
			db()->leftjoin($this->relations[$rel],$this->relations[$rel].'.'.$rel.'_id',$rel.".id")->where(array($this->relations[$rel].'.'.$this->tableName.'_id'=>$id));
			if($offset || $count)db()->limit($offset,$count);
			$this->loadRules();
			return db()->getAll($rel,$num);
		}else{
			return array();
		}
	}
	public function getRelsByPage($id,$rel,$page,$perPage,& $num=false){
		if($page<1)$page=1;
		return $this->getRels($id,$rel,($page-1)*$perPage,$perPage,$num);
	}
	
	public function perelinkByRel($id,$count,$rel,$perelinkflag=true){
		$relIds=array();
		if(is_array($rel)){
			list($rel,$relId)=each($rel);
			if($this->relations[$rel]){
				$relIds[]=$relId;
			}else{
				return array();
			}
		}else{
			if($this->relations[$rel]){
				$arr=db()->select($rel.'_id')->where(array($this->tableName.'_id'=>$id))->getAll($this->relations[$rel]);
				foreach($arr as $value){
					$relIds[]=$value[$rel.'_id'];
				}
			}else{
				return array();
			}
		}
		
		if(count($relIds)){
			$dbRules=$this->dbRules;
			$this->addRule('leftjoin',$this->relations[$rel],$this->relations[$rel].'.'.$this->tableName.'_id',$this->tableName.'.id');
			$this->addRule('wherein',array($this->relations[$rel].'.'.$rel.'_id'=>$relIds));
			
			$data=$this->perelink($id,$count);
			
			if(count($data)<$count && $relIds){
				$ids=array();
				foreach($data as $value){
					$ids[]=$value['id'];
				}
				$this->dbRules=$dbRules;
				$this->addRule('leftjoin',$this->relations[$rel],$this->relations[$rel].'.'.$this->tableName.'_id',$this->tableName.'.id');
				$this->addRule('wherein',array($this->relations[$rel].'.'.$rel.'_id'=>$relIds));
				$this->addRule('wherenotin',array('id'=>$ids));
				$data=array_merge($data,$this->perelink($id,$count-count($data)));
			}
			if(count($data)<$count && $perelinkflag){
				$this->dbRules=$dbRules;
				$ids=array();
				foreach($data as $value){
					$ids[]=$value['id'];
				}
				if(count($ids)){
					$this->addRule('wherenotin',array('id'=>$ids));
				}
				$data=array_merge($data,$this->perelink($id,$count-count($data)));
			}
			return $data;
		}else{
			if($perelinkflag){
				return $this->perelink($id,$count);
			}else{
				return array();
			}			
		}
	
	}
	
	
	public function addRule($dbMethod){
		$data=func_get_args();
		array_shift($data);
		$this->dbRules[]=array($dbMethod=>$data);
		return $this;
	}
	public function where($data){ 
		$this->addRule('where',$data);
		return $this;
	}
	public function select($data){ 
		$this->addRule('select',$data);
		return $this;
	}
	public function whereOr($data){ 
		$this->addRule('whereOr',$data);
		return $this;
	}	
	public function whereIn($data){ 
		$this->addRule('whereIn',$data);
		return $this;
	}	
	public function whereInOr($data){ 
		$this->addRule('whereInOr',$data);
		return $this;
	}	
	public function whereNotIn($data){ 
		$this->addRule('whereNotIn',$data);
		return $this;
	}	
	public function whereNotInOr($data){ 
		$this->addRule('whereNotInOr',$data);
		return $this;
	}	
	public function limit(){ 
		
		$data=func_get_args();
		array_unshift($data,'limit');
		call_user_func_array(array($this,'addRule'),$data);
		return $this;
	}	
	public function orderBy($data){ 
		$this->addRule('orderBy',$data);
		return $this;
	}	
	public function groupBy($data){ 
		$this->addRule('groupBy',$data);
		return $this;
	}	
	public function having($data){ 
		$this->addRule('having',$data);
		return $this;
	}	
	public function leftJoin(){ 
		$data=func_get_args();
		array_unshift($data,'leftJoin');
		call_user_func_array(array($this,'addRule'),$data);
		return $this;
	}	
	public function join(){ 
		$data=func_get_args();
		array_unshift($data,'join');
		call_user_func_array(array($this,'addRule'),$data);
		return $this;
	}	
	public function rightJoin(){ 
		$data=func_get_args();
		array_unshift($data,'rightJoin');
		call_user_func_array(array($this,'addRule'),$data);
		return $this;
	}	

}
?>