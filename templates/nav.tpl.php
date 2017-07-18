<nav>
	<ul id="nav_buttons">
		<!--
		<li><a id="home_link" class="nav_button button home" href="/"><?php echo translate_button('home_page'); ?></a></li>
		-->
		<li><a id="member_link" class="nav_button button member keep" href="/member/login/"><i class="icon-<?php echo isset($_COOKIE['NSH:member']) ? 'user' : 'log-in'; ?>"></i></a></li>
		<li><a id="forum_link" class="nav_button button keep" href="http://cungrao.net/" target="_blank" title="CùngRao.net"><i class="icon-shopping-cart"></i></a></li>
		<li><a id="donation_link" class="nav_button button donation keep" href="/donate/"><i class="icon-donate"></i></a></li>
		<li><a id="intro_link" class="nav_button button intro keep" href="/introduction/"><?php echo translate_button('intro'); ?></a></li>
		<li><a id="apps_link" class="nav_button button apps keep" href="javascript:void(0);"><?php echo translate_button('apps'); ?></a></li>
		<li><a id="bmi_link" class="nav_button button bmi keep" href="/bmi/"><?php echo translate_button('bmi'); ?></a></li>
		<li><a id="lunar_link" class="nav_button button lunar" href="/xemngay/"><?php echo translate_button('lunar'); ?></a></li>
		<li><a target="_blank" id="survey_link" class="nav_button button survey" href="http://bit.ly/khaosat_nsh"><?php echo translate_button('survey'); ?></a></li>
		<li><a id="game_link" class="nav_button button game keep" href="/game/"><?php echo translate_button('game'); ?></a></li>
		<li><a id="contact_link" class="nav_button button contact keep" href="/contact/"><?php echo translate_button('contact'); ?></a></li>
		<!--
		<li><a id="blog_link" class="nav_button button keep" href="/blog/" target="_blank"><?php echo translate_button('blog'); ?></a></li>
		<li><a id="forum_link" class="nav_button button keep" href="http://diendan.nhipsinhhoc.vn" target="_blank"><?php echo translate_button('forum'); ?></a></li>
		-->
	</ul>
	<ul>
		<li id="nav_toggle"><a id="nav_toggle_button" class="nav_button button keep" href="javascript:void(0);"><i class="icon-arrow-down"></i></a></li>
	</ul>
</nav>