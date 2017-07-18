<div id="register_modal">
	<p><?php echo translate_span('register_modal'); ?></p>
	<form id="register_form" method="POST" action="/member/register/">
		<div class="m-input-prepend">
			<span class="add-on"><?php echo translate_span('email'); ?></span>
			<input class="m-wrap required" tabindex="5" size="20" type="text" name="member_register_email" placeholder="john@example.com" tabindex="1" required>
		</div>
		<div class="m-input-prepend">
			<span class="add-on"><?php echo translate_span('fullname'); ?></span>
			<input class="m-wrap required" tabindex="6" size="20" type="text" name="member_register_fullname" placeholder="John Doe" tabindex="2" required>
		</div>
		<a id="register_form_submit" class="m-btn green" href="#" tabindex="7"><?php echo translate_button('try_it'); ?></a>
	</form>
</div>
<script>
manipulateRegisterModal();
</script>