<div id="bottom">
<?php
if (!isset($_GET['p']) && $embed == 0 || in_array($p, $navs)):
	if ($p != 'home' && $p != 'member/home'):
		if ($p != 'bmi' && $p != 'lunar' && $p != '2048' && $p != 'race' && $p != 'race/1' && $p != 'race/2' && $p != 'race/3'):
			include template('banner_matched_content');
			include template('youtube');
		endif;
		include template('install_app');
		include template('comments');
	endif;
	if (!is_birthday()):
		if ($show_donate):
			include template('donate_bottom');
		endif;
		if ($show_sponsor):
			include template('sponsor_bottom');
		endif;
	endif;
endif;
?>
</div>