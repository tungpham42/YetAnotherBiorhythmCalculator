<?php
require realpath($_SERVER['DOCUMENT_ROOT']).'/includes/init_trigger.inc.php';
if (isset($_POST['unhashed'])) {
	header('Content-Type: text/plain; charset=utf-8');
	echo hash_pass($_POST['unhashed']);
}
?>