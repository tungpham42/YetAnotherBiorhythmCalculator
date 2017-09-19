<?php
require realpath($_SERVER['DOCUMENT_ROOT']).'/includes/init_trigger.inc.php';
header('Content-Type: text/html; charset=utf-8');
if (isset($_GET['lang_code'])):
?>
<div id="facebook_section">
<?php
	include realpath($_SERVER['DOCUMENT_ROOT']).'/templates/fb_comments.tpl.php';
?>
</div>
<div id="google_section">
<?php
	include realpath($_SERVER['DOCUMENT_ROOT']).'/templates/g_comments.tpl.php';
?>
</div>
<?php
endif;
?>