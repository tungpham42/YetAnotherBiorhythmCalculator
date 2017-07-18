<?php
require realpath($_SERVER['DOCUMENT_ROOT']).'/includes/init_trigger.inc.php';
if (isset($_GET['lang_code'])) {
	header('Content-Type: application/json; charset=utf-8');
	render_proverb_json($_GET['lang_code']);
}
?>