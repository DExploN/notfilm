<?
namespace models;
class users extends \users{

	public static function login($login,$password){
		if($login=='' || $password==''){
			\msg::set('error','Логин/пароль не должны быть пустыми');
			return ;
		}
		
		$logerror= new \table('logerror');
		
		if($logerror->where(array('login'=>$login,'timestamp >'=>date("Y-m-d H:i:s",(time()-config('logerror_time')))))->numrows()>=config('logerror_count')){
			\msg::set('error','Аккаунт заблокирован на '.(config('logerror_time')/60).' минут. Причина: превышено допустимое количество попыток неверного ввода пароля.');
			return;
		}
		
		$password=self::hash($password);
		$data=db()->select('id,activate,banned, ban_reason')->where(array('login'=>$login,'password'=>$password))->get('users');
		if($data['id']){
			if(!$data['activate']){
				\msg::set('error','Ваша учетная запись еще не актвиирована. Проверьте почту.');
				return false;
			}
			if($data['banned']){
				\msg::set('error','Ваша учетная запись была забанена, <br /> Причина: '.$data['ban_reason']);
				return false;
			}
			if(!session_id())session_start();
			$_SESSION['user_id']=$data['id'];
			$time=date("Y-m-d H:i:s");
			setcookie("authtime",$time,time()+86400,"/");
			db()->insert('sessions',array('user_id'=>$data['id'],'hash'=>self::hash(self::browserinfo(),$time),'timestamp'=>$time));
			db()->where(array('timestamp <'=>date("Y-m-d H:i:s",(time()-config('logerror_time')))))->delete('logerror');
		}else{
			if(db()->select('id')->where(array('login'=>$login))->get('users')){
				$logerror->insert(array('login'=>$login));
			}
			\msg::set('error','Логин/пароль не подошли');
		}
	}

	public static function changeInfo($id,$data){
		
		if($img=\image::get('img',true)){
			if($img->imageType!='JPG'){
				\msg::set('error','Изображение должно быть JPG');
				return false;
			}
			$img->name='image.jpg';
			$img->moveTo('/uploads/users/'.$id.'/');
			$img->resize($img->cropPath,config('user_img_w'),config('user_img_h'));
		}
		
		if(!filter_var($data['mail'])){
			\msg::set('error','Не верный формат E-mail');
			return false;
		}
		
		if(db()->where(array('mail'=>$data['mail'],'id !='=>$id))->get('users')){
			\msg::set('error','Данный e-mail уже есть в системе');
			return false;
		}
		
		parent::changeInfo($id,$data);
	}
	
	public static function addUser($data){
		if(!filter_var($data['mail'])){
			\msg::set('error','Не верный формат E-mail');
			return false;
		}
		
		if(db()->where(array('mail'=>$data['mail']))->get('users')){
			\msg::set('error','Данный e-mail уже есть в системе');
			return false;
		}
		
		if($id=parent::addUser($data)){
			$user=db()->where(array('login'=>$data['login']))->get('users');
		
			$to=$data['mail'];
			$subject='Активация на сайте notfilm.ru';
			$code=md5($user['id'].$user['login'].$user['password'].$user['mail']);
			
			$message="Для активации перейдите по ссылке: \r\n".'<a href="http://notfilm.ru/activate/'. $data['login'].'/'.$code.'/">http://notfilm.ru/activate/'. $data['login'].'/'.$code.'/</a>';
			$headers   = array();
			$headers[] = "MIME-Version: 1.0";
			$headers[] = "Content-type: text/html; charset=utf-8";
			$headers[] = "From: notfilm@yandex.ru";
			$headers[] = "Reply-To: notfilm@yandex.ru";
			$headers[] = "Subject: {$subject}";
			$headers[] = "X-Mailer: PHP/".phpversion();
			
			$message = wordwrap($message, 70, "\r\n");
			mail($to, $subject, $message, implode("\r\n", $headers));
			\msg::set('success','Пользователь успешно зарегистрирован. На ваш e-mail выслана ссылка активации. <br/> Внимание! Письмо может попасть в «Спам».');
			return true;
		}else{
			return false;
		}
	}
	
	
	public static function remind($mail){
		if(!filter_var($mail)){
			\msg::set('error','Не верный формат E-mail');
			return false;
		}
		
		if(!$user=db()->select('id')->where(array('mail'=>$mail))->get('users')){
			\msg::set('error','Данный e-mail отсутствует в системе');
			return false;
		}
		$pass=self::generatePassword();
		db()->where(array('id'=>$user['id']))->update('users',array('password'=>\users::hash($pass)));
		
		$to=$mail;
		$subject='Напоминание пароля на сайте notfilm.ru';
		
		$message="Ваш новый пароль: \r\n".$pass;
		$headers   = array();
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-type: text/html; charset=utf-8";
		$headers[] = "From: notfilm@yandex.ru";
		$headers[] = "Reply-To: notfilm@yandex.ru";
		$headers[] = "Subject: {$subject}";
		$headers[] = "X-Mailer: PHP/".phpversion();
		
		$message = wordwrap($message, 70, "\r\n");
		mail($to, $subject, $message, implode("\r\n", $headers));
		\msg::set('success','Новый пароль отправлен по почте');
		
		
	}
	
	private static function generatePassword($length = 8){
		  $chars = 'abdefhiknrstyz123456789';
		  $numChars = strlen($chars);
		  $string = '';
		  for ($i = 0; $i < $length; $i++) {
			$string .= substr($chars, rand(1, $numChars) - 1, 1);
		  }
		  return $string;
	}
	public static function activate($login,$code){
		$user=db()->where(array('login'=>$login))->get('users');
		if($user['activate']=='0'){
			if($code==md5($user['id'].$user['login'].$user['password'].$user['mail'])){
				db()->where(array('id'=>$user['id']))->update('users',array('activate'=>1));
				\msg::set('success','Активация прошла успешно');
				return true;
			}else{
				\msg::set('error','Код активации не верен');
				return false;
			}
		}else{
				\msg::set('success','Пользователь уже активирован');
				core()->redirect('/');
				return true;
		}
	}
	
} 
?>