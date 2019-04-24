<?
 class register{
	static private $store=array();
	static private $instance; 
	private function __construct(){}
	private function __clone(){}
	public function set($name,$value){
		self::$store[$name]=$value;
	}
	public function get($name){
		return self::$store[$name];
	}
	public static function getInstance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
?>