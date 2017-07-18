<h4><?= $domain ?> backlink count</h4>
<br/>
<table class="table table-striped">
    <thead>
    <tr>
        <th width="30%">Search engine</th>
        <th>Backlink count</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><p class="table-stat"><span class="google"></span>Google:</p></td>
        <td><?= number_format($data['Cnt'], 0, '.', '.') ?></td>
    </tr>

    </tbody>
</table>