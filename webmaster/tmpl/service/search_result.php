<h4><?= $domain ?> in Search Engines</h4>
<br/>
<table class="table table-striped">
<thead>
<tr>
<th>Search Engine</th>
<th>Pages</th>
</tr>
</thead>
<tbody>
<tr>
<td><p class="table-stat"><span class="google"></span>Google:</p></td>
<td><?= number_format($data['google'], 0, '.', '.') ?></td>
</tr>


<tr>
<td><p class="table-stat"><span class="yahoo"></span>Yahoo:</p></td>
<td><?= number_format($data['yahoo'], 0, '.', '.') ?></td>
</tr>

<tr>
<td><p class="table-stat"><span class="bing"></span>Bing:</p></td>
<td><?= number_format($data['bing'], 0, '.', '.') ?></td>
</tr>


<!--tr>
<td><p class="table-stat"><span class="yandex"></span>Yandex:</p></td>
<td><?= number_format($data['yandex'], 0, '.', '.') ?></td>
</tr-->

</tbody>
</table>