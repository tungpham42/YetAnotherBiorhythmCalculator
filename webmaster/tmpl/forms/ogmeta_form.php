<script type="text/javascript">
$(document).ready(function(){	
	$("#generate").click(ogpropertiesgenerator);
	$("#resetform").click(function(){
		$('#ogmeta_form')[0].reset();
		$("#ogmeta_table tbody tr:not(:first)").remove();
		$('#result').val('').hide();
	});
	
	$("#add").click(function(){
		$("#ogmeta_table tbody").append('<tr><td>' +
			'<input type="text" name="property[]" placeholder="property" class="input-medium"/>' +
			'</td><td>' +
			'<input type="text" name="content[]" placeholder="content" style="width:95%"/>' +
			'</td></tr>');
	});
});
</script>


<div class="form-area thumbnail">
<div class="error alert alert-error"></div>

<form method="post" id="ogmeta_form">
<table class="table table-striped table-condensed" id="ogmeta_table">
<thead>
<tr>
<th width="35%">Property</th>
<th>Content</th>
</tr>
</thead>
<tbody>

<tr>
<td>
<input type="text" name="property[]" placeholder="property" class="input-medium"/>
</td>
<td>
<input type="text" name="content[]" placeholder="content" style="width:95%"/>
</td>
</tr>

</tbody>
</table>

<button id="add" class="btn btn-large btn-success" type="button">Add+</button>
<button id="generate" class="btn btn-large btn-primary" type="button">Generate</button>
<button id="resetform" class="btn btn-large" type="button">Reset</button>

</form>

<br/>
<textarea id="result" rows="10" onclick="this.focus();this.select()" readonly="readonly"></textarea>
</div>