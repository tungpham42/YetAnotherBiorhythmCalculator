<h4><?= $domain ?> location</h4>
<br/><br/>
<div class="row-fluid">
<div class="span4">
<span><strong>IP: </strong> <?= $data['ip'] ?></span><br/><br/>
<span><strong>Country Name:<br/> </strong><?= $data['country_name'] ?><? if($data['country_code'] != "XX") { ?> <img src="<?= base_url() ?>static/img/flags/<?= strtolower($data['country_code']) ?>.png" /><? } ?></span>
<br/><br/><span><strong>Region Name: </strong><?= $data['region_name'] ?></span>
<br/><br/><span><strong>City Name: </strong><?= $data['city'] ?></span>
</div>
<div class="span8">
<img class="thumbnail" src="http://maps.googleapis.com/maps/api/staticmap?center=<?= $data['latitude']?>,<?= $data['longitude']?>&amp;sensor=false&amp;zoom=5&amp;size=640x250&amp;markers=<?= $data['latitude']?>,<?= $data['longitude']?>
<? if(ConfigFactory::load('app')->gmaps_api_key != false) : ?>&amp;key=<?= ConfigFactory::load('app')->gmaps_api_key ?> <? endif; ?>" alt="GoogleMap" />
</div>
</div>
<br/>
<br/>