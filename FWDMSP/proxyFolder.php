<?php

$url = "http".(!empty($_SERVER['HTTPS'])?"s":"")."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$url = substr($url, 0, strrpos($url, "/") + 1); 

$dir = (isset($_GET['dir']) ? urldecode($_GET['dir']) : null);
$decodedeURL = urldecode($dir);
$decodedeURL = ltrim($decodedeURL, "/");
$decodedeURL = rtrim($decodedeURL, "/");
$decodedeURL = $decodedeURL . "/";
$url .= $decodedeURL;

$dirHandle = opendir($dir); 
$imlBody .= '{"folder":[';
$ar = array();
$i = 0;
while ($file = readdir($dirHandle)) { 
      if(!is_dir($file) && strpos($file, '.mp3')){
	    $i++; 
		$ar[$i] = $file;
     } 
} 
sort($ar);
for($i=0;$i<count($ar);$i++){
	$imlBody .= '{"@attributes":{';
	$file = $ar[$i];
	$trackTitle;
	if($i < 9){
		$trackTitle = 'track 0' . ($i + 1);
	}else{
		$trackTitle = 'track ' . ($i + 1);
	}
	$imlBody .='"data-path":"' . $url.$file . '",';
	$imlBody .='"data-title":"' . $trackTitle . '"';
	if($i != count($ar) - 1){
		$imlBody .= "}},";
	}else{
		$imlBody .= "}}";
	}
	
	
 };
closedir($dirHandle);
$imlBody .= ']}';
echo $imlBody; 
?>