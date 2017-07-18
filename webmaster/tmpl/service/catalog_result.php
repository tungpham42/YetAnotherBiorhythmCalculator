<h4><?= $domain ?> in directories</h4>
<br/>
<table class="table table-striped">
<thead>
<tr>
<th>Catalog</th>
<th>Pages</th>
</tr>
</thead>
<tbody>
<tr>
<td><p class="table-stat"><span class="dmoz"></span>DMOZ:</p></td>
<td><?= number_format($data['dmoz'], 0, '.', '.') ?></td>
</tr>

<tr>
<td><p class="table-stat"><span class="yahoo"></span>Yahoo:</p></td>
<td><span class="<?= $data['yahoo'] >= 0 ? "success" : "failed"; ?>"></span></td>
</tr>


<tr>
<td><p class="table-stat"><span class="yandex"></span>Yandex:</p></td>
<td><?= number_format($data['yandex'], 0, '.', '.') ?></td>
</tr>


<tr>
<td><p class="table-stat"><span class="alexa"></span>Alexa:</p></td>
<td><span class="<?= $data['alexa'] >= 0 ? "success" : "failed"; ?>"></span></td>
</tr>

</tbody>
</table>