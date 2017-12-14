<?php
include template('dob_form');
include template('proverb');
include template('time');
include template('adsense_feed');
include template('scripts_top');
if (has_dob()) {
	if (isset($_COOKIE['NSH:member'])) {
		//include template('news');
	}
	include template('results');
} else if (!has_dob()) {
	include template('explanation_chart');
}
include template('keyboard');
include template('explanation');
include template('adsense_article');
include template('youtube');
//include template('feed_blog');
//include template('feed_forum');
include template('install_app');
include template('banner_matched_content');
include template('comments');
echo list_user_same_birthday_links('same-birthday-links');
echo list_user_birthday_links('birthday-links');
echo list_user_links('user-birthdates');
?>