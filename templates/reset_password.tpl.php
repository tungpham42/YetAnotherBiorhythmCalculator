<?php
$email_errors = array();
$reset_password_errors = array();
$forgot_password_email = isset($_REQUEST['forgot_password_email']) ? $_REQUEST['forgot_password_email']: "";
$password = isset($_POST['password']) ? $_POST['password']: "";
$repeat_password = isset($_POST['repeat_password']) ? $_POST['repeat_password']: "";
if ($forgot_password_email != "" || isset($_POST['reset_password_submit'])) {
	if (invalid_email($forgot_password_email) || !taken_email($forgot_password_email)) {
		$email_errors[] = translate_error('invalid_email');
	}
	if (!count($email_errors)) {
		if (isset($_POST['reset_password_submit'])) {
			if ($password == "" || $repeat_password == "") {
				$reset_password_errors[] = translate_error('not_filled');
			} else {
				if (short_pass($password)) {
					$reset_password_errors[] = translate_error('short_pass');
				}
				if (long_pass($password)) {
					$reset_password_errors[] = translate_error('long_pass');
				}
				if (no_number_pass($password)) {
					$reset_password_errors[] = translate_error('no_number_pass');
				}
				if (no_letter_pass($password)) {
					$reset_password_errors[] = translate_error('no_letter_pass');
				}
				/*
				if (no_caps_pass($password)) {
					$reset_password_errors[] = translate_error('no_caps_pass');
				}
				if (no_symbol_pass($password)) {
					$reset_password_errors[] = translate_error('no_symbol_pass');
				}
				*/
				if (not_match_pass($password, $repeat_password)) {
					$reset_password_errors[] = translate_error('not_match_pass');
				}
			}
			if (!count($reset_password_errors)) {
				$member = load_member_from_email($forgot_password_email);
				edit_member($forgot_password_email,$member['fullname'],$password,$member['dob'],$member['lang']);
				email_edit_member($forgot_password_email,$member['fullname'],$password,$member['dob']);
			}
		}
?>
<form id="reset_password_form" method="POST" action="/reset_password/">
	<input type="hidden" name="forgot_password_email" value="<?php echo $forgot_password_email; ?>" />
	<div class="m-input-prepend">
		<span class="add-on"><?php echo translate_span('password'); ?></span>
		<input id="password" class="m-wrap translate required" size="20" type="password" name="password" data-lang-ja="<?php echo $input_interfaces['password']['ja']; ?>" data-lang-zh="<?php echo $input_interfaces['password']['zh']; ?>" data-lang-es="<?php echo $input_interfaces['password']['es']; ?>" data-lang-ru="<?php echo $input_interfaces['password']['ru']; ?>" data-lang-en="<?php echo $input_interfaces['password']['en']; ?>" data-lang-vi="<?php echo $input_interfaces['password']['vi']; ?>" placeholder="<?php echo $input_interfaces['password'][$lang_code]; ?>" tabindex="1" required>
	</div>
	<div class="m-input-prepend">
		<span class="add-on"><?php echo translate_span('repeat_password'); ?></span>
		<input id="repeat_password" class="m-wrap translate required" size="20" type="password" name="repeat_password" data-lang-ja="<?php echo $input_interfaces['repeat_password']['ja']; ?>" data-lang-zh="<?php echo $input_interfaces['repeat_password']['zh']; ?>" data-lang-es="<?php echo $input_interfaces['repeat_password']['es']; ?>" data-lang-ru="<?php echo $input_interfaces['repeat_password']['ru']; ?>" data-lang-en="<?php echo $input_interfaces['repeat_password']['en']; ?>" data-lang-vi="<?php echo $input_interfaces['repeat_password']['vi']; ?>" placeholder="<?php echo $input_interfaces['repeat_password'][$lang_code]; ?>" tabindex="2" required>
	</div>
	<input id="reset_password_submit" class="m-btn translate green" name="reset_password_submit" type="submit" data-lang-ja="<?php echo $button_interfaces['update']['ja']; ?>" data-lang-zh="<?php echo $button_interfaces['update']['zh']; ?>" data-lang-es="<?php echo $button_interfaces['update']['es']; ?>" data-lang-ru="<?php echo $button_interfaces['update']['ru']; ?>" data-lang-en="<?php echo $button_interfaces['update']['en']; ?>" data-lang-vi="<?php echo $button_interfaces['update']['vi']; ?>" value="<?php echo $button_interfaces['update'][$lang_code]; ?>" tabindex="3" />
</form>
<?php
		if (isset($_POST['reset_password_submit'])) {
			if ($reset_password_errors) {
				echo '<span class="error">'.implode('<br />',$reset_password_errors).'</span>';
			} else if (!count($reset_password_errors)) {
				echo translate_span('update_success','class: success');
			}
		}
	} else if ($email_errors) {
		echo '<span class="clear error">'.implode('<br />',$email_errors).'</span>';
	}
} else {
	echo '<span class="clear error">'.translate_error('invalid_input').'</span>';
}
?>