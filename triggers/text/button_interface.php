<?php
require realpath($_SERVER['DOCUMENT_ROOT']).'/includes/init_trigger.inc.php';
$button_id = $_GET['button_id'];
header('Content-Type: text/plain; charset=utf-8');
echo $button_interfaces[$button_id][$lang_code];
?>