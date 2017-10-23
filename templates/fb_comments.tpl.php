<div id="fb-root"></div>
<script>
$(document).ready(function() {
	$.ajax({
		url: '//connect.facebook.net/<?php echo $lang_fb_apis[$lang_code]; ?>/sdk.js',
		type: 'GET',
		cache: true,
		global: false,
		dataType: 'script',
		async: true
	}).done(function(){
		FB.init({
			appId: '1244495622232184',
			cookie : true,
			xfbml : true,
			version : 'v2.10'
		});
	});
});
</script>
<div id="fb_page" class="fb-page facebook_plugin" data-href="https://www.facebook.com/NhipSinhHocPage/" data-tabs="timeline" data-width="300" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"></div>
<div class="fb-login-button facebook_plugin" data-width="300" data-max-rows="1" data-size="medium" data-show-faces="true" data-auto-logout-link="true"></div>
<div id="fb_like" class="fb-like facebook_plugin" data-width="300" data-href="" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
<div id="fb_comments" class="fb-comments" data-href="" data-width="100%" data-numposts="12" data-colorscheme="light"></div>
<script>
$('#fb_like, #fb_comments').attr('data-href',$('#comments').attr('data-href'));
</script>