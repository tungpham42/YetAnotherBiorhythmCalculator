<h4><?= $domain ?> Alexa statistics</h4>
<table class="table table-striped">
<thead>
<tr>
<th>Name</th>
<th>Value</th>
</tr>
</thead>
<tbody>
<tr>
<td>Global Rank</td>
<td><?= number_format($data['rank'], 0, '.', '.') ?></td>
</tr>

<?php if(!empty($data['country_code']) AND !empty($data['country_name']) AND $data['country_rank'] > 0): ?>
<tr>
<td>Local Rank in <?= $data['country_name'] ?> <img src="<?= base_url() ?>static/img/flags/<?= strtolower($data['country_code']) ?>.png" /></td>
<td>
<?= number_format($data['country_rank'], 0, '.', '.') ?>
</td>
</tr>
<?php endif; ?>

<tr>
<td>Links in</td>
<td><?= number_format($data['linksin'], 0, '.', '.') ?></td>
</tr>

<tr>
<td>Review count</td>
<td><?= number_format($data['review_count'], 0, '.', '.') ?></td>
</tr>

<tr>
<td>Review average</td>
<td><?= $data['review_avg'] ?></td>
</tr>

</tbody>
</table>

<!--h5>Demographics</h5>
<div class="row-fluid">
<div style="position:relative; z-index:0;">
<object type="application/x-shockwave-flash" wmode="transparent" settings_file="http://www.alexa.com/amMap/ammap_settings.xml"
data_file="http://www.alexa.com/amMap/index.php?settings=<?= urlencode($domain) ?>" path="http://www.alexa.com/amMap/ammap/" bgcolor="#E9F0FF"
class="feedback-subject" name="ammap_c47ecf06f4988d9e69780b117869b0b5"
data="http://www.alexa.com/amMap/ammap.swf" width="100%" height="240px" id="ammap_c47ecf06f4988d9e69780b117869b0b5">
<param name="wmode" value="transparent"><param name="settings_file" value="http://www.alexa.com/amMap/ammap_settings.xml">
<param name="data_file" value="http://www.alexa.com/amMap/index.php?settings=<?= urlencode($domain) ?>php5developer.com">
<param name="path" value="http://www.alexa.com/amMap/ammap/">
<param name="flashvars" value="wmode=transparent&amp;settings_file=http://www.alexa.com/amMap/ammap_settings.xml&amp;data_file=http://www.alexa.com/amMap/index.php?settings=<?= urlencode($domain) ?>&amp;path=http://www.alexa.com/amMap/ammap/">
</object>
</div-->


<h5>Audience</h5>
<div class="row-fluid">
<img class="thumbnail" src="http://traffic.alexa.com/graph?&amp;w=320&amp;h=230&amp;o=f&amp;c=1&amp;y=t&amp;b=ffffff&amp;r=1m&amp;u=<?= urlencode($domain) ?>" alt="AlexaRank" />
</div>