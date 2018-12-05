<div class="banner left w160">
<?php
if (!isset($_COOKIE['NSH:member']) || $p == 'home' || in_array($p, $adsense_navs)):
?>
<!-- <?php echo $adsense_codes[$_SERVER['SERVER_NAME']]['label']; ?>_160x600 -->
<ins class="adsbygoogle"
     style="display:inline-block;width:160px;height:600px"
     data-ad-client="ca-pub-3585118770961536"
     data-ad-slot="<?php echo $adsense_codes[$_SERVER['SERVER_NAME']]['160x600']; ?>"></ins>
<?php
else:
	echo "";
endif;
?>
</div>
<div class="banner right w160">
<?php
if (!isset($_COOKIE['NSH:member']) || $p == 'home' || in_array($p, $adsense_navs)):
?>
<!-- <?php echo $adsense_codes[$_SERVER['SERVER_NAME']]['label']; ?>_160x600 -->
<ins class="adsbygoogle"
     style="display:inline-block;width:160px;height:600px"
     data-ad-client="ca-pub-3585118770961536"
     data-ad-slot="<?php echo $adsense_codes[$_SERVER['SERVER_NAME']]['160x600']; ?>"></ins>
<?php
else:
	echo "";
endif;
?>
</div>
<span class="hide"><?php echo $title; ?></span>