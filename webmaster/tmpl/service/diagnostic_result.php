<h4><?= $domain ?> diagnostic</h4>
<br/>
<table class="table table-striped">
<thead>
<tr>
<th>Antivirus</th>
<th>Diagnose</th>
</tr>
</thead>
<tbody>
<tr>
<td><p class="table-stat"><span class="norton"></span>Norton:</p></td>
<td><img src="<?= base_url() ?>static/img/<?= $data['norton'] ?>.png" /></td>
</tr>

<tr>
<td><p class="table-stat"><span class="avg"></span>AVG:</p></td>
<td><img src="<?= base_url() ?>static/img/<?= $data['avg'] ?>.png" /></td>
</tr>


<tr>
<td><p class="table-stat"><span class="mcafee"></span>McAfee:</p></td>
<td><img src="<?= base_url() ?>static/img/<?= $data['mcafee'] ?>.png" /></td>
</tr>


<tr>
<td><p class="table-stat"><span class="google"></span>Google:</p></td>
<td><img src="<?= base_url() ?>static/img/<?= $data['google'] ?>.png" /></td>
</tr>

</tbody>
</table>