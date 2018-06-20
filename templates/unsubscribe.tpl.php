<?php
$unsubscribe_errors = array();
$email = isset($_GET['email']) ? $_GET['email']: "";
$token = isset($_GET['token']) ? $_GET['token']: "";
if ($email != "" && check_token($email,$token)) {
	if (invalid_email($email) || !taken_email($email)) {
		$unsubscribe_errors[] = translate_error('invalid_email');
	}
	if (unsubscribed_email($email)) {
		$unsubscribe_errors[] = translate_error('unsubscribed_email');
	}
	if (!count($unsubscribe_errors)) {
		do_unsubscribe($email);
	} else if ($unsubscribe_errors) {
		echo '<span class="clear error">'.implode('<br />',$unsubscribe_errors).'</span>';
	}
} else {
	echo '<span class="clear error">'.translate_error('invalid_input').'</span>';
}