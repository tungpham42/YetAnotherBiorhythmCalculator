<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf8');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-Length: 44');
header('Connection: keep-alive');
header('X-Content-Type-Options: nosniff');
header('Status: 200 OK');
require 'jwt.php';
$token = array(
	'username' => 'youngbuff',
	'password' => 'P@ssword1234'
);
echo json_encode($token);
?>