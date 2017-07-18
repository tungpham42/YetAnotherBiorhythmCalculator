<script type="text/javascript">
$(document).ready(function(){	
	$("#generate").click(metataggenerator);
	$("#resetform").click(function(){
		$('#metatags_form')[0].reset();
		$('#result').val('').hide();
	});
});
</script>


<div class="form-area thumbnail">
<div class="error alert alert-error"></div>

<form method="post" id="metatags_form">
<table class="table table-striped table-condensed">
<thead>
<tr>
<th width="30%">Meta tag</th>
<th>Value</th>
</tr>
</thead>
<tbody>

<tr>
<td>
<label for="meta_keywords" class="control-label">Keywords:</label>
</td>
<td>
<input type="text" id="meta_keywords" name="keywords" value="" class="input-block-level"/>
</td>
</tr>

<tr>
<td>
<label for="meta_description" class="control-label">Description:</label>
</td>
<td>
<input type="text" id="meta_description" name="description" value="" class="input-block-level"/>
</td>
</tr>

<tr>
<td>
<label for="meta_author" class="control-label">Author:</label>
</td>
<td>
<input type="text" id="meta_author" name="author" value="" class="input-block-level"/>
</td>
</tr>

<tr>
<td>
<label for="meta_copyright" class="control-label">Copyright:</label>
</td>
<td>
<input type="text" id="meta_copyright" name="copyright" value="" class="input-block-level"/>
</td>
</tr>

<tr>
<td>
<label for="meta_robots" class="control-label">Robots:</label>
</td>
<td>
<select id="meta_robots" name="robots">
<? foreach(ConfigFactory::load("metagen")->robots as $robot) : ?>
<option value="<?= $robot ?>"><?= $robot ?></option>
<? endforeach; ?>
</select>
</td>
</tr>

<tr>
<td>
<label for="meta_charset" class="control-label">Charset:</label>
</td>
<td>
<select id="meta_charset" name="charset">
<? foreach(ConfigFactory::load("metagen")->charset as $charset) : ?>
<option value="<?= $charset ?>"><?= $charset ?></option>
<? endforeach; ?>
</select>
</td>
</tr>

<tr>
<td>
<label for="meta_chache" class="control-label">Cache:</label>
</td>
<td>
<select id="meta_chache" name="cache">
<? foreach(ConfigFactory::load("metagen")->cache as $cache) : ?>
<option value="<?= $cache ?>"><?= $cache ?></option>
<? endforeach; ?>
</select>
</td>
</tr>

<tr>
<td>
<label for="meta_language" class="control-label">Language:</label>
</td>
<td>
<select id="meta_language" name="language">
<? foreach(ConfigFactory::load("metagen")->language as $id => $language) : ?>
<option value="<?= $id ?>"><?= $language ?></option>
<? endforeach; ?>
</select>
</td>
</tr>

<tr>
<td>
<label for="meta_refresh" class="control-label">Refresh To:</label>
</td>
<td>
<input type="text" class="input-large" name="refresh">
<span class="help-inline">After&nbsp;</span>
<input type="text" class="input-mini" name="after">
<span class="help-inline">Secs</span>
</td>
</tr>

<tr>
<td>
<label for="meta_expires" class="control-label">Expires:</label>
</td>
<td>
<input type="text" id="meta_expires" name="expires" placeholder="<?= ConfigFactory::load("metagen")->expires ?>" class="input-block-level"/>
</td>
</tr>

<tr>
<td>
<label for="meta_revisist" class="control-label">Revisit after:</label>
</td>
<td>
<input type="text" id="meta_revisist" class="input-mini" name="revisist">
<select name="period" class="input-small">
<? foreach(ConfigFactory::load("metagen")->revisit as $revisit) : ?>
<option value="<?= $revisit ?>"><?= $revisit ?></option>
<? endforeach; ?>
</select>
</td>
</tr>
</tbody>
</table>
<button id="generate" class="btn btn-large btn-primary" type="button">Generate</button>
<button id="resetform" class="btn btn-large" type="button">Reset</button>

</form>

<br/>
<textarea id="result" rows="10" onclick="this.focus();this.select()" readonly="readonly"></textarea>
</div>