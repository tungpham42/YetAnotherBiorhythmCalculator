<?php
if (!is_birthday() && $show_ad):
?>
<div class="mobile_banner w336">
<ins class="adsbygoogle" data-ad-client="ca-pub-3585118770961536" data-ad-slot="9851189287"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</div>
<?php
include template('contributor_badge');
endif;
?>
<span class="hide"><?php echo $title; ?></span>