<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<title><?=htmlspecialchars(strip_tags(str_replace("  "," ",$title)))?></title>
<?if($description){?>
<meta name="description" content="<?=htmlspecialchars(strip_tags(str_replace("  "," ",$description)))?>"  />
<?}?>
<?if($keywords){?>
<meta name="keywords" content="<?=htmlspecialchars(strip_tags(str_replace("  "," ",$keywords)))?>"  />
<?}?>
<?if($noindex){?>
<meta name="robots" content="noindex" />
<?}else{?>
<meta name="robots" content="all" />
<?}?>
<?if($og_type){?>
<meta property="og:type" content="<?=$og_type?>" />
<?}?>
<?if($og_title){?>
<meta property="og:title" content="<?=$og_title?>" />
<?}?>
<?if($og_image){?>
<meta property="og:image" content="<?=$og_image?>" />
<?}?>
<?if($og_url){?>
<meta property="og:url" content="<?=$og_url?>" />
<?}?>
<script src="/js/jquery-1.11.1.min.js"  type="text/javascript" ></script>
<script src="/js/jquery.cookie.js"  type="text/javascript" ></script>
<script src="/js/plugins/carousel.js" type="text/javascript" ></script>
<script src="/js/script.js" type="text/javascript" ></script>
<?if(user()->info('id')!=1){?>
<script src="/js/unc.js" type="text/javascript" ></script>
<?}?>

<?
echo $this->getScripts();
echo $this->getStyles();
?>
<?if(!$_COOKIE['ismobile']){?>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link href='/mediaquery.css' rel='stylesheet' type='text/css' />
<?}?>
<meta name="revisit-after" content="1 days" />
<link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" />


</head>
<body>
<div id='container' <?if($isMobile){?>style='margin-top:50px'<?}?>>
	
	
	
	<div id='middle'>
		<!--noindex--><div class='toprek' id='edc892cea4'></div><!--/noindex-->
		<div id='leftcolumn'>
			<!--noindex-->
			<div id='leftcolumnin'>
				<div id="M292711Composite609853"></div> 
				
			</div>
			<!--/noindex-->
		</div>
		<div id='cenright' >
			<div id='center' class='block_out'>
				<div class='block_in center_block_in'>
			<?if(\msg::get('error')){
				echo '<center class="messblock"><font color="red">'.\msg::get('error').'</font></center>';
			}?>
			<?if(\msg::get('captcha_error')){
				echo '<center class="messblock"><font color="red">'.\msg::get('captcha_error').'</font></center>';
			}?>
			<?if(\msg::get('success')){
				echo '<center class="messblock"><font color="green">'.\msg::get('success').'</font></center>';
			}?>
			<?if($h1){?><h1><?=$h1?></h1><?}?>	
			