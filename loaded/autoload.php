<?
function autoload_ns($class){
	$ar=explode("\\",$class);
	$ar=array_diff($ar,array('',NULL));
	$file=array_pop($ar);
	$path=ROOT."/".implode("/",$ar)."/".$file.".php";
	
	if(file_exists($path)){
		include_once($path);
	}elseif(count($ar)==0 && file_exists(ROOT."/main/".$file.".php")){
		include_once(ROOT."/main/".$file.".php");
	}
}
spl_autoload_register("autoload_ns");

?>