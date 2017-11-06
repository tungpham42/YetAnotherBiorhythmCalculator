<?php
//ini_set('session.save_handler','files');
//ini_set('session.save_path','/tmp');
ini_set('session.save_handler', 'redis');
ini_set('session.save_path', 'tcp://127.0.0.1:6379');
require_once realpath($_SERVER['DOCUMENT_ROOT']).'/includes/database.inc.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']).'/includes/functions.inc.php';
init_timezone();