<script type="text/javascript">
$(document).ready(function(){
	$('#text').bind('paste, keyup', function (){
		var t = $("#text").val();
		$("#ch_cnt_sp").html(t.length);
		$("#ch_cnt").html(TextUtils.lengthWithoutSpaces(t));
		$("#line_cnt").html(TextUtils.lineCount(t));
		$("#wrd_cnt").html(TextUtils.wordCount(t));
		$("#lgn_ln").html(TextUtils.longestLine(t));
	});
});
</script>

<div class="form-area thumbnail">

<table class="table table-striped">
<thead>
<tr>
<th width="70%">Statistic</th>
<th>Value</th>
</tr>
</thead>

<tbody>
<tr>
<td>Character count (With spaces)</td>
<td id="ch_cnt_sp">0</td>
</tr>

<tr>
<td>Character count (Without spaces)</td>
<td id="ch_cnt">0</td>
</tr>

<tr>
<td>Line count</td>
<td id="line_cnt">0</td>
</tr>

<tr>
<td>Word count (Counts spaces between words)</td>
<td id="wrd_cnt">0</td>
</tr>

<tr>
<td>Longest line</td>
<td id="lgn_ln">0</td>
</tr>

</tbody>
</table>


<div class="control-group">
<label for="text" class="control-label">Input your text here &darr;</label>
<div class="controls">
<textarea id="text" rows="5" class="input-block-level"></textarea>
</div>
</div>
<br/>
</div>