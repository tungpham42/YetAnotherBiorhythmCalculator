<?php
if (isset($_GET['p']) && $_GET['p'] != 'home' && $_GET['p'] != 'member/home') {
	include template('scripts_top');
}
if (isset($_GET['q']) && $_GET['q'] != '') {
	include template('search_results');
} else if ($p == 'home') {
	if ($embed == 0 && !isset($hide_lang_bar)) {
		if (!is_birthday() && $show_ad):
			include template('banner_728x90');
		endif;
		include template('lang_bar');
		include template('home');
	} else if (isset($hide_lang_bar)) {
		if (!is_birthday() && $show_ad):
			include template('banner_728x90');
		endif;
		include template('home');
	} else if ($embed == 1) {
		include template('scripts_top');
		include template('embed');
	}
} else if ($p == 'intro') {
	if (!is_birthday() && $show_ad):
		include template('banner_728x90');
	endif;
	if ($embed == 0 && !isset($hide_lang_bar)) {
		include template('lang_bar');
		include template('intro');
		include template('keyboard');
	} else if (isset($hide_lang_bar)) {
		include template('intro');
	}
} else if ($p == 'vip') {
	include template('lang_bar');
	include template('vip');
	include template('keyboard');
}  else if ($p == 'login') {
	include template('login-page');
} else if ($p == 'rhythm') {
	if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1) {
		include template('admin/rhythm/index');
	} else {
		echo 'You are not authorized';
	}
} else if ($p == 'rhythm/create') {
	if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1) {
		include template('admin/rhythm/create');
	} else {
		echo 'You are not authorized';
	}
} else if ($p == 'rhythm/edit') {
	if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1) {
		include template('admin/rhythm/edit');
	} else {
		echo 'You are not authorized';
	}
} else if ($p == 'rhythm/delete') {
	if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1) {
		include template('admin/rhythm/delete');
	} else {
		echo 'You are not authorized';
	}
} else if ($p == 'user') {
	if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1) {
		include template('admin/user/index');
	} else {
		echo 'You are not authorized';
	}
} else if ($p == 'user/create') {
	if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1) {
		include template('admin/user/create');
	} else {
		echo 'You are not authorized';
	}
} else if ($p == 'user/edit') {
	if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1) {
		include template('admin/user/edit');
	} else {
		echo 'You are not authorized';
	}
} else if ($p == 'user/delete') {
	if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1) {
		include template('admin/user/delete');
	} else {
		echo 'You are not authorized';
	}
} else if ($p == 'hash') {
	if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1) {
		include template('admin/hash/index');
	} else {
		echo 'You are not authorized';
	}
} else if ($p == 'member') {
	if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1) {
		include template('admin/member/index');
	} else {
		echo 'You are not authorized';
	}
} else if ($p == 'member/register') {
	include template('lang_bar');
	include template('member/register');
	include template('keyboard');
	include template('explanation');
} else if ($p == 'member/login') {
	include template('lang_bar');
	include template('member/login');
	include template('keyboard');
	include template('explanation');
} else if ($p == 'member/home') {
	include template('lang_bar');
	include template('member/home');
} else if ($p == 'contact') {
	include template('lang_bar');
	include template('contact');
	include template('keyboard');
} else if ($p == 'author') {
	include template('lang_bar');
	include template('author');
	include template('keyboard');
	include template('music');
} else if ($p == 'proverbs') {
	include template('lang_bar');
	include template('clear');
	include template('proverbs_list');
	include template('keyboard');
} else if ($p == 'co') {
	include template('co/play');
} else if ($p == 'word') {
	include template('word/index');
} else if ($p == 'race' || $p == 'race/1') {
	include template('race/step1');
} else if ($p == 'race/2') {
	include template('race/step2');
} else if ($p == 'race/3') {
	include template('race/step3');
} else if ($p == 'race/single') {
	include template('race/single');
} else if ($p == 'bmi') {
	if (!is_birthday() && $show_ad):
		include template('banner_728x90');
	endif;
	include template('lang_bar');
	include template('bmi');
	include template('keyboard');
} else if ($p == 'lunar') {
	if (!is_birthday() && $show_ad):
		include template('banner_728x90');
	endif;
	include template('lunar');
	include template('keyboard_lunar');
} else if ($p == '2048') {
	include template('lang_bar');
	include template('clear');
	include template('2048');
} else if ($p == 'pong') {
	include template('pong');
} else if ($p == 'fish') {
	include template('lang_bar');
	include template('clear');
	include template('fish/index');
} else if ($p == 'tictactoe') {
	include $basepath.'/tic-tac-toe/play.php';
} else if ($p == 'donate') {
	include template('lang_bar');
	include template('clear');
	include template('donate');
	include template('clear');
	include template('keyboard');
} else if ($p == 'sponsor') {
	include template('sponsor');
} else if ($p == 'thank-you') {
	include template('thank_you');
} else if ($p == 'xin-cam-on') {
	include template('cam_on');
} else {
	echo 'Page not found';
}
include template('bottom');
?>