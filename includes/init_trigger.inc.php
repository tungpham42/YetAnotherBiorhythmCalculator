<?php
ini_set('session.save_handler','files');
ini_set('session.save_path','/tmp');
require_once realpath($_SERVER['DOCUMENT_ROOT']).'/includes/database.inc.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']).'/includes/functions.inc.php';
init_timezone();