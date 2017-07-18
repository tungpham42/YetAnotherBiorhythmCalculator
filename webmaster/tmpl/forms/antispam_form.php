<script type="text/javascript">
$(document).ready(function(){
	$('#background-color').ColorPicker({
		onChange: function (hsb, hex, rgb) {
			$('#background-color div').css('backgroundColor', '#' + hex);
			$('input[name="background-color"]').val(hex);
		}
	});
	$('#text-color').ColorPicker({
		onChange: function (hsb, hex, rgb) {
			$('#text-color div').css('backgroundColor', '#' + hex);
			$('input[name="text-color"]').val(hex);
		}
	});
});
</script>

<div class="form-area thumbnail">
<div class="error alert alert-error"></div>

<form method="post" id="main">
<div class="control-group">
<label for="text" class="control-label">Enter Text</label>
<div class="controls">
<textarea id="text" name="text" rows="3" class="input-block-level"></textarea>
</div>
</div>

<div class="control-group">
<label for="font-size" class="control-label">Font-size</label>
<div class="controls">
<select name="font-size">
	<? foreach(ConfigFactory::load("antispam")->font_size as $size) : ?>
  <option value="<?= $size ?>"><?= $size ?></option>
	<? endforeach; ?>
</select>
</div>
</div>

<div class="control-group">
<label for="font-family" class="control-label">Font-family</label>
<div class="controls">
<select name="font-family">
	<? foreach(ConfigFactory::load("antispam")->font_family as $family) : ?>
  <option value="<?= $family ?>"><?= ucfirst($family) ?></option>
	<? endforeach; ?>
</select>
</div>
</div>

<div class="control-group" style="float:left">
<span>Background color</span>
<div id="background-color">
<div style="background-color: #<?= ConfigFactory::load("antispam")->background ?>"></div>
</div>
</div>

<div class="control-group" style="float:left; margin:0 0 20px 80px;">
<span>Text color</span>
<div id="text-color">
<div style="background-color: #<?= ConfigFactory::load("antispam")->text_color ?>"></div>
</div>
</div>

<div class="clearfix"></div>

<input type="hidden" name="background-color" value="<?= ConfigFactory::load("antispam")->background ?>"/>
<input type="hidden" name="text-color" value="<?= ConfigFactory::load("antispam")->text_color ?>"/>


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