<?
namespace models;
class multicat extends \table{
	protected $relations=array('films'=>'films_multicat');
	
	
	public function getType(){
		return array(0=>'Фильмы',1=>'Мультфильмы',2=>'Сериалы',3=>'Документальные',4=>'Шоу',5=>'Аниме');
	}
	public function datatofilter($data){
		$result=array();
		$data['type']=(int)$data['type'];
		if($data['type']>0)$result[]='t'.$data['type'];
		
		
		$data['country']=(int)$data['country'];
		if($data['country']>0)$result[]='c'.$data['country'];
		
		if(is_array($data['genre'])){
			foreach($data['genre'] as $id){
				$id=(int)$id;
				if($id)$result[]='g'.$id;
			}
		}
		
		
		
		if(is_array($data['selection'])){
			foreach($data['selection'] as $id){
				$id=(int)$id;
				if($id)$result[]='s'.$id;
			}
		}
		
		$result=array_unique($result);
		sort($result);
		$result=implode("-",$result);
		return $result;
	}
	
	public function filtertodata($filter){
		$arr=explode("-",$filter);
		$data=array();
		$data['selection']=array();
		$data['genre']=array();
		
		foreach($arr as $row){
			if(preg_match("/^s(\d+)$/",$row,$matches)){
				$data['selection'][$matches[1]]['id']=$matches[1];
				continue;
			}
			
			if(preg_match("/^g(\d+)$/",$row,$matches)){
				$data['genre'][$matches[1]]['id']=$matches[1];
				continue;
			}
			
			if(preg_match("/^c(\d+)$/",$row,$matches)){
				$data['country']=$matches[1];
				continue;
			}
			if(preg_match("/^t(\d+)$/",$row,$matches)){
				$data['type']=$matches[1];
				continue;
			}
		}
		
		return $data;
	}
	
	public function datatoquery($data){
		
		$query=array();
		if($id=(int)$data['country']){
			if($id==2){
				$query[]="`id` in (select `films_id` from `films_country` where `country_id`='".$id."' or `country_id`='13')";
			}elseif($id==31){
				$query[]="`id` in (select `films_id` from `films_country` where `country_id`='".$id."' or `country_id`='28')";
			}else{
				$query[]="`id` in (select `films_id` from `films_country` where `country_id`='".$id."')";
			}
		}	
		if(1==(int)$data['type'])$query[]="`mult`='1'";
		if(2==(int)$data['type'])$query[]="`serial`='1'";
		if(3==(int)$data['type'])$query[]="`documental`='1'";
		if(4==(int)$data['type'])$query[]="`show`='1'";
		if(5==(int)$data['type'])$query[]="`anime`='1'";
		if(0==(int)$data['type'])$query[]="`mult`='0' and `serial`='0' and `documental`='0' and `show`='0' and `anime`='0'";
		
		if($cnt=count($data['selection'])){
			$arr=array();
			$cnt=0;
			foreach($data['selection'] as $row){
				if((int)$row['id']){
					$arr[]=(int)$row['id'];
					$cnt+=1;
					$query[]="`id` in (SELECT `films_id`  FROM `films_selection`  WHERE `selection_id` =".$row['id'].")";
				}
			}
			if($cnt){
				//$query[]="`id` in (select `films_id` from (SELECT `films_id`  FROM `films_selection`  WHERE `selection_id` in (".implode(',',$arr).")  group by `films_id` having count(`films_id`)=".$cnt.")`s`)";
				/*$ids=array();
				$arr=db()->getAllBySql("SELECT `films_id`  FROM `films_selection`  WHERE `selection_id` in (".implode(',',$arr).")  group by `films_id` having count(`films_id`)=".$cnt);
				if(count($arr)){
					foreach($arr as $row){
						$ids[]=$row['films_id'];
					}
					$ids=implode(",",$ids);
				}else{
					$ids='0';
				}
				$query[]="`id` in (".$ids.")";
				*/
			}
		}
		
		if($cnt=count($data['genre'])){
			$arr=array();
			$cnt=0;
			foreach($data['genre'] as $row){
				if((int)$row['id']){
					$arr[]=(int)$row['id'];
					$cnt+=1;
					$query[]="`id` in (SELECT `films_id`  FROM `films_genre`  WHERE `genre_id` =".$row['id'].")";
				}
			}
			if($cnt){
				//$query[]="`id` in (select `films_id` from (SELECT `films_id`  FROM `films_genre`  WHERE `genre_id` in (".implode(',',$arr).")  group by `films_id` having count(`films_id`)=".$cnt.")`g`)";
				/*$ids=array();
				$arr=db()->getAllBySql("SELECT `films_id`  FROM `films_genre`  WHERE `genre_id` in (".implode(',',$arr).")  group by `films_id` having count(`films_id`)=".$cnt);
				if(count($arr)){
					foreach($arr as $row){
						$ids[]=$row['films_id'];
					}
					$ids=implode(",",$ids);
				}else{
					$ids='0';
				}
				$query[]="`id` in (".$ids.")";
				*/
				
			}
		}
		
		if($data['actors']){
			$actors=trim($data['actors']);
			$actors=preg_replace('/[^a-zA-Zа-яА-Я0-9]+/iu','%',$actors);
			$actors="%".$actors."%";
			$query[]="`actors` like '".$actors."'";
		}
		
		$query=implode(' and ',$query);
		return $query;
	}
	public function add($data){
		if(trim($data['name'])==''){
			\msg::set('error','Имя мультикат пустое');
			return false;
		}
		
		if(!$data['filter'] && !$data['actors']){
			\msg::set('error','Данные фильтра не заданы');
			return false;
		}
		
		if(count($this->where(array('filter'=>$data['filter'],'actors'=>$data['actors']))->getAll())){
			\msg::set('error','Данный фильтр уже сущестует');
			return false;
		}
		
		core()->load('translit');
		$data['url']=translit($data['name']);
		
		$id=$this->insert($data);
		
		\msg::set('success','Мультикат добавлена');	
		return true;
	}
	
	public function change($id,$data){
		if(trim($data['name'])==''){
			\msg::set('error','Имя мультикат пустое');
			return false;
		}
		
		if(!$data['filter'] && !$data['actors']){
			\msg::set('error','Данные фильтра не заданы');
			return false;
		}
		
		if(count($this->where(array('filter'=>$data['filter'],'id !='=>$id,'actors'=>$data['actors']))->getAll())){
			\msg::set('error','Данный фильтр уже сущестует');
			return false;
		}
		
		$id=$this->update($id,$data);
		\msg::set('success','Мультикат изменена');	
		return true;
	}
	public function delete($id){
		parent::delete($id);
		\msg::set('success','Мультикат удалена');	
		return true;
	}
	
	
}
?>