<?
namespace models;
class comments extends \table{
	protected $relations=array();
	
		
	public function add($data,$unreg=false){
		$data['film_id']=(int)$data['film_id'];
		if(!$data['film_id']){
			\msg::set('error','Отсутствует идентификатор фильма');
			return false;
		}
		
		
		
		if($unreg==false){
			if(!$data['user_login']){
				\msg::set('error','Отсутствует идентификатор пользователя');
				return false;
			}
			if($data['cash']){
				if(mb_strlen ($data['text'])<config('min_com_len')){
					\msg::set('error','Размер комментария должен быть больше '.config('min_com_len').' символов');
					return false;
				}
			}else{
				if(mb_strlen ($data['text'])<config('min_unreg_com_len')){
					\msg::set('error','Размер комментария должен быть больше '.config('min_unreg_com_len').' символов');
					return false;
				}
			}
		}else{
			if(mb_strlen ($data['text'])<config('min_unreg_com_len')){
				\msg::set('error','Размер комментария должен быть больше '.config('min_unreg_com_len').' символов');
				return false;
			}
			if(mb_strlen ($data['unregname'])<config('min_com_name_len')){
				\msg::set('error','Длина имени должна быть больше '.config('min_com_name_len').' символов');
				return false;
			}
		}		
		
		
		if($data['cash'] && $data['cash']==md5(user()->info('login').$data['film_id'].user()->info('timestamp'))){
			if(db()->where(array('film_id'=>$data['film_id'],'user_login'=>user()->info('login')))->get('comments')){
				\msg::set('error','Вы уже оставляли комментарии к данному фильму (оплачивается только первый комментарий)');
				return false;
			}
			$data['paid']=1;
			db()->query("update `users` set `balance`=`balance`+:com_price: where `id`=':user_id:' ",array(':user_id:'=>user()->info('id'),':com_price:'=>config('com_price')));
		}
		
		unset($data['cash']);
		
		$id=$this->insert($data);
		if($id)return $id;
		return true;
	}
	
	public function delete($id){
		$dbRules=$this->dbRules;
		$com=$this->select('user_login, paid')->get($id);
		
		if($com['paid']){
			$users = new \models\users();
			$user= $users->select('id,balance')->getByFields(array('login'=>$com['user_login']));
			$user['balance']=$user['balance']-config('com_price');
			$user['balance']=($user['balance']>=0)?$user['balance']:0;
			$users->update($user['id'],array('balance'=>$user['balance']));
			
		}
		$this->dbRules=$dbRules;
		parent::delete($id);
	}
		
	public function getAllByFilm($id,$user_login=false,$lastcom_id=0){
		if($user_login==false && $lastcom_id==0){
			return $this->select('users.id as user_id, comments.user_login,  comments.unregname, comments.text, comments.timestamp, comments.activate')->where(array('film_id'=>$id,'comments.activate'=>1))->leftJoin('users','users.login','comments.user_login')->orderby(array('timestamp'=>'desc'))->getAll();
		}elseif($user_login && $lastcom_id){
			$lastcom_id=(int)$lastcom_id;
			return db()->select('comments.user_login,  comments.unregname, comments.text, comments.timestamp, comments.activate')->where("`comments`.`film_id`='$1' and `comments`.`activate`='1' or (`film_id`='$1' and `comments`.`activate`='0' and `user_login`='$2') or (`comments`.`id`='$3' and `comments`.`activate`='0')",array('$1'=>$id,'$2'=>$user_login,'$3'=>$lastcom_id))->orderby(array('timestamp'=>'desc'))->getAll('comments');
		}elseif($user_login){
			$user_id=(int)$user_id;
			return db()->select('users.id as user_id, comments.user_login,  comments.unregname, comments.text, comments.timestamp, comments.activate')->where(" `film_id`='$1' and `comments`.`activate`='1' or (`film_id`='$1' and `comments`.`activate`='0' and `user_login`='$2')",array('$1'=>$id,'$2'=>$user_login))->leftJoin('users','users.login','comments.user_login')->orderby(array('timestamp'=>'desc'))->getAll('comments');
		}elseif($lastcom_id){
			$lastcom_id=(int)$lastcom_id;
			return db()->select('comments.user_login,  comments.unregname, comments.text, comments.timestamp, comments.activate')->where("`comments`.`film_id`='$1' and `comments`.`activate`='1' or (`comments`.`id`='$3' and `comments`.`activate`='0')",array('$1'=>$id,'$3'=>$lastcom_id))->orderby(array('timestamp'=>'desc'))->getAll('comments');

		}
		
	}
	
	

	public function getLastComs($count){
		return $this->select('comments.text, comments.user_login, comments.unregname, films.id, films.name, films.url')->where(array('comments.activate'=>1))->leftJoin('films','comments.film_id','films.id')->orderby(array('timestamp'=>'desc'))->getAll($count);
	}
		
}
?>