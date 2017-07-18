<div class="form-area thumbnail">

<div class="control-group">
<label for="result" class="control-label">Result</label>
<div class="controls">
<img src="<?= $antispam_img ?>" />
</div>
</div>


<div class="control-group">
<label for="for_website" class="control-label">Link for website</label>
<div class="controls">
<textarea id="for_website" name="text" rows="3" class="input-block-level" onclick="this.focus();this.select()" readonly="readonly">
<a href="<?= create_url(array("antispam-protector")); ?>"><img src="<?= $antispam_img ?>" alt="Antispam" /></a>
</textarea>
</div>
</div>

<div class="control-group">
<label for="for_forum" class="control-label">Link for forum</label>
<div class="controls">
<textarea id="for_forum" name="text" rows="3" class="input-block-level" onclick="this.focus();this.select()" readonly="readonly">
[url=<?= create_url(array("antispam-protector")); ?>][img]<?= $antispam_img ?>[/img][/url]
</textarea>
</div>
</div>

</form>
</div>