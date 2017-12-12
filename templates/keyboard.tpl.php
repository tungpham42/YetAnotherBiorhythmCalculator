<section id="keyboard_shortcuts">
	<i class="center key dark icon-keyboard-wireless x2"></i>
	<div class="clear"></div>
	<h4 class="key dark"><?php echo translate_span('keyboard_shortcuts'); ?></h4>
<?php
if (!isset($hide_lang_bar)):
?>
	<p><span class="dark-keys"><kbd>1</kbd><kbd>2</kbd><kbd>3</kbd><kbd>4</kbd><kbd>5</kbd><kbd>6</kbd></span></p>
<?php
endif;
if ($p != 'member/register' && $p != 'member/login' && $p != 'intro' && $p != 'bmi' && $p != 'vip' && $p != 'donate' && $p != 'contact' && $p != 'author' && $p != 'proverbs'):
?>
	<p><span class="dark-keys"><kbd>A</kbd><kbd>S</kbd><kbd>D</kbd><kbd>W</kbd><kbd>E</kbd><kbd>R</kbd></span></p>
	<p><span class="dark-keys"><kbd>F</kbd><kbd>G</kbd><kbd>H</kbd><kbd>T</kbd><kbd>Y</kbd><kbd>U</kbd></span></p>
	<p><span class="dark-keys"><kbd>J</kbd><kbd>K</kbd><kbd>L</kbd><kbd>I</kbd><kbd>O</kbd><kbd>P</kbd></span></p>
	<h4 class="key light html"><?php echo translate_span('keyboard_shortcuts_long'); ?></h4>
<?php
else:
?>
	<h4 class="key light html"><?php echo translate_span('keyboard_shortcuts_short'); ?></h4>
<?php
endif;
?>
</section>