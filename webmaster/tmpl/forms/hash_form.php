<div class="form-area thumbnail">

<table class="table">
<tbody>
<tr>
<td width="20%">String</td>
<td><input type="text" id="input-string" class="input-xxlarge in-table"></td>
</tr>
<tr>
<td>Type</td>
<td>
<button onclick="document.getElementById('hash-string').value = hex_md5(document.getElementById('input-string').value)" class="btn btn-medium">MD5</button>
<button onclick="document.getElementById('hash-string').value = hex_sha1(document.getElementById('input-string').value)" class="btn btn-medium">SHA-1</button>
</td>
</tr>
<tr>
<td>Hash</td>
<td><input type="text" id="hash-string" class="input-xxlarge in-table"></td>
</tr>
</tbody>
</table>

</div>