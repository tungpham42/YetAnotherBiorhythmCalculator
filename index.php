<?php
error_reporting(-1);
ini_set('display_errors', 'On');
$basepath = realpath($_SERVER['DOCUMENT_ROOT']);
$template_path = $basepath.'/templates/';
//require $basepath.'/includes/redirect.inc.php';
//require $basepath.'/includes/header.inc.php';
require $basepath.'/includes/init.inc.php';
require $basepath.'/includes/template.inc.php';
//include $basepath.'/includes/compressor.inc.php';
?>
<!DOCTYPE html>
<html lang="<?php echo $lang_code; ?>">
<head>
<?php
include template('head');
if (isset($_GET['noanal'])):
	echo "";
else:
	include template('google_analytics');
endif;
include template('adsense_top');
include template('fb_pixel');
if ($hotjar):
	include template('hotjar');
endif;
if ($smartlook):
	include template('smartlook');
endif;
?>
</head>
<body lang="<?php echo $lang_code; ?>" class="<?php echo $body_class.(has_one_lang() ? ' one_lang': ''); ?>">
<?php
if (!isset($_GET['p']) || $_GET['p'] == 'home'):
	include template('sitelinks_searchbox');
endif;
include template('variables');
include template('img_desc');
if ($clicktale):
	include template('clicktale_top');
endif;
if (isset($_SESSION['loggedin'])):
	include template('toolbar');
endif;
//if (!is_birthday() && $show_ad):
//	include template('adsense_top');
//endif;
?>
	<!-- Start Header -->
	<header id="header">
		<div class="inner">
<?php
include template('header');
?>
		</div>
	</header>
	<!-- End Header -->
	<!-- Start Main -->
	<main id="main">
		<div id="content">
<?php
include template('router');
include template('highlight');
?>
		</div>
	</main>
	<!-- End Main -->
	<!-- Start Footer -->
	<footer id="footer">
		<div class="inner">
<?php
include template('footer');
?>
		</div>
	</footer>
	<!-- End Footer -->
	<div class="clear"></div>
<?php
include template('loading');
include template('to_top');
//if (!isset($hide_nav)):
	include template('register_modal');
//endif;
if (!isset($_GET['p']) && $embed == 0 || in_array($p, $navs)):
	if ($show_sumome):
		include template('sumome');
	endif;
	if ($show_addthis):
		include template('addthis');
	endif;
	//if (!is_birthday() && $show_ad):
	//if (!is_birthday()):
		include template('banner_160x600');
	//endif;
endif;
if ($clicktale):
	include template('clicktale_bottom');
endif;
include template('adsense_bottom');
include template('scripts_bottom');
include template('track');
include template('interstitial_geniee');
//if (is_birthday()):
//	include template('presents');
//else:
//	include template('snow');
//endif;
//if ($p == 'home' || $p == 'member/home'):
//	include template('music');
//endif;
//include template('clicky');
?>
</body>
</html>