<?php
if (isset($_COOKIE['NSH:member']) && !invalid_email($_COOKIE['NSH:member'])) {
	header('Location: http'.(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 's':'').'://'.$_SERVER['HTTP_HOST'].'/member/'.$_COOKIE['NSH:member'].'/');
	exit;
}
$member_login_errors = array();
$inputted_email = isset($_POST['member_login_email']) ? $_POST['member_login_email']: '';
if (isset($_POST['member_login_submit'])) {
	if (!$_POST['member_login_email'] || !$_POST['member_login_password']) {
		$member_login_errors[] = translate_error('not_filled');
	} else if (invalid_member($_POST['member_login_email'],$_POST['member_login_password'])) {
		$member_login_errors[] = translate_error('invalid_member');
	}
	if (!count($member_login_errors)) {
		setrawcookie('NSH:member',strtolower($_POST['member_login_email']),time()+(10*365*24*60*60),'/');
		header('Location: http'.(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 's':'').'://'.$_SERVER['HTTP_HOST'].'/member/'.strtolower($_POST['member_login_email']).'/');
		exit;
	}
}
?>
<form id="login_form" method="POST" action="/member/login/">
	<div class="m-input-prepend">
		<span class="add-on"><?php echo translate_span('email'); ?></span>
		<input class="m-wrap translate required" size="20" type="text" name="member_login_email" data-lang-ja="<?php echo $input_interfaces['email']['ja']; ?>" data-lang-zh="<?php echo $input_interfaces['email']['zh']; ?>" data-lang-es="<?php echo $input_interfaces['email']['es']; ?>" data-lang-ru="<?php echo $input_interfaces['email']['ru']; ?>" data-lang-en="<?php echo $input_interfaces['email']['en']; ?>" data-lang-vi="<?php echo $input_interfaces['email']['vi']; ?>" placeholder="<?php echo $input_interfaces['email'][$lang_code]; ?>" value="<?php echo $inputted_email; ?>" tabindex="1" required>
	</div>
	<div class="m-input-prepend">
		<span class="add-on"><?php echo translate_span('password'); ?></span>
		<input class="m-wrap translate required" size="20" type="password" name="member_login_password" data-lang-ja="<?php echo $input_interfaces['password']['ja']; ?>" data-lang-zh="<?php echo $input_interfaces['password']['zh']; ?>" data-lang-es="<?php echo $input_interfaces['password']['es']; ?>" data-lang-ru="<?php echo $input_interfaces['password']['ru']; ?>" data-lang-en="<?php echo $input_interfaces['password']['en']; ?>" data-lang-vi="<?php echo $input_interfaces['password']['vi']; ?>" placeholder="<?php echo $input_interfaces['password'][$lang_code]; ?>" tabindex="2" required>
	</div>
	<input class="m-btn translate green" name="member_login_submit" type="submit" data-lang-ja="<?php echo $button_interfaces['login']['ja']; ?>" data-lang-zh="<?php echo $button_interfaces['login']['zh']; ?>" data-lang-es="<?php echo $button_interfaces['login']['es']; ?>" data-lang-ru="<?php echo $button_interfaces['login']['ru']; ?>" data-lang-en="<?php echo $button_interfaces['login']['en']; ?>" data-lang-vi="<?php echo $button_interfaces['login']['vi']; ?>" value="<?php echo $button_interfaces['login'][$lang_code]; ?>" tabindex="3" />
	<h5><?php echo translate_span('not_yet_registered'); ?></h5>
	<a id="member_register" class="m-btn blue button_changeable" href="/member/register/" tabindex="4"><?php echo translate_button('register'); ?></a>
	<h5><a id="forget_password" href="mailto:admin@nhipsinhhoc.vn?subject=<?php echo $span_interfaces['forget_password'][$lang_code]; ?>&amp;body=<?php echo $span_interfaces['forget_password'][$lang_code].' : '.$span_interfaces['forget_password_hint'][$lang_code]; ?>&amp;cc=tung.42@gmail.com" target="_blank"><?php echo translate_span('forget_password'); ?></a></h5>
</form>
<div class="clear"></div>
<?php
if (isset($_POST['member_login_submit'])):
	if ($member_login_errors):
		echo '<span class="error">'.implode('<br />',$member_login_errors).'</span>';
	endif;
endif;
if (isset($_GET['registered']) && $_GET['registered'] == '✓'):
?>
<script>fbq('track', 'CompleteRegistration');</script>
<?php
endif;
?>
<script>
function changeLangForgetPassword(langCode) {
	var forgetPasswordTextArray = {
		'vi': '<?php echo $span_interfaces['forget_password']['vi']; ?>',
		'en': '<?php echo $span_interfaces['forget_password']['en']; ?>',
		'ru': '<?php echo $span_interfaces['forget_password']['ru']; ?>',
		'es': '<?php echo $span_interfaces['forget_password']['es']; ?>',
		'zh': '<?php echo $span_interfaces['forget_password']['zh']; ?>',
		'ja': '<?php echo $span_interfaces['forget_password']['ja']; ?>'
	};
	var forgetPasswordHintArray = {
		'vi': '<?php echo $span_interfaces['forget_password_hint']['vi']; ?>',
		'en': '<?php echo $span_interfaces['forget_password_hint']['en']; ?>',
		'ru': '<?php echo $span_interfaces['forget_password_hint']['ru']; ?>',
		'es': '<?php echo $span_interfaces['forget_password_hint']['es']; ?>',
		'zh': '<?php echo $span_interfaces['forget_password_hint']['zh']; ?>',
		'ja': '<?php echo $span_interfaces['forget_password_hint']['ja']; ?>'
	};
	$('a#forget_password').attr('href', 'mailto:admin@nhipsinhhoc.vn?subject='+forgetPasswordTextArray[langCode]+'&body='+forgetPasswordTextArray[langCode]+' : '+forgetPasswordHintArray[langCode]+'&cc=tung.42@gmail.com');
}
$(document).on('keyup', jwerty.event('1/num-1', function(e){
	if ($('#lang_bar').length && !$(e.target).is('input') && !$(e.target).is('textarea') && !$('#vi_toggle').hasClass('clicked') && !$('#vi_toggle').hasClass('disabled')) {
		changeLangForgetPassword('vi');
	}
})).on('keyup', jwerty.event('2/num-2', function(e){
	if ($('#lang_bar').length && !$(e.target).is('input') && !$(e.target).is('textarea') && !$('#en_toggle').hasClass('clicked') && !$('#en_toggle').hasClass('disabled')) {
		changeLangForgetPassword('en');
	}
})).on('keyup', jwerty.event('3/num-3', function(e){
	if ($('#lang_bar').length && !$(e.target).is('input') && !$(e.target).is('textarea') && !$('#ru_toggle').hasClass('clicked') && !$('#ru_toggle').hasClass('disabled')) {
		changeLangForgetPassword('ru');
	}
})).on('keyup', jwerty.event('4/num-4', function(e){
	if ($('#lang_bar').length && !$(e.target).is('input') && !$(e.target).is('textarea') && !$('#es_toggle').hasClass('clicked') && !$('#es_toggle').hasClass('disabled')) {
		changeLangForgetPassword('es');
	}
})).on('keyup', jwerty.event('5/num-5', function(e){
	if ($('#lang_bar').length && !$(e.target).is('input') && !$(e.target).is('textarea') && !$('#zh_toggle').hasClass('clicked') && !$('#zh_toggle').hasClass('disabled')) {
		changeLangForgetPassword('zh');
	}
})).on('keyup', jwerty.event('6/num-6', function(e){
	if ($('#lang_bar').length && !$(e.target).is('input') && !$(e.target).is('textarea') && !$('#ja_toggle').hasClass('clicked') && !$('#ja_toggle').hasClass('disabled')) {
		changeLangForgetPassword('ja');
	}
}));
$('#lang_bar').off('click','**').on('click', '#vi_toggle', function(){
	if (!$(this).hasClass('disabled')) {
		changeLangForgetPassword('vi');
	}
}).on('click', '#en_toggle', function(){
	if (!$(this).hasClass('disabled')) {
		changeLangForgetPassword('en');
	}
}).on('click', '#ru_toggle', function(){
	if (!$(this).hasClass('disabled')) {
		changeLangForgetPassword('ru');
	}
}).on('click', '#es_toggle', function(){
	if (!$(this).hasClass('disabled')) {
		changeLangForgetPassword('es');
	}
}).on('click', '#zh_toggle', function(){
	if (!$(this).hasClass('disabled')) {
		changeLangForgetPassword('zh');
	}
}).on('click', '#ja_toggle', function(){
	if (!$(this).hasClass('disabled')) {
		changeLangForgetPassword('ja');
	}
});
</script>