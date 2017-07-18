<?php
require realpath($_SERVER['DOCUMENT_ROOT']).'/includes/init_trigger.inc.php';
$input_id = $_GET['input_id'];
header('Content-Type: text/plain; charset=utf-8');
echo $input_interfaces[$input_id][$lang_code];
?>