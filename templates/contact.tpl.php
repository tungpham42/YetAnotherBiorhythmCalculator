<?php
$contact_errors = array();
$inputted_email = isset($_POST['contact_email']) ? $_POST['contact_email']: '';
$inputted_fullname = isset($_POST['contact_fullname']) ? $_POST['contact_fullname']: '';
$inputted_body = isset($_POST['contact_body']) ? $_POST['contact_body']: '';
if (isset($_POST['contact_submit'])) {
	if (!$_POST['contact_email'] || !$_POST['contact_fullname'] || !$_POST['contact_body']) {
		$contact_errors[] = translate_error('not_filled');
	} else {
		if (invalid_email($_POST['contact_email'])) {
			$contact_errors[] = translate_error('invalid_email');
		}
	}
	if (!count($contact_errors)) {
		email_contact($_POST['contact_email'], $_POST['contact_fullname'], $_POST['contact_body']);
	}
}
?>
<form id="contact_form" method="POST" action="/contact/">
	<div class="m-input-prepend">
		<?php echo translate_span('fullname','class: add-on label'); ?>
		<input id="contact_fullname" type="text" class="m-wrap" name="contact_fullname" placeholder="John Doe" required="required" value="<?php echo $inputted_fullname; ?>">
	</div>
	<div class="m-input-prepend">
		<?php echo translate_span('email','class: add-on label'); ?>
		<input id="contact_email" type="text" class="m-wrap" name="contact_email" placeholder="john@example.com" required="required" value="<?php echo $inputted_email; ?>">
	</div>
	<div class="m-input-prepend">
		<?php echo translate_span('content','class: add-on label'); ?>
		<textarea id="contact_body" class="m-wrap panel" name="contact_body" required="required"><?php echo $inputted_body; ?></textarea>
	</div>
	<input class="m-btn translate green" name="contact_submit" type="submit" data-lang-ja="<?php echo $button_interfaces['submit']['ja']; ?>" data-lang-zh="<?php echo $button_interfaces['submit']['zh']; ?>" data-lang-es="<?php echo $button_interfaces['submit']['es']; ?>" data-lang-ru="<?php echo $button_interfaces['submit']['ru']; ?>" data-lang-en="<?php echo $button_interfaces['submit']['en']; ?>" data-lang-vi="<?php echo $button_interfaces['submit']['vi']; ?>" value="<?php echo $button_interfaces['submit'][$lang_code]; ?>" />
<?php
include template('clear');
if (isset($_POST['contact_submit'])) {
	if ($contact_errors) {
		echo '<span class="error">'.implode('<br />',$contact_errors).'</span>';
	} elseif (!count($contact_errors)) {
		echo translate_span('submit_success','class: success');
	}
}
?>
</form>