<h4><?= $domain ?> social statistics</h4>
<br/>
<table class="table table-striped">
<thead>
<tr>
<th><p class="table-stat"><span class="facebook"></span>Facebook</p></th>
<th></th>
<th><p class="table-stat"><span class="other-social"></span>Other</p></th>
<th></th>
</tr>
</thead>
<tbody>
<tr>
<td>Share count:</td>
<td><?= number_format($data['share_count'], 0, '.', '.') ?></td>
<td><p class="table-stat"><span class="gplus"></span>Google Plus:</p></td>
<td><?= number_format($data['gplus'], 0, '.', '.') ?></td>
</tr>

<tr>
<td>Like count:</td>
<td><?= number_format($data['like_count'], 0, '.', '.') ?></td>
<td><p class="table-stat"><span class="twitter"></span>Tweets:</p></td>
<td> <?= number_format($data['twitter'], 0, '.', '.') ?></td>
</tr>


<tr>
<td>Click count:</td>
<td><?= number_format($data['click_count'], 0, '.', '.') ?></td>
<td><p class="table-stat"><span class="pinterest"></span>Pints:</p></td>
<td><?= number_format($data['pinterest'], 0, '.', '.') ?></td>
</tr>


<tr>
<td>Comment count:</td>
<td><?= number_format($data['comment_count'], 0, '.', '.') ?></td>
<td><p class="table-stat"><span class="linkedin"></span>Linked In:</p></td>
<td><?= number_format($data['linkedin'], 0, '.', '.') ?></td>
</tr>


<tr>
<td>Total count:</td>
<td><?= number_format($data['total_count'], 0, '.', '.') ?></td>
<td><p class="table-stat"><span class="stumbleupon"></span>Stumbleupon:</p></td>
<td><?= number_format($data['stumbleupon'], 0, '.', '.') ?></td>
</tr>

<tr>
<td></td>
<td></td>
<td><p class="table-stat"><span class="delicious"></span>Delicious:</p></td>
<td><?= number_format($data['delicious'], 0, '.', '.') ?></td>
</tr>

</tbody>
</table>