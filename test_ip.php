<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']).'/includes/init.inc.php';
echo '<pre>';
print_r($geoip_reader->city($_SERVER['REMOTE_ADDR']));
echo '</pre>';