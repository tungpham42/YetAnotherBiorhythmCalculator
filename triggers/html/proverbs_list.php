<?php
require realpath($_SERVER['DOCUMENT_ROOT']).'/includes/init.inc.php';
if (isset($_GET['page']) && isset($_GET['lang'])) {
	echo list_proverbs($_GET['page'],$_GET['lang']);
}
?>