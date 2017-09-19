<?php
require realpath($_SERVER['DOCUMENT_ROOT']).'/includes/init_trigger.inc.php';
header('Content-Type: text/html; charset=utf-8');
echo isset($_GET['keyword']) ? list_ajax_user_links('user-birthdates',$_GET['keyword']): list_ajax_user_links('user-birthdates');
?>