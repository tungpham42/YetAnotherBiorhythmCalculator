<?php
require realpath($_SERVER['DOCUMENT_ROOT']).'/includes/init_trigger.inc.php';
header('Content-Type: text/html; charset=utf-8');
if (isset($_GET['dob']) && isset($_GET['diff']) && isset($_GET['dt_change']) && isset($_GET['partner_dob']) && isset($_GET['lang_code'])) {
	$embed_chart = new Chart($_GET['dob'],$_GET['diff'],0,$_GET['dt_change'],$_GET['partner_dob'],$_GET['lang_code']);
	$embed_chart->render_embed_chart();
}
?>