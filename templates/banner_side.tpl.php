<div class="banner left w160">
<?php
if (!isset($_COOKIE['NSH:member']) || $p == 'home' || in_array($p, $adsense_navs)):
?>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- NSH_res -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-3585118770961536"
     data-ad-slot="9710222034"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
<?php
else:
	render_ad('banner_160x600');
endif;
?>
</div>
<div class="banner right w160">
<?php
if (!isset($_COOKIE['NSH:member']) || $p == 'home' || in_array($p, $adsense_navs)):
?>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- NSH_res -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-3585118770961536"
     data-ad-slot="9710222034"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
<?php
else:
	render_ad('banner_160x600');
endif;
?>
</div>
<span class="hide"><?php echo $title; ?></span>