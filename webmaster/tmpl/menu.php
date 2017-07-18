<div class="navbar navbar-static">
<div class="navbar-inner">
<div class="container">
<a class="brand" href="<?= base_url() ?>">Webmaster Tools</a>
<ul class="nav" role="navigation">
<li class="dropdown">
<a id="drop1" href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">Statistics <b class="caret"></b></a>
<ul class="dropdown-menu" role="menu" aria-labelledby="drop1">
<li role="presentation"><a role="menuitem" tabindex="-1" href="<?= create_url(array($routes['alexa'])) ?>">Alexa Statistics</a></li>
<li role="presentation"><a role="menuitem" tabindex="-1" href="<?= create_url(array($routes['social'])) ?>">Social Analytics</a></li>
<li role="presentation"><a role="menuitem" tabindex="-1" href="<?= create_url(array($routes['catalog'])) ?>">Directory Listing Checker</a></li>
<li role="presentation"><a role="menuitem" tabindex="-1" href="<?= create_url(array($routes['diagnostic'])) ?>">Website Diagnostic</a></li>
<li role="presentation"><a role="menuitem" tabindex="-1" href="<?= create_url(array($routes['location'])) ?>">Domain Location</a></li>
<li role="presentation"><a role="menuitem" tabindex="-1" href="<?= create_url(array($routes['search'])) ?>">Indexed Pages</a></li>
<li role="presentation"><a role="menuitem" tabindex="-1" href="<?= create_url(array($routes['backlinks'])) ?>">Backlinks</a></li>
</ul>
</li>
<li class="dropdown">
<a href="#" id="drop2" role="button" class="dropdown-toggle" data-toggle="dropdown">SEO Toolkit <b class="caret"></b></a>
<ul class="dropdown-menu" role="menu" aria-labelledby="drop2">
<li role="presentation"><a role="menuitem" tabindex="-1" href="<?= create_url(array($routes['suggest'])) ?>">Google Suggestion Tool</a></li>
<li role="presentation"><a role="menuitem" tabindex="-1" href="<?= create_url(array($routes['alexacomparison'])) ?>">Alexa Comparison Tool</a></li>
<li role="presentation"><a role="menuitem" tabindex="-1" href="<?= create_url(array($routes['antispam'])) ?>">Antispam Protector</a></li>
<li role="presentation"><a role="menuitem" tabindex="-1" href="<?= create_url(array($routes['metatags'])) ?>">Meta Tags Generator</a></li>
<li role="presentation"><a role="menuitem" tabindex="-1" href="<?= create_url(array($routes['ogproperties'])) ?>">Og Properties Generator</a></li>
<li role="presentation"><a role="menuitem" tabindex="-1" href="<?= create_url(array($routes['password'])) ?>">Password Generator</a></li>
<li role="presentation"><a role="menuitem" tabindex="-1" href="<?= create_url(array($routes['hash'])) ?>">MD5/SHA-1 Hashing</a></li>
<li role="presentation"><a role="menuitem" tabindex="-1" href="<?= create_url(array($routes['duplicate'])) ?>">Duplicate Remover</a></li>
<li role="presentation"><a role="menuitem" tabindex="-1" href="<?= create_url(array($routes['htmlencoder'])) ?>">HTML Encoder</a></li>
<li role="presentation"><a role="menuitem" tabindex="-1" href="<?= create_url(array($routes['timeconverter'])) ?>">UNIX Time Converter</a></li>
<li role="presentation"><a role="menuitem" tabindex="-1" href="<?= create_url(array($routes['textlength'])) ?>">Text Length</a></li>
</ul>
</li>
<li class="dropdown">
<a href="#" id="drop2" role="button" class="dropdown-toggle" data-toggle="dropdown">Domainer <b class="caret"></b></a>
<ul class="dropdown-menu" role="menu" aria-labelledby="drop2">
<li role="presentation"><a role="menuitem" tabindex="-1" href="<?= create_url(array($routes['whois'])) ?>">Whois Lookup</a></li>
<li role="presentation"><a role="menuitem" tabindex="-1" href="<?= create_url(array($routes['dns'])) ?>">DNS Record Lookup</a></li>
<li role="presentation"><a role="menuitem" tabindex="-1" href="<?= create_url(array($routes['headers'])) ?>">HTTP Headers</a></li>
</ul>
</li>
<li><a href="mailto:<?= ConfigFactory::load("app")->admin_email ?>">Contact</a></li>
</ul>
</div>
</div>
</div>