<h2 id="comments_head"><?php echo translate_span('comments_head'); ?> <i class="icon-red icon-heart"></i></h2>
<div id="comments" data-href="<?php echo str_replace('&', '&amp;', current_url()); ?>">
	<div id="facebook_section">
<?php
include template('fb_comments');
?>
	</div>
	<div id="google_section">
<?php
include template('g_comments');
?>
	</div>
</div>