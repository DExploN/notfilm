<?
class cache{
	protected static $liveTime=0;
	protected static $path='/cache/';
	
	public static function read($name,$func,$params=array(),$liveTime=NULL){
		if($liveTime===NULL)$liveTime=self::$liveTime;
		$path=str_replace("//","/",ROOT.self::$path.$name.'_.txt');
		if(self::is_cache($path,$liveTime)){
			return unserialize(gzuncompress(file_get_contents($path)));
		}else{
		
			if(is_callable($func)){
				$data=call_user_func_array($func,$params);
				$dataGZ=gzcompress(serialize($data));
				$pathinfo = pathinfo($path);
				if(!file_exists($pathinfo['dirname'])){
					@mkdir($pathinfo['dirname'],0777,true);
					$fp=fopen($pathinfo['dirname']."/.htaccess","w");
					flock ($fp,LOCK_EX);
					fwrite($fp,"deny from all");
					flock ($fp,LOCK_UN);
					fclose($fp);
				}
				$fp=fopen($path,"w");
				flock ($fp,LOCK_EX);
				fwrite($fp,$dataGZ);
				flock ($fp,LOCK_UN);
				fclose($fp);
				return $data;					
			}else{
				echo "У кэша $name не вызываемая функция";
			}
		}
	}
	
	public function delete($name,$dir=false){
		$path=self::$path.$name;
		if($dir===false)$path.='_.txt';
		if(file_exists(ROOT.$path)){
			if(is_dir(ROOT.$path)){
				dir::delete($path);
			}else{
				unlink(str_replace("//","/",ROOT.$path));
			}
		}
	}
	
	
	protected function is_cache($path,$time){
		if(file_exists($path) && ((time()-filemtime($path))<(int)$time)){
			return true;
		}else{
			return false;
		}
	}
		
	
}
?>