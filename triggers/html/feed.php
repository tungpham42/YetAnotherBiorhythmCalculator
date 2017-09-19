<?php
require realpath($_SERVER['DOCUMENT_ROOT']).'/includes/init_trigger.inc.php';
header('Content-Type: text/html; charset=utf-8');
if (isset($_GET['url'])) {
	load_rss_feed($_GET['url']);
}
?>