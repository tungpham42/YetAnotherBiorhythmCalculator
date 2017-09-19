<?php
require realpath($_SERVER['DOCUMENT_ROOT']).'/includes/init_trigger.inc.php';
header('Content-Type: text/html; charset=utf-8');
echo $explanation_interfaces[$lang_code];
?>
<div class="clear"></div>