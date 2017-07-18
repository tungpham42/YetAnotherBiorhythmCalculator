<?php
set_time_limit(0);
require_once realpath($_SERVER['DOCUMENT_ROOT']).'/includes/init.inc.php';
if (isset($_GET['v0d0i']) && $_GET['v0d0i'] == 'H@PPYV@LLEY') {
	email_daily_suggestion();
	echo 'success!';
} else if (isset($_GET['test']) && $_GET['test'] == 'yes') {
	test_email_daily_suggestion();
	echo 'tested!';
} else {
	echo 'wrong!!';
}
?>