<?php
require realpath($_SERVER['DOCUMENT_ROOT']).'/includes/init_trigger.inc.php';
if (isset($_GET['dob'])) {
	header('Content-Type: application/json; charset=utf-8');
	echo get_json_same_birthday($_GET['dob']);
}
?>