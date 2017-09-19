<?php
require realpath($_SERVER['DOCUMENT_ROOT']).'/includes/init.inc.php';
header('Content-Type: text/html; charset=utf-8');
if (isset($_GET['page']) && isset($_GET['lang'])) {
	echo list_proverbs($_GET['page'],$_GET['lang']);
}
?>