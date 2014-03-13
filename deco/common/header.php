<!DOCTYPE html>
<html lang="<?php echo get_html_lang(); ?>">
<head>

<title><?php 
echo option('site_title'); echo isset($title) ? ' | ' . $title : ''; ?></title>

<!-- Meta -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="description" content="<?php echo option('description'); ?>" />

<?php echo auto_discovery_link_tags(); ?>

<?php fire_plugin_hook('public_head',array('view'=>$this)); ?>

<link rel="shortcut icon" type="image/x-icon" href="<?php echo img('favicon.ico');?>">
<link rel="apple-touch-icon" href="<?php echo img('apple_touch_icon.png');?>"/>


<!-- Get Core stylesheets -->
<?php 
queue_css_file('screen');
queue_css_file('jquery.fancybox-1.3.4');
queue_css_file('video-js');
queue_css_file('print'); 
echo head_css();
?>


<!-- get fancy fonts via Google Fonts API -->
<?php if (deco_get_fonts()!==false){
	echo'<link type="text/css" rel="stylesheet" href="http://fonts.googleapis.com/css?family='.deco_get_fonts().'"/>';
	}
?>

<!-- configurable styles -->
<style>
#site-title h2 a,#site-description h3,#footer p a{<?php echo deco_fonts_for_css('primary');?>}
h1,h2,h3,h4,h5,#site-title .tagline{<?php echo deco_fonts_for_css('secondary');?>}

<?php if(get_theme_option('no_radius')=='1'){?>
#mobile-menu-button a,#content,#site-title{border-radius: 0;}
<?php }?>

<?php if(get_theme_option('add_transparency')=='1'){?>
.small #mobile-menu-button a, .small #footer .navigation{opacity: .8;}
<?php }?>

<?php if(get_theme_option('darkonlight')=='1'){?>
#footer p, #footer p a, #primary-nav a{color:#555; text-shadow: 0 1px 2px #fafafa;}
<?php }?>

<?php echo (get_theme_option('custom_css')) ? get_theme_option('custom_css') : '' ;?>
</style>

<!-- JavaScripts -->

<?php
queue_js_file(array(
	'fancybox/jquery.easing-1.3.pack',
	'fittext.min',
	'fancybox/jquery.fancybox-1.3.4',
	'video-js/video',
	'swipe.min'
	));
echo head_js();
?>

<!-- Plugin Stuff -->

<?php echo fire_plugin_hook('public_header'); ?>

<!-- this hides the slideshow divs from users who do not have javascript enabled so they don't see a big mess -->
<noscript>
<style>#showcase,.showcase, h2.awkward{display:none; visibility:hidden;}</style>
</noscript>

</head>
<body<?php 
isset($bodyclass) ? $bodyclass=$bodyclass : $bodyclass=null;
$bodyclass .= ' '.deco_body_bg();
echo isset($bodyid) ? ' id="'.$bodyid.'"' : $bodyid=null;
echo isset($bodyclass) ? ' class="'.$bodyclass.'"' : $bodyclass=null;
?>>
<?php fire_plugin_hook('public_body', array('view'=>$this)); ?>

	<div id="wrap">
		
		<div id="mobile-menu-button"><a>Show Menu</a></div>
		<div id="primary-nav">
				<?php echo public_nav_main(); ?>
			<!-- search --> 
			<div id="search-container">
			    <?php echo search_form(); ?>
			    <?php echo link_to_item_search('Advanced Search'); ?>
			</div>
		</div><!-- end primary-nav -->
		<div id="header">
		<div id="site-title">
	
			<?php if(get_theme_option('logo_upload')){
				$logo= '<img alt="" src="'.WEB_ROOT.'/files/theme_uploads/'.get_theme_option('logo_upload').'" style="margin:0 auto;max-width:100%;">';
				echo link_to_home_page($logo);
				if(get_theme_option('hide_header_text')){
						$visibility='style="position: absolute; left: -999em;" ';
					}
				}
			?>	
			
			<h2 <?php echo isset($visibility) ? $visibility : null;?>><?php echo link_to_home_page(); ?></h2>
			<div <?php echo isset($visibility) ? $visibility : null;?>class="tagline"><?php echo deco_get_tagline();?></div>
		
		</div>
		</div>

		<div id="content">
		<?php fire_plugin_hook('public_content_top'); ?>