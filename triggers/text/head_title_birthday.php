<?php
require realpath($_SERVER['DOCUMENT_ROOT']).'/includes/init.inc.php';
header('Content-Type: text/plain; charset=utf-8');
echo ((has_dob() && has_birthday($dob,strtotime($_GET['time']))) ? birthday_title() : home_title()).' | '.$site_name;
?>