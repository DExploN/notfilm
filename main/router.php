<?
class router{
	private $routes=array();
	public $code;
	public $CAParams;
	public $target;
	public $controller;
	public $action;
	public $request;
	public function __construct($routes){
		if(is_array($routes)){
			$this->routes=$routes;
		}
		$this->run();
	}
	
	public function run(){
		foreach($this->routes as $route){
			$url=$_SERVER['REQUEST_URI'];
			$sName=$_SERVER['SERVER_NAME'];
			$flag=0;		
			$pattern="/^".addcslashes($route[0],"/")."$/i";
			
			if(preg_match($pattern,($route[3]['sn'])?$sName.$url:$url,$matches)){
				$flag=1;
				break;
			}
		}	
		if(!$flag){
			return $this;
		}
		
		$is_query=is_array($route[2]);
		
		$CAParams=array();
		for($i=1;$i<count($matches);$i++){
			if(strpos($route[1],"$".$i)!==false){
				$route[1]=str_replace("$".$i,$matches[$i],$route[1]);
			}else{	
				$CAParams[$i]=$matches[$i];
			}
			if($is_query){
				if($key=array_search("$".$i,$route[2]))$route[2][$key]=$matches[$i];
			}
		}
		$this->code=($route[3]['code'])?$route[3]['code']:200;
		$this->CAParams=$CAParams;
		$this->target=$route[1];
		$conAc=explode('/',$route[1]);
		$this->controller=$conAc[0];
		$this->action=($conAc[1])?$conAc[1]:'index';
		$this->request=(is_array($route[2]))?$route[2]:array();

	}
}

?>