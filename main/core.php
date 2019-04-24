<?
class core{
	private static $core;
	private static $runStatus;
	public $route;
	public $config;
	private function __construct(){}
	private function __clone(){}
	private $loaded=array();

	public function getCore(){
		if(!self::$core){
			if (isset($_REQUEST[session_name()])) session_start();
			$core=new self();
			mb_internal_encoding("UTF-8");
			$core->config=include('config.php');
			$core->load('core');
			$core->load('recursive_trim');
			$_POST=recursive_trim($_POST);
			$_GET=recursive_trim($_GET);
			if($core->config['server'])db::getDb($core->config['server'],$core->config['user'],$core->config['password'],$core->config['db']);
			msg::init();
			self::$core=$core;
			return $core;
		}else{
			return self::$core;
		}
	}
	public function run(router $route){
		if(self::$runStatus){
			echo "Ядро уже было запущено" ;
		}else{
			$core=self::getCore();
			$core->route=$route;
			switch($core->route->code){
				case 200:
					$core->runController($core->route->controller."/".$core->route->action,$core->route->CAParams);
					break;
				case 301:
					$core->code301($core->route->target);
					break;
				default:
					$core->code404();
					break;
			}
		}
		exit();
	}
	public function request($name){
		return $this->route->request[$name];
	}
	public function code301($url){
		header("HTTP/1.1 301 Moved Permanently");
		header("Location:".$url);
		exit();
	}
	public function redirect($url){
		header("Location:".$url);
		exit();
	}
	public function code404($message=NULL){
		header("HTTP/1.0 404 Not Found");
		$data=array();
		if($message)$data['text']=$message;
		$this->runController("_site/code404",$data);
		exit();	
	}
	public function backpage(){
		header("Location:".$_SERVER['HTTP_REFERER']);
		exit();	
	}
	public function runController($CA,$data=array(),$flag=true){
		$CA=explode('/',$CA);
		$controller=$CA[0];
		$action=($CA[1])?$CA[1]:'index';
		$class='\\controllers\\'.$controller.'Controller';
		if(class_exists($class)){
			if(method_exists($class,$action)){
				$obj= new $class;
				$access=$obj->gAccess($action);
				if($access){
					if($obj->gCaptcha($action)!==false){
						if(!\form::checkCaptcha())core()->backpage();
					}
					$data=call_user_func_array(array($obj,$action),$data);
					return $data;
				}else{
					//echo "Нет доступа к $controller/$action";
					core()->code404('Нет доступа');
				}				
			}else{
				if($flag==true){
					//echo "Метод ".$action." Контроллера ".$controller.'Controller не найден';
					
					core()->code404('Страница не найдена (Ошибка метода контроллера)');
				}	
			}
		}else{
			if($flag==true){
				//echo "Контроллер ".$controller.'Controller не найден';
				core()->code404('Страница не найдена (Ошибка контроллера)');
			}	
		}
	}
	public function load($load){
		if($this->loaded[$load]){
			return;
		}else{
			if(file_exists(ROOT.'/loaded/'.$load.'.php')){
				include(ROOT.'/loaded/'.$load.'.php');
				$this->loaded[$load]='1';
			}else{
				echo "Загружаемый файл $load не найден";
			}
		}
	}
}
?>