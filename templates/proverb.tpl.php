<div id="proverb" data-ajax-triggered="no">
<?php
render_proverb($lang_code);
if (!isset($_COOKIE['NSH:member']) || $p == 'home') {
	include template('banner_300x250');
} else {
	include template('banner_300x250_alt');
}
include template('clear');
if ($show_donate) {
	include template('donate_top');
}
if ($show_sponsor) {
	include template('sponsor_top');
}
?>
</div>