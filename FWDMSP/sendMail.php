<?php

	$to = $_GET['mail'];
	$subject = 'Download Link from MP3 Sticky Player';
	$name = $_GET['name'];
	$path = $_GET['path'];
	$path = ltrim($path, "/");
	
	if(strpos($path, "soundcloud.com") === false ){ 
		$url = "http".(!empty($_SERVER['HTTPS'])?"s":"")."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		$url = substr($url, 0, strrpos($url, "sendMail.php"));
		//if(strpos($path, "://") === false && strpos($path, "/") !== false){
		//	$path = substr($path,  strpos($path, "/") + 1);
		//}
		$url .= "downloader.php?path=". $path ."&name=" . $name;
	}else{
		$url = $path;
	}
	
	$header = "From: do-not-reply@yourdomain.com\r\n"; 
	$message = 'Mp3 file called "'. $name .'" can be downloaded at the following link: '. $url;
	
	if(!mail($to, $subject, $message, $header)){
		echo('error');
	}else{
		echo('sent');
	}
?>