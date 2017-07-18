<div class="form-area thumbnail">
<div class="error alert alert-error"></div>

<form method="post" id="main">
<div class="control-group">
<label for="domain" class="control-label">Enter Domain</label>
<div class="controls">
<input type="text" id="domain" name="domain" placeholder="google.com" value="" class="input-block-level"/>
</div>
</div>

<? if(ConfigFactory::load("captcha")->$controller): ?>
<div class="control-group">
<div class="controls">
<label for="captcha" class="control-label">Captcha</label>
<input type="image" src="<?= base_url() ?>captcha/captcha.php" id="captcha_img" name="captcha_img" class="captcha-image"/>
<input type="text" id="captcha" name="captcha" class="input-block-level captcha" autocomplete="off"/>
<br/>
<span class="captcha-refresh">click to refresh</span>
</div>
</div>
<? endif; ?>

<button type="submit" id="go" class="btn btn-large btn-primary">Submit</button>
</form>
</div>