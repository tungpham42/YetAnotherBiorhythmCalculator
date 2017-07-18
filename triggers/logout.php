<?php
ini_set('session.save_handler','files');
ini_set('session.save_path','/tmp');
session_name('NSH');
session_start();
session_destroy();
header('Location: '.$_SERVER['HTTP_REFERER'].'');
?>
