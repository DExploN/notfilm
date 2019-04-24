<?
	function translit($str,$sep='-'){
		
    	
		$pattern=array('/а/ui','/б/ui','/в/ui','/г/ui','/д/ui','/е/ui','/ё/ui','/ж/ui','/з/ui','/и/ui','/й/ui','/к/ui','/л/ui','/м/ui','/н/ui','/о/ui','/п/ui','/р/ui','/с/ui','/т/ui','/у/ui','/ф/ui','/х/ui','/ц/ui','/ч/ui','/ш/ui','/щ/ui','/ъ/ui','/ы/ui','/ь/ui','/э/ui','/ю/ui','/я/ui');
    	$sub=array('a','b','v','g','d','e','jo','zh','z','i','j','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','shh','','y','','e','yu','ya');
    	$str=preg_replace($pattern,$sub,$str);
    	$str=preg_replace('/[^a-z0-9]/ui',$sep,$str);
		$str=str_replace($sep.$sep,$sep,$str);
		
		$str=preg_replace('/^('.$sep.'|)(.*)('.$sep.'|)$/Uui',"$2",$str);
		
    	return $str;
 	}

?>