<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']).'/includes/init.inc.php';
if (isset($_GET['test']) && $_GET['test'] == 'yes') {
	test_email_daily_suggestion();
	echo 'tested!';
} else {
	echo 'wrong!!';
}
?>