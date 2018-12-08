<nav>
	<ul id="nav_buttons">
		<!--
		<li><a id="home_link" class="nav_button button home" href="/"><?php echo translate_button('home_page'); ?></a></li>
		-->
		<li><a id="member_link" class="nav_button button member keep top-left-corner bottom-left-corner" href="/member/login/" title="<?php echo isset($_COOKIE['NSH:member']) ? 'Account' : 'Login'; ?>"><i class="icon-<?php echo isset($_COOKIE['NSH:member']) ? 'user' : 'log-in'; ?>"></i></a></li>
<?php
if ($_SERVER['SERVER_NAME'] == $second_domain):
?>
		<li><a id="bio_forum_link" class="nav_button button keep" href="https://forum.biorhythm.xyz/" target="_blank" title="Biorhythm Forum"><i class="icon-conversation"></i></a></li>
<?php
endif;
?>
		<li><a id="blog_link" class="nav_button button keep" href="/blog/" target="_blank" title="Blog"><i class="icon-book-open"></i></a></li>
		<li><a id="wiki_link" class="nav_button button keep" href="/wiki/" target="_blank" title="Wiki"><i class="social-wikipedia"></i></a></li>
		<li><a id="forum_link" class="nav_button button keep" href="https://cungrao.net/" target="_blank" title="CùngRao.net"><i class="icon-shopping-cart"></i></a></li>
		<li><a id="yoga_link" class="nav_button button keep" href="https://yogakhoe.com/" target="_blank" title="Yoga Khỏe"><i class="icon-heartbeat"></i></a></li>
		<li><a id="apps_link" class="nav_button button apps keep" href="javascript:void(0);" title="App"><i class="icon-download-alt"></i></a></li>
		<li><a id="donation_link" class="nav_button button donation keep" href="/donate/"><?php echo translate_span('donate'); ?></a></li>
		<li><a id="intro_link" class="nav_button button intro keep" href="/introduction/"><?php echo translate_button('intro'); ?></a></li>
		<!--
		<li><a id="apps_link" class="nav_button button apps keep" href="javascript:void(0);"><?php echo translate_button('apps'); ?></a></li>
		-->
		<li><a id="bmi_link" class="nav_button button bmi keep" href="/bmi/"><?php echo translate_button('bmi'); ?></a></li>
		<li><a id="lunar_link" class="nav_button button lunar" href="/xemngay/"><?php echo translate_button('lunar'); ?></a></li>
		<li><a target="_blank" id="survey_link" class="nav_button button survey" href="https://bit.ly/khaosat_nsh"><?php echo translate_button('survey'); ?></a></li>
		<li><a id="game_link" class="nav_button button game keep" href="/game/"><?php echo translate_button('game'); ?></a></li>
		<li><a id="contact_link" class="nav_button button contact keep top-right-corner bottom-right-corner" href="/contact/"><?php echo translate_button('contact'); ?></a></li>
		<!--
		<li><a id="blog_link" class="nav_button button keep" href="/blog/" target="_blank"><?php echo translate_button('blog'); ?></a></li>
		<li><a id="forum_link" class="nav_button button keep" href="https://diendan.nhipsinhhoc.vn" target="_blank"><?php echo translate_button('forum'); ?></a></li>
		-->
	</ul>
	<ul>
		<li id="nav_toggle"><a id="nav_toggle_button" class="nav_button button keep top-left-corner top-right-corner bottom-left-corner bottom-right-corner" href="javascript:void(0);"><i class="icon-menu-hamburger"></i></a></li>
	</ul>
</nav>