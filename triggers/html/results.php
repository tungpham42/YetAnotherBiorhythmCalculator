<?php
require realpath($_SERVER['DOCUMENT_ROOT']).'/includes/init_member.inc.php';
require realpath($_SERVER['DOCUMENT_ROOT']).'/includes/init_trigger.inc.php';
header('Content-Type: text/html; charset=utf-8');
$chart->render_results();
?>