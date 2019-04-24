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
<?if($og_image){?>
<meta property="og:image" content="<?=$og_image?>" />
<?}?>
<?
echo $this->getScripts();
echo $this->getStyles();
?>
<link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" />
</head>
<body>
<div id='container'>
	
	
	
	<div id='middle'>
		<div id='cenright' >
			<div id='center' class='block_out'>
				<div class='block_in center_block_in'>
			
			<?if($h1){?><h1><?=$h1?></h1><?}?>	
			