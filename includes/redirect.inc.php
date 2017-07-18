<?php
if  ( $_SERVER['HTTPS'] ) {
	$host = $_SERVER['HTTP_HOST'];
	$request_uri = $_SERVER['REQUEST_URI'];
	$good_url = "http://" . $host . $request_uri;
	header( "HTTP/1.1 301 Moved Permanently" );
	header( "Location: $good_url" );
	exit;
}
if (substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.') {
	header("HTTP/1.1 301 Moved Permanently");
	header('Location: http'.(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 's':'').'://'.substr($_SERVER['HTTP_HOST'], 4).$_SERVER['REQUEST_URI']);
	exit;
}
?>