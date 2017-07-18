<?php
if (!is_birthday()):
?>
<div id="link_ads">
<ins class="adsbygoogle" data-ad-client="ca-pub-3585118770961536" data-ad-slot="<?php echo has_dob() ? '6382372087' : '8476384081'; ?>"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</div>
<?php
endif;
?>
<span class="hide"><?php echo $title; ?></span>