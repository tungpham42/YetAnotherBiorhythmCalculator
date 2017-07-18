<?php
$domain = strtolower(urldecode(_v($_GET, 'domain')));
//$domain = 'google.lt';
$sCaptcha = _v($_SESSION, 'captcha');
$gCaptcha = _v($_GET, 'captcha');

if(ConfigFactory::load("captcha")->$controller) {
	if(empty($sCaptcha) or empty($gCaptcha) or $sCaptcha != $gCaptcha) {
		echo Response::run(array("error"=>"Invalid captcha"));
		exit(0);
	}
}

if(!checkDomain($domain)) {
	echo Response::run(array("error"=>"Invalid domain"));
	exit(0);
}

$ip = gethostbyname($domain);
$long = ip2long($ip);

if($long == -1 OR $long === FALSE) {
	echo Response::run(array("error"=>sprintf('Could not reach host <strong>%s</strong>', $domain)));
	exit(0);
}

if($table && $data = Model::select($table, $domain)) {
	if(time() - strtotime($data['Modified']) > ConfigFactory::load("cache")->$controller) {
		$data = call_user_func($controller."update", $domain, $ip);
	}
} else {
	$data = call_user_func($controller."insert", $domain, $ip);
}

ob_start();
include ROOT."tmpl".DS."service".DS.$controller."_result.php";
$content = ob_get_contents();
ob_end_clean();
echo Response::run(array("html"=>$content));
exit(0);