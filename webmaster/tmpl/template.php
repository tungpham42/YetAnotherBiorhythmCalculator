<!DOCTYPE html>
<html>
<head>
<link rel="icon" href="favicon.ico" type="image/x-icon" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>static/js/base.js"></script>
<script type="text/javascript" src="<?= base_url() ?>static/js/bootstrap.min.js"></script>
<? HtmlHead::outputJs() ?>
<link href="<?= base_url() ?>static/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen, projection">
<link href="<?= base_url() ?>static/css/style.css" rel="stylesheet" type="text/css" media="screen, projection" />
<? HtmlHead::outputCss() ?>
<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<meta name="keywords" content="<?= HtmlHead::getKeywords() ?>" />
<meta name="description" content="<?= HtmlHead::getDescription() ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<? HtmlHead::outputOg() ?>

<script type="text/javascript">
var base_url = '<?= base_url() ?>';
var amp = '<?= $amp; ?>';
</script>
<title><?= HtmlHead::getTitle() ?></title>
</head>
<body>
<div id="fb-root"></div>
<div id="wrap">
<? include ROOT."tmpl".DS."menu.php"; ?>
<div class="container-fluid">
	<div class="row-fluid" style="margin-bottom:60px">
		<div class="span2">
		<? include ROOT."tmpl".DS."left-menu.php" ?>
		</div>
		<div class="span10">
			<?= $content ?>
			<div id="push"></div>
		</div>
	</div>
</div><!-- container-fluid -->
</div><!-- #wrap -->

<div id="footer">
<div class="container">
<p class="muted credit" align="center">Developed by <strong><a href="http://php5developer.com">PHP5 Developer</a></strong></p>
</div>
</div><!--#footer-->
</body>
</html>