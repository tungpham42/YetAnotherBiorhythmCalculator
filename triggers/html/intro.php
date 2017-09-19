<?php
require realpath($_SERVER['DOCUMENT_ROOT']).'/includes/init_trigger.inc.php';
header('Content-Type: text/html; charset=utf-8');
echo $introduction_interfaces[$lang_code];
?>
<h3><a class="right rotate" target="_blank" href="<?php echo $help_interfaces['wiki'][$lang_code]; ?>"><span data-title="Wikipedia">Wikipedia</span></a></h3>
<div class="clear"></div>