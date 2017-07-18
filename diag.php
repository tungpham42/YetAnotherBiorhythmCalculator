<?php
$basepath = realpath($_SERVER['DOCUMENT_ROOT']);
require_once $basepath.'/includes/init.inc.php';
?>
<!DOCTYPE html>
<html lang="vi">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, height=device-height, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
		<meta name="description" content="<?php echo ((isset($_GET['dob']) && date('m-d',strtotime($_GET['dob'])) == date('m-d')) ? 'Xin Ơn trên phù hộ bạn': $meta_description); ?>">
		<meta name="keywords" content="nhipsinhhoc, nhip sinh hoc, boi nhip sinh hoc, nhip sinh hoc theo thang, bieu do nhip sinh hoc, nhịp sinh học, bói nhịp sinh học, biểu đồ nhịp sinh học, bieu do sinh hoc, xem ngay sinh, xem ngay, xem ngày sinh, xem ngày, tu vi, tử vi<?php echo (isset($_GET['fullname']) && $_GET['fullname'] != '') ? ', '.$_GET['fullname']: ''; ?>">
		<meta name="author" content="Tung Pham">
		<title><?php echo ((has_dob() && is_birthday()) ? 'Chúc mừng sinh nhật '.((isset($_GET['fullname'])) ? $_GET['fullname']: '') : $site_name.' - '.$title).((has_dob()) ? ' - '.$chart->render_meta_description(): ''); ?></title>
		<link rel="canonical" href="<?php echo str_replace('&', '&amp;', current_url()); ?>" />
		<link rel='stylesheet' href='/min/b=css&amp;f=fonts.css,normalize.css,install-button.css,jquery.listnav.css,bootstrap.css,m-styles.css,button_default.css,style_default.css,ui-blue/jquery-ui.css,nprogress.css,diag.css' />
		<link rel='stylesheet' href='/min/?f=css/print.css' media="print"/>
		<link rel="icon" href="/favicon.ico" />
		<!--
		<style>
		body {
			filter: grayscale(100%);
			-webkit-filter: grayscale(100%);
			-moz-filter: grayscale(100%);
			-o-filter: grayscale(100%);
			-ms-filter: grayscale(100%);
		}
		</style>
		-->
		<!--[if lt IE 9]>
		<script src="/min/?f=js/html5shiv.js"></script>
		<![endif]-->
	</head>
	<body lang="vi" class="<?php echo $body_class; ?>">
<?php
include $basepath.'/templates/clicktale_top.tpl.php';
if (isset($_SESSION['loggedin'])):
?>
		<style>
			#page_section {
				top: 30px;
			}
			#loading {
				top: 40px !important;
			}
		</style>
		<div id="toolbar">
			<ul class="toolbar">
				<li><a class="rhythm" href="?p=rhythm">Manage Rhythms</a></li>
				<li><a class="user" href="?p=user">Manage Users</a></li>
			</ul>
			<div class="right">
				<ul class="toolbar">
					<li>Welcome, <b>Tung</b></li>
					<li><a id="logout-btn" href="triggers/logout.php">Log out</a></li>
				</ul>
			</div>
		</div>
<?php
endif;
?>
		<section id="page_section">
			<!-- Start Header -->
			<header id="header">
<?php
include $basepath.'/templates/header.tpl.php';
?>
			</header>
			<!-- End Header -->
			<!-- Start Main -->
			<main id="main">
				<section id="content">
<?php
include $basepath.'/templates/scripts_top.tpl.php';
include $basepath.'/templates/router.tpl.php';
?>
				</section>
			</main>
			<!-- End Main -->
			<!-- Start Footer -->
			<footer id="footer">
<?php
include $basepath.'/templates/footer.tpl.php';
?>
			</footer>
			<!-- End Footer -->
			<div class="clear"></div>
		</section>
		<div id="loading">
			<img alt="nhịp sinh học" width="42" height="42" src="./images/ajax-<?php echo (has_dob() && is_birthday()) ? 'birthday': 'loader'; ?>.gif" />
		</div>
<?php
if (!isset($_GET['p'])):
	include $basepath.'/templates/addthis.tpl.php';
	if (!is_birthday()):
		include $basepath.'/templates/banner.tpl.php';
	endif;
endif;
include $basepath.'/templates/clicktale_bottom.tpl.php';
include $basepath.'/templates/scripts_bottom.tpl.php';
?>
	</body>
</html>