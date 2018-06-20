<div id="register_modal">
	<p><?php echo translate_span('register_modal'); ?></p>
	<form id="register_form" method="POST" class="top-left-corner top-right-corner bottom-left-corner bottom-right-corner" action="/member/register/">
		<div class="m-input-prepend top-left-corner top-right-corner">
			<span class="add-on top-left-corner"><?php echo translate_span('email'); ?></span>
			<input class="m-wrap required top-right-corner" tabindex="0" size="20" type="text" name="member_register_email" placeholder="john@example.com" tabindex="1" required>
		</div>
		<div class="m-input-prepend bottom-left-corner bottom-right-corner">
			<span class="add-on bottom-left-corner"><?php echo translate_span('fullname'); ?></span>
			<input class="m-wrap required bottom-right-corner" tabindex="0" size="20" type="text" name="member_register_fullname" placeholder="John Doe" tabindex="2" required>
		</div>
		<a id="register_form_submit" class="m-btn green top-left-corner top-right-corner bottom-left-corner bottom-right-corner" href="#" tabindex="7"><?php echo translate_button('try_it'); ?></a>
	</form>
</div>
<script>
manipulateRegisterModal();
</script>