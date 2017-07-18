<script type="text/javascript">
function encode() {
	var source = $("#source").val();
	if(source.length == 0)
		return false;
	var unicode = Encoder.toUnicode(source);
	var hex = Encoder.toHexHTML(source);
	$("#unicode").val(
    "<script type=\"text/javascript\">//<![CDATA[\n" +
    "document.write(unescape('"+ unicode +"'));\n" +
    "//]]></script\>"
	);
	$("#hex").val(
    "<script type=\"text/javascript\">//<![CDATA[\n" +
    "document.write(unescape('"+ hex +"'));\n" +
    "//]]></script\>"
	);
	$("#result").slideDown();
}
</script>
<div class="form-area thumbnail">

<div class="control-group">
<label for="source" class="control-label">Text</label>
<div class="controls">
<textarea id="source" name="text" rows="5" class="input-block-level"></textarea>
<button onclick="encode()" class="btn btn-large btn-primary">Encode</button>
</div>
</div>


<div id="result" style="display:none;">

<div class="control-group">
<label for="for_website" class="control-label">Unicode</label>
<div class="controls">
<textarea id="unicode" name="text" rows="5" class="input-block-level" onclick="this.focus();this.select()" readonly="readonly">
</textarea>
</div>
</div>

<div class="control-group">
<label for="for_forum" class="control-label">Hex</label>
<div class="controls">
<textarea id="hex" name="text" rows="5" class="input-block-level" onclick="this.focus();this.select()" readonly="readonly">
</textarea>
</div>
</div>

</div>

</form>
</div>