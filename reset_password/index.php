<?php
$_GET['p'] = 'reset_password';
require_once realpath($_SERVER['DOCUMENT_ROOT']).'/includes/init_member.inc.php';
$_GET['dob'] = get_member_dob();
$_GET['fullname'] = get_member_fullname();
require_once realpath($_SERVER['DOCUMENT_ROOT']).'/index.php';
?>