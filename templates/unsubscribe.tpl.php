<?php
$unsubscribe_errors = array();
$email = isset($_POST['email']) ? $_POST['email']: "";
if (isset($_POST['unsubscribe_submit'])) {
	if (invalid_email($email)) {
		$unsubscribe_errors[] = translate_error('invalid_email');
	}
	if (!taken_email($email)) {
		$unsubscribe_errors[] = translate_error('no_email');
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
?>