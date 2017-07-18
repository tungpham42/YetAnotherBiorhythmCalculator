<? foreach($data['dns'] as $record): ?>
<h4>Record type <?= $record['type'] ?></h4>
<table class="table table-striped">
<thead>
<tr>
<th>Name</th>
<th>Value</th>
</tr>
</thead>
<tbody>
<? foreach($record as $name => $value): ?>
<tr>
<td>
<?= $name ?>
</td>
<td>
<?= $value ?>
</td>
</tr>
<? endforeach; ?>
</tbody>
</table>
<br />
<? endforeach; ?>