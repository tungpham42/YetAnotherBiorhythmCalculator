<h4><?= $domain ?> Headers</h4>
<table class="table table-striped">
<thead>
<tr>
<th>Name</th>
<th>Value</th>
</tr>
</thead>
<tbody>
<? foreach($data['headers'] as $name => $value): ?>
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
<? if(_v($_GET, 'rawhtml')): ?>
<h4><?= $domain ?> Raw HTML (<?= $data['size'] ?> KB)</h4>
<pre>
<?= htmlspecialchars($data['body']) ?>
</pre>
<? endif; ?>
