<?php
//$link_ad_position = rand(1,2);
//include template('clock');
//if ($link_ad_position == 1 && ((isset($_COOKIE['NSH:member']) && $_COOKIE['NSH:member'] == get_member_email()) || $_SESSION['loggedin'] == 1)) {
//	include template('link_ads_200x90');
//}
include template('clock');
if (!has_dob()) {
	include template('help');
}
include template('sleep_time');
//if ($link_ad_position == 2 && ((isset($_COOKIE['NSH:member']) && $_COOKIE['NSH:member'] == get_member_email()) || $_SESSION['loggedin'] == 1)) {
//	include template('link_ads_200x90');
//}
?>