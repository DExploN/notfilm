<?php
header ("Content-type: image/gif");
header ( "Pragma: no-cache");
$height=75;
$width=300;
$font="font/5.ttf";
session_start();
$string="";
$alf="авгдежиклмнпрстуфхцчшыэю€2456789";
$sizeAlf=strlen($alf);
for($i=0;$i<rand(5,8);$i++){
	$string.=$alf[rand(0,$sizeAlf-1)];
}
$_SESSION["captcha".$_GET['captcha_id']]=iconv('WINDOWS-1251','UTF-8',$string);
$im = imagecreate ($width, $height);
$black = imagecolorallocate ($im, 255,255, 255);
$size=strlen($string);
$block=$width/$size;
for($i=0;$i<3;$i++){
	$color=imagecolorallocate($im,rand(128,255),rand(0,255),rand(0,255));
	imageline($im,0,rand(0,$height),$width-1,rand(0,$height),$color);
}
for($i=0;$i<$size;$i++){
	$color=imagecolorallocate($im,rand(0,255),rand(0,128),rand(0,128));
	imagettftext ($im, 28, rand(-20,20), $i*$block+5, 62, $color, $font, iconv('WINDOWS-1251','UTF-8', $string[$i]));
}
for($i=0;$i<3;$i++){
	$color=imagecolorallocate($im,rand(128,255),rand(0,255),rand(0,255));
	imageline($im,0,rand(0,$height),$width-1,rand(0,$height),$color);
}
$str="¬ведите код: ";
$str=iconv('WINDOWS-1251','UTF-8', $str);
$color=imagecolorallocate($im,0,0,0);
imagettftext ($im, 28, 0, 30, 32, $color, $font, $str);
imagegif ($im);
imagedestroy ($im);
?>