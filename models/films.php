<?
namespace models;
class films extends \table{
	protected $relations=array('genre'=>'films_genre','selection'=>'films_selection','country'=>'films_country','multicat'=>'films_multicat');
	
	
	
	public function add($data){
		if(trim($data['name'])==''){
			\msg::set('error','Имя фильма пустое');
			return false;
		}
		core()->load('translit');
		$data['url']=translit($data['name']);
		if($img=\image::get('img',true)){
			if($img->imageType!='JPG'){
				\msg::set('error','Изображение должно быть JPG');
				return false;
			}
			$img->name=$data['url'].".jpg";
			
		}elseif(!$data['img_base64']){
			\msg::set('error','Отсутствует изображение');
			return false;
		}
		
		$timestamp=date("Y-m-d H:i:s");
		$data['insertTimestamp']=$timestamp;
		$data['updateTimestamp']=$timestamp;
		
		
		
		if($data['endSer']==1){
			$data['endSer']=1;
		}else{
			$data['endSer']=0;
		}
		
		if(is_array($data['genre'])){
			if(array_search(config('serialgenre_id'),$data['genre'])!==false){
				$data['serial']=1;
			}else{
				$data['serial']=0;
			}
			
			if(array_search(config('showgenre_id'),$data['genre'])!==false){
				$data['show']=1;
			}else{
				$data['show']=0;
			}
			
			if(array_search(config('animegenre_id'),$data['genre'])!==false){
				$data['anime']=1;
			}else{
				$data['anime']=0;
			}
			
			if(array_search(config('docgenre_id'),$data['genre'])!==false){
				$data['documental']=1;
			}else{
				$data['documental']=0;
			}
			
			if(array_search(config('multgenre_id'),$data['genre'])!==false){
				$data['mult']=1;
			}else{
				$data['mult']=0;
			}
		}
		
		unset($data['img_url']);
		
		$img_base64=$data['img_base64'];
		unset($data['img_base64']);
		
		$id=$this->insert($data);
		
		if($img && !$img_url){
			$img->moveTo("/uploads/films/$id/");
			$img->resize($img->cropPath,config('film_img_w'),config('film_img_h'));
		}elseif($img_base64){
			if(!is_dir(ROOT."/uploads/films/$id/"))mkdir(ROOT."/uploads/films/$id/",0777,true);
			file_put_contents(ROOT."/uploads/films/$id/".$data['url'].".jpg",base64_decode($img_base64));
			if($img=\image::get("uploads/films/$id/".$data['url'].'.jpg')){
				$img->resize($img->cropPath,config('film_img_w'),config('film_img_h'));
			}
		}
		if(!$data['parts']){
			$this->update($id,array('parts'=>$id));
		}else{
			$parts=$this->select('parts')->get($data['parts']);
			$this->update($id,array('parts'=>$parts['parts']));
		}
		
		\msg::set('success','Фильм добавлен');
		return true;
	}
	
	public function change($id,$data){
		if(trim($data['name'])==''){
			\msg::set('error','Имя фильма пустое');
			return false;
		}
		$dbRules=$this->dbRules;
		if($img=\image::get('img',true)){
			if($img->imageType!='JPG'){
				\msg::set('error','Изображение должно быть JPG');
				return false;
			}
			$obj=$this->select('url')->get($id);
			if($obj['url']){
				$obj['url']=preg_replace("/\-[0-9]{2}\-[0-9]{2}\-[0-9]{4}$/i","",$obj['url']);
				$img->name=$obj['url'].".jpg";
				$img->moveTo("/uploads/films/$id/");
				$img->resize($img->cropPath,config('film_img_w'),config('film_img_h'));
			}
		}
		
		
		if($data['endSer']==1){
			$data['endSer']=1;
		}else{
			$data['endSer']=0;
		}
		
		if($data['parts']){
			$parts=$this->select('parts')->get($data['parts']);
			$data['parts']=$parts['parts'];
		}
		
		if(is_array($data['genre'])){
			if(array_search(config('serialgenre_id'),$data['genre'])!==false){
				$data['serial']=1;
			}else{
				$data['serial']=0;
			}
			
			if(array_search(config('showgenre_id'),$data['genre'])!==false){
				$data['show']=1;
			}else{
				$data['show']=0;
			}
			
			if(array_search(config('animegenre_id'),$data['genre'])!==false){
				$data['anime']=1;
			}else{
				$data['anime']=0;
			}
			
			if(array_search(config('docgenre_id'),$data['genre'])!==false){
				$data['documental']=1;
			}else{
				$data['documental']=0;
			}
			
			if(array_search(config('multgenre_id'),$data['genre'])!==false){
				$data['mult']=1;
			}else{
				$data['mult']=0;
			}
		}
		
		if(($data['anime'] || $data['serial'] || $data['show']) && $data['onmain']){
			$obj=$this->select('url')->get($id);
			if($obj['url']){
				$obj['url']=preg_replace("/\-[0-9]{2}\-[0-9]{2}\-[0-9]{4}$/i","",$obj['url']);
				$data['url']=$obj['url'].'-'.date("d-m-Y");
			}
		}
		
		if($data['onmain']==1){
			unset($data['onmain']);
			$data['updateTimestamp']=date("Y-m-d H:i:s");
		}
		
		$this->dbRules=$dbRules;		
		$id=$this->update($id,$data);
		\msg::set('success','Фильм изменен');
		return true;
	}
	public function delete($id){
		parent::delete($id);
		db()->where(array('film_id'=>$id))->delete('comments');
		\dir::delete("/uploads/films/$id/");
		\msg::set('success','Фильм удален');
	}
	
	
	public function get($id){
		$film=parent::get($id);
		$links=$film['links'];
		$film['video']=array();
		$links=explode(";",$links);
		$i=1;
		
		if($film['dateRightholder']=='0000-00-00'){
			$film['Rightholder']=false;
		}else{
			$film['Rightholder']=true;
		}
		
		foreach($links as $row){
				if($row){
					$link=explode("|",$row);
					if($link[1]){
						unset($data);
						$data['name']=$link[0];
						$data['link']=trim($link[1]);
						
					}else{
						unset($data);
						$link[0]=trim($link[0]);
						if(strpos($link[0],'ivi.ru')!==false){
							$data['name']='Плеер от ivi';
						}elseif(strpos($link[0],'megogo.net')!==false){
							$data['name']='Плеер от megogo';
						}else{
							$data['name']='Плеер '.$i;
						}
						$data['link']=$link[0];
						
					}
					$data['link']=str_replace('ivi.ru/external/stub/','ivi.ru/player/',$data['link']);
					/*
					$query=parse_url($data['link']);
					$query=$query['query'];
					if(preg_match('/^oid\=.*\&id\=.*\&hash\=.*$/ui',$query)){
						parse_str($query, $output);
						$data['link']="http://scriptdomen.ru/pleer/".$output['oid']."/".$output['id']."/".$output['hash']."/";
					}
					*/
					
					//$data['link']="http://scriptdomen.ru/pleer/?url=".urlencode($data['link']);
					
					$film['video'][]=$data;
					$i++;
				}
		}
		
		
		
		if($film['video'][0]){
			$film['first_link']=$film['video'][0]['link'];
		}else{
			$film['first_link']=$film['trailer'];
		}		
		return $film;
	}
	
	public static function getQuality($id=NULL){
		$array=array(
			0=>'-',
			1=>'CamRip',
			2=>'HD 720p',
			3=>'HD звук TS',
			4=>'HD одноголосый',
		);
		if($id){
			return $array[$id];
		}else{
			return $array;
		}
	}
	
	
	
	public function getIndexFilms($page,$perpage,& $num){
		$this->with(array('genre'=>'id,url,name,anime','selection'=>'id,url,name'))->where(array('serial'=>'0','show'=>'0','anime'=>'0','links !='=>'','dateRightholder'=>'0000-00-00'));
		$sort=(int)$_COOKIE['sort'];
		$sortType=(int)$_COOKIE['sortType'];
		if(!$sort && !$sortType){
			$this->addRule('orderBy',"`updateTimestamp` desc",true);
		}else{
			switch($sort){
				case 1:
					$sort='year';
					break;
				case 2:
					$sort='name';
					break;
				case 3:
					$sort='rate';
					break;
				case 4:
					$sort='views';
					break;
				case 5:
					$sort='comments';
					break;
				default:
					$sort='updateTimestamp';
					break;		
			}
			$sortType=($sortType==1)?'desc':'asc';
			$this->orderBy(array($sort=>$sortType));
		}
		
		
		$data=$this->getAllByPage($page,$perpage,$num);
		return $data;
	}
	
	public function getDetailFilm($id){
		return $this->with(array('selection'=>'id,name,url','genre'=>'id,name,url,anime','multicat'=>'id,name,url'))->get($id);
	}
	
	public function getPerelink($id,$count,$rel,$parts){
		return $this->select('id,name,url')->where(array('parts !='=>$parts))->perelinkByRel($id,$count,$rel);
	}
	
	public function getOtherParts($id,$partsId){
		return $this->select('id,name,url')->where(array('parts'=>$partsId,'id !='=>$id))->orderBy(array('year'=>'desc','insertTimestamp'=>'desc'))->getAll();
	}
	
	public function isCashed($id){
		$tmp=$this
			->addRule('select','`films`.`id`,`films`.`name`,`films`.`url`,count(`comments`.`id`) as `count`',true)
			->leftjoin('comments','films.id','comments.film_id')
			->addRule('where'," ((`dateRf`< ':up:' and `dateRf`>':down:') || (`dateRf`< ':up:' and `year`>':down_year:' and `links`!='' and `serial`='0' and `show`='0' and `anime`='0')) and `films`.`id` not in (select `film_id` from `comments` where `user_login`=':user_login:') ",array(':user_login:'=>user()->info('login'),':up:'=>date("Y-m-d",(time()+config('need_com_time_up'))),':down:'=>date("Y-m-d",(time()-config('need_com_time_down'))),':down_year:'=>(date('Y')-config('need_com_year_down'))))
			->where(array('dateRightholder'=>'0000-00-00'))
			->groupBy('films.id')
			->having(array('count <'=>config('need_com_count')))
			->get($id);
		
		if($tmp['id']){
			return true;
		}else{
			return false;
		}
	}
	
	public function getSoonFilms($page,$perpage,& $num){
		return $this->with(array('genre'=>'id,url,name','selection'=>'id,url,name'))->where(array('dateRf >'=>date("Y-m-d")))->orderBy(array('dateRf'=>'asc'))->getAllByPage($page,$perpage,$num);
	}
	
	public function getAllFilms($page,$perpage,& $num){
		return $this->select('id, name, url,anime,year,serial,show')->orderBy(array('insertTimestamp'=>'desc'))->getAllByPage($page,$perpage,$num);
	}
	
	public function getCamRipFilms($page,$perpage,& $num){
		return $this->select('id, name,url, dateRf')->where(array('quality !='=>0))->where(array('quality !='=>2))->orderBy(array('dateRf'=>'asc'))->getAllByPage($page,$perpage,$num);
	}
	
	public function getNotvidFilms($page,$perpage,& $num){
		return $this->select('id, name,url, dateRf')->where(array('links'=>'','s_links'=>'','onlineStatus'=>'1','serial'=>'0','show'=>'0','anime'=>'0'))->orderBy(array('year'=>'desc','dateRf'=>'desc'))->getAllByPage($page,$perpage,$num);
	}
	
	public function getChangedonline($page,$perpage,& $num){
		return $this->select('id, name,url, dateRf')->where(array('onlineStatus'=>'2','dateRf <='=>date("Y-m-d")))->orderBy(array('year'=>'desc'))->getAllByPage($page,$perpage,$num);
	}
	
	public function getSearchPleer($pleer,$inv,$page,$perpage,& $num){
		if($pleer){
			if($inv)$inv="not";
			return $this->select('id, name,url, year as dateRf')->addRule("where","`links`!='' and `links`  ".$inv." like '%:1:%' ",array(":1:"=>$pleer))->orderBy(array('year'=>'asc'))->getAllByPage($page,$perpage,$num);
		}else{
			return array();
		}		
	}
	
	
	public function getRightholderFilms($page,$perpage,& $num){
		return $this->select('id, name,url, dateRightholder as dateRf')->where(array('dateRightholder !='=>'0000-00-00'))->orderBy(array('dateRightholder'=>'asc'))->getAllByPage($page,$perpage,$num);
	}
	
	
	public function getNextserFilms(){
		return $this->select('id, name,url,lastSer, nextSerDate as dateRf')->where(array('nextSerDate !='=>'0000-00-00'))->where(array('nextSerDate <='=>date("Y-m-d")))->orderBy(array('nextSerDate'=>'asc'))->getAll();
	}
	
	
	public function getForgSer($page,$perpage,& $num){
		return $this->select('id, name,url, updateTimestamp as dateRf')
		//->where(array('updateTimestamp <'=>date("Y-m-d H:i:s",(time()-config('forgot_ser_time'))),'serial'=>'1','endSer'=>'0'))
		->addRule('where',"`updateTimestamp`<':1:' and `endSer`='0' and (`serial`='1' or `show`='1' or `anime`='1')",array(':1:'=>date("Y-m-d H:i:s",(time()-config('forgot_ser_time')))))
		->orderBy(array('updateTimestamp'=>'asc'))->getAllByPage($page,$perpage,$num);
	}
	
	public function getErrorvidFilms($page,$perpage,& $num){
		return $this->select('id, name,url,error_text')->where(array('error'=>'1'))->orderBy(array('dateRf'=>'desc'))->getAllByPage($page,$perpage,$num);
	}
	
	
	public function getFilmsByYear($year,$page,$perpage,& $num){
		$this->with(array('genre'=>'id,url,name,anime','selection'=>'id,url,name'));
		
		
		$this->where(array('year'=>$year,'serial'=>'0','show'=>'0','anime'=>'0','dateRightholder'=>'0000-00-00'));
		
		
		$sort=(int)$_COOKIE['sort'];
		$sortType=(int)$_COOKIE['sortType'];
		if(!$sort && !$sortType){
			$this->addRule('orderBy',"field(links,'') asc, field(s_links,'') asc,`updateTimestamp` desc",true);
		}else{
			switch($sort){
				case 2:
					$sort='name';
					break;
				case 3:
					$sort='rate';
					break;
				case 4:
					$sort='views';
					break;
				case 5:
					$sort='comments';
					break;
				default:
					$sort='updateTimestamp';
					break;		
			}
			$sortType=($sortType==1)?'desc':'asc';
			$this->orderBy(array($sort=>$sortType));
		}
		
		
		$data=$this->getAllByPage($page,$perpage,$num);
		
	
		
		return $data;
	}
	
	public function searchFilm($field,$value,$page,$perpage,& $num ){
		if(!trim($value)) return array();
		$data=$this->with(array('genre'=>'id,url,name,anime','selection'=>'id,url,name'))->orderBy(array('year'=>'desc'))->searchByPage($field,$value,$page,$perpage,$num);
		
		return $data;
	}
	
	public function getFilmsByGenre($genre,$page,$perpage,& $num){
		if($genre==config('serialgenre_id') || $genre==config('showgenre_id') || $genre==config('animegenre_id')){
			$this->with(array('genre'=>'id,url,name,anime','selection'=>'id,url,name'))->where(array('dateRf <='=>date("Y-m-d"),'dateRightholder'=>'0000-00-00'));
		}else{
			$this->with(array('genre'=>'id,url,name,anime','selection'=>'id,url,name'))->where(array('dateRf <='=>date("Y-m-d"),'serial'=>'0','show'=>'0','dateRightholder'=>'0000-00-00'));
		}
		
		
		$sort=(int)$_COOKIE['sort'];
		$sortType=(int)$_COOKIE['sortType'];
		if(!$sort && !$sortType){
			if($genre==config('serialgenre_id') || $genre==config('showgenre_id') || $genre==config('animegenre_id')){
				$this->addRule('orderBy',"field(s_links,'') asc, `updateTimestamp` desc",true);
			}else{
				$this->addRule('orderBy',"field(links,'') asc, `year` desc, `updateTimestamp` desc",true);
			}
		}else{
			switch($sort){
				case 1:
					$sort='year';
					break;
				case 2:
					$sort='name';
					break;
				case 3:
					$sort='rate';
					break;
				case 4:
					$sort='views';
					break;
				case 5:
					$sort='comments';
					break;
				default:
					$sort='updateTimestamp';
					break;		
			}
			$sortType=($sortType==1)?'desc':'asc';
			$this->orderBy(array($sort=>$sortType));
		}
		
		$data=$this->getAllByRP('genre',$genre,$page,$perpage,$num);
		return $data;
	}
	
	public function getFilmsBySelection($sel,$page,$perpage,& $num){
		$this->with(array('genre'=>'id,url,name,anime','selection'=>'id,url,name'));
		
		
		$sort=(int)$_COOKIE['sort'];
		$sortType=(int)$_COOKIE['sortType'];
		if(!$sort && !$sortType){
			$this->addRule('orderBy',"field(links,'') asc, field(s_links,'') asc, `year` desc, `updateTimestamp` desc",true);
		}else{
			switch($sort){
				case 1:
					$sort='year';
					break;
				case 2:
					$sort='name';
					break;
				case 3:
					$sort='rate';
					break;
				case 4:
					$sort='views';
					break;
				case 5:
					$sort='comments';
					break;
				default:
					$sort='updateTimestamp';
					break;		
			}
			$sortType=($sortType==1)?'desc':'asc';
			$this->orderBy(array($sort=>$sortType));
		}
		$data=$this->getAllByRP('selection',$sel,$page,$perpage,$num);
		return $data;
	}
	
	public function getFilmsByQuery($query,$page,$perpage,& $num){
		
		$this->with(array('genre'=>'id,url,name,anime','selection'=>'id,url,name'));
				
		$sort=(int)$_COOKIE['sort'];
		$sortType=(int)$_COOKIE['sortType'];
		if(!$sort && !$sortType){
			$this->addRule('orderBy',"field(links,'') asc, field(s_links,'') asc, `year` desc, `updateTimestamp` desc",true);
		}else{
			switch($sort){
				case 1:
					$sort='year';
					break;
				case 2:
					$sort='name';
					break;
				case 3:
					$sort='rate';
					break;
				case 4:
					$sort='views';
					break;
				case 5:
					$sort='comments';
					break;
				default:
					$sort='updateTimestamp';
					break;		
			}
			$sortType=($sortType==1)?'desc':'asc';
			$this->orderBy(array($sort=>$sortType));
		}
		$this->addRule('where',$query,array());
		
		$data=$this->getAllByPage($page,$perpage,$num);
		return $data;
	}
	
	public function needcomFilms($count){
		$data=$this
			->addRule('select','`films`.`id`,`films`.`name`,`films`.`url`,count(`comments`.`id`) as `count`',true)
			->leftjoin('comments','films.id','comments.film_id')
			->where(array('dateRf <'=>date("Y-m-d",(time()+config('need_com_time_up')))))
			->where(array('dateRf >'=>date("Y-m-d",(time()-config('need_com_time_down')))))
			->where(array('dateRightholder'=>'0000-00-00'))
			->where(array('endSer'=>0))
			->addRule('where'," `films`.`id` not in (select `film_id` from `comments` where `user_login`=':user_login:') ",array(':user_login:'=>user()->info('login')))
			->groupBy('films.id')
			->having(array('count <'=>config('need_com_count')))
			->orderby(array('films.dateRf'=>'desc'))
			->getAll($count);

		if(count($data)<$count){
			
			$this
			->addRule('select','`films`.`id`,`films`.`name`,`films`.`url`,count(`comments`.`id`) as `count`',true)
			->leftjoin('comments','films.id','comments.film_id')
			->where(array('dateRf <'=>date("Y-m-d",(time()+config('need_com_time_up')))))
			->where(array('links !='=>'','serial'=>'0','show'=>'0','anime'=>'0','year >'=>(date('Y')-config('need_com_year_down')),'dateRightholder'=>'0000-00-00'))
			
			->addRule('where'," `films`.`id` not in (select `film_id` from `comments` where `user_login`=':user_login:') ",array(':user_login:'=>user()->info('login')))
			->groupBy('films.id')
			->having(array('count <'=>config('need_com_count_more')))
			->addRule('orderBy',' `films`.`year` desc , rand()',true);
			
			if($data){
				foreach($data as $row){
					$ids[]=$row['id'];
				}
				$this->addRule('whereNotIn',array('films.id'=>$ids));
			}
			$data2=$this->getAll($count-count($data));
			$data= array_merge($data,$data2);
		}
		return $data;	
	}
	
	public function lcfilms($count){
		$count=(int)$count;
		return db()->getAllBySql("select `film_id` as `id`,`user_login`,`name`,`url`,`t`.`text`,`t`.`unregname`, `t`.`timestamp`  from (select * from `comments` where `activate`='1' order by `timestamp` desc)`t` left join `films` on `t`.`film_id`=`films`.`id` group by `film_id` order by `t`.`timestamp` desc  limit $1 ",array('$1'=>$count));
	}
	
	
	
}
?>