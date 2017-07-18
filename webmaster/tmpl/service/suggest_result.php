<h4>Suggested keywords for <strong><?php echo htmlspecialchars($keyword, ENT_QUOTES, "UTF-8") ?></strong></h4>
<?php if(!empty($suggestions)): ?>
<table class="table table-hover table-striped table-bordered">
<tbody>
<?php foreach($suggestions as $suggestion): ?>
<tr>
<td>
    <?php echo htmlspecialchars($suggestion, ENT_QUOTES, "UTF-8") ?>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php else: ?>
<div class="alert">
    Nothing found.
</div>
<?php endif; ?>