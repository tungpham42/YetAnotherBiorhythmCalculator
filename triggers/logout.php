<?php
//ini_set('session.save_handler','files');
//ini_set('session.save_path','/tmp');
ini_set('session.save_handler', 'redis');
ini_set('session.save_path', 'tcp://127.0.0.1:6379');
session_name('NSH');
session_start();
session_destroy();
header('Location: '.$_SERVER['HTTP_REFERER'].'');
?>
