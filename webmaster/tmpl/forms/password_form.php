<script>
var passopts = {
	volves: '<?= ConfigFactory::load("password")->volves; ?>',
	hyphen: '<?= ConfigFactory::load("password")->hyphen; ?>',
	consonants: '<?= ConfigFactory::load("password")->consonants; ?>',
}
</script>
<div class="form-area thumbnail">
<div class="error alert alert-error"></div>
<form method="post">
<div class="input-append">
<input type="text" style="text-align:center;" id="password" onclick="this.focus();this.select()">
<input type="button" class="btn" id="regen" value="Generate">
</div>
<div class="well">
<table>
<tbody><tr>
<td width="200">

<? if(ConfigFactory::load("password")->letters): ?>
<label title="<?= ConfigFactory::load("password")->letters; ?>" class="checkbox">
<input type="checkbox" id="use_letters" value="<?= ConfigFactory::load("password")->letters; ?>" checked="checked"> Letters</label>
<? endif; ?>

<? if(ConfigFactory::load("password")->numbers): ?>
<label title="<?= ConfigFactory::load("password")->numbers; ?>" class="checkbox">
<input type="checkbox" id="use_numbers" value="<?= ConfigFactory::load("password")->numbers; ?>" checked="checked"> Numbers</label>
<? endif; ?>

<? if(ConfigFactory::load("password")->symbols): ?>
<label title="<?= ConfigFactory::load("password")->symbols; ?>" class="checkbox">
<input type="checkbox" id="use_specs" value="<?= ConfigFactory::load("password")->symbols; ?>"> Symbols</label>
<? endif; ?>

<? if(ConfigFactory::load("password")->special_chars): ?>
<label class="checkbox"><input type="checkbox" id="use_specified" value="1"> Special chars</label>
<span class="help-inline">
<input type="text" id="specified_chars" value="<?= ConfigFactory::load("password")->special_chars; ?>" disabled="disabled" class="input-mini">
</span>
<? endif; ?>

</td>
<td>
<label class="checkbox"><input type="checkbox" id="use_pronounceable" value="1"> Pronounceable</label>
<label class="checkbox"><input type="checkbox" id="use_hyphens" value="1"> Separate each </label>
<input type="text" class="input-mini" id="hyphen_length" value="2" disabled="disabled"> symbols.
<label>Length: <input type="text" class="input-mini" id="password_length" value="8"></label>
</td>
</tr>
</tbody>
</table>
</div>
</form>
</div>