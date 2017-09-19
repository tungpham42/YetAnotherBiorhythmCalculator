<?php
require realpath($_SERVER['DOCUMENT_ROOT']).'/includes/init_trigger.inc.php';
header('Content-Type: text/html; charset=utf-8');
if (isset($_GET['lang_code'])) {
	load_news_feed($span_interfaces['health'][$_GET['lang_code']]);
	load_news_feed($span_interfaces['biorhythm'][$_GET['lang_code']]);
	load_news_feed(get_zodiac_from_dob($dob,$_GET['lang_code']));
	load_news_feed(date('Y',strtotime($dob)));
	load_news_feed();
}
?>