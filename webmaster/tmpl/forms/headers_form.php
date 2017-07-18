<div class="form-area thumbnail">
<div class="error alert alert-error"></div>

<form method="post" id="main">
<div class="control-group">
<label for="domain" class="control-label"><strong>Enter Domain</strong></label>
<div class="controls">
<input type="text" id="domain" name="domain" placeholder="google.com" value="" class="input-block-level"/>
</div>
</div>

<div class="control-group">
<label for="domain" class="control-label"><strong>User-Agent</strong></label>
<div class="controls">
<select name="user-agent">
	<? foreach(ConfigFactory::load("browser") as $id => $browser) : ?>
  <option value="<?= $id ?>"><?= $id ?></option>
	<? endforeach; ?>
</select>
</div>
</div>

<div class="control-group">
<div class="controls">
<strong>HTTP version:</strong>
<label class="radio">
  <input type="radio" name="HTTP" value="1.1" checked>
  HTTP/1.1
</label>
<label class="radio">
  <input type="radio" name="HTTP" value="1.0">
  HTTP/1.0
</label>
</div>
</div>

<div class="control-group">
<div class="controls">
<strong>Request type:</strong>
<label class="radio">
  <input type="radio" name="request" value="GET" checked>
  GET
</label>

<label class="radio">
  <input type="radio" name="request" value="POST" >
  POST
</label>
</div>
</div>


<div class="control-group">
<div class="controls">
<label class="checkbox inline">
  <input type="checkbox" name="rawhtml"> Raw HTML view
</label>
<label class="checkbox inline">
  <input type="checkbox" name="gzip"> Accept-Encoding: gzip
</label>
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