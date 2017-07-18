<div id="social-container" style="width:190px; margin: 0 auto;">
<!-- Facebook Like button -->
<script type="text/javascript">
(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>
<div style="float:left;" class="height-fix">
<div class="fb-like height-fix" data-send="false" data-href="<?= base_url() ?>" data-layout="box_count" data-show-faces="true"></div>
</div>
<!-- End of Facebook -->

<!-- Google Plus button -->
<script type="text/javascript">
(function() {
	var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
	po.src = 'https://apis.google.com/js/plusone.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();
</script>
<div style="float:left; margin-left:10px;" class="height-fix">
<div class="g-plusone height-fix" data-href="<?= base_url() ?>" data-size="tall"></div>
</div>
<!-- End of Google Plus button -->

<!-- Twitter Twit button -->
<script type="text/javascript">!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
<div style="float:left; margin-left:10px; margin-top:-1px;" class="height-fix">
<a href="https://twitter.com/share" id="tweet" class="twitter-share-button" data-count="vertical" data-url="<?= base_url() ?>"></a>
</div>
<!-- End of Twitter button -->
</div><!-- End of social container -->