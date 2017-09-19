<?php
require realpath($_SERVER['DOCUMENT_ROOT']).'/includes/init.inc.php';
header('Content-Type: text/html; charset=utf-8');
if (isset($_POST['page']) && isset($_POST['keyword'])) {
	echo list_users($_POST['page'],$_POST['keyword']);
}
?>