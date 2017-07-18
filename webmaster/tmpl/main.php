<div class="dropdowns">
<div class="block">
<h1 class="block-header service-header">Webmaster Tools</h1>
<div class="block-wrap">
<img class="service-thumb" src="static/img/webmaster.png"/>
This application came as a collection of webmaster tools developed
by <a href="http://php5developer.com">PHP5 Developer</a> to help webmasters
with thier daily chores. You can find here a lot of useful tools which
will reduce your work time and make it easier. All tools are free. See the full list below.
<div class="clearfix"></div>

<div class="row-fluid">
<div class="span4">
<h4>Statistics</h4>
<ul class="service-list">
<li><a href="<?= create_url(array($routes['alexa'])) ?>">Alexa Statistics</a></li>
<li><a href="<?= create_url(array($routes['social'])) ?>">Social Analytics</a></li>
<li><a href="<?= create_url(array($routes['catalog'])) ?>">Directory Listing Checker</a></li>
<li><a href="<?= create_url(array($routes['diagnostic'])) ?>">Website Diagnostic</a></li>
<li><a href="<?= create_url(array($routes['location'])) ?>">Domain Location</a></li>
<li><a href="<?= create_url(array($routes['search'])) ?>">Indexed Pages</a></li>
<li><a href="<?= create_url(array($routes['backlinks'])) ?>">Backlinks</a></li>
</ul>

</div>
<div class="span4">
<h4>SEO Toolkit</h4>
<ul class="service-list">
<li><a href="<?= create_url(array($routes['suggest'])) ?>">Google Suggestion Tool</a></li>
<li><a href="<?= create_url(array($routes['alexacomparison'])) ?>">Alexa Comparison Tool</a></li>
<li><a href="<?= create_url(array($routes['antispam'])) ?>">Antispam Protector</a></li>
<li><a href="<?= create_url(array($routes['metatags'])) ?>">Meta Tags Generator</a></li>
<li><a href="<?= create_url(array($routes['ogproperties'])) ?>">Og Properties Generator</a></li>
<li><a href="<?= create_url(array($routes['password'])) ?>">Password Generator</a></li>
<li><a href="<?= create_url(array($routes['hash'])) ?>">MD5/SHA-1 Hashing</a></li>
<li><a href="<?= create_url(array($routes['duplicate'])) ?>">Duplicate Remover</a></li>
<li><a href="<?= create_url(array($routes['htmlencoder'])) ?>">HTML Encoder</a></li>
<li><a href="<?= create_url(array($routes['timeconverter'])) ?>">UNIX Time Converter</a></li>
<li><a href="<?= create_url(array($routes['textlength'])) ?>">Text Length</a></li>
</ul>
</div>
<div class="span4">
<h4>Domainer</h4>
<ul class="service-list">
<li><a href="<?= create_url(array($routes['whois'])) ?>">Whois Lookup</a></li>
<li><a href="<?= create_url(array($routes['dns'])) ?>">DNS Record Lookup</a></li>
<li><a href="<?= create_url(array($routes['headers'])) ?>">HTTP Headers</a></li>
</ul>
</div>
</div>

<br/><br/>
<? include ROOT."tmpl".DS."social.php"; ?>
<br/><br/><br/><br/><br/>
</div>
</div>
</div>

