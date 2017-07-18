<?php
if(isAjaxRequest()) {
    $config = ConfigFactory::load("antispam");
    $sCaptcha = _v($_SESSION, 'captcha');
    $gCaptcha = _v($_GET, 'captcha');
    if(ConfigFactory::load("captcha")->$controller) {
        if(empty($sCaptcha) or empty($gCaptcha) or $sCaptcha != $gCaptcha) {
            echo Response::run(array("error"=>"Invalid captcha"));
            exit(0);
        }
    }
	$fontsize = _v($_GET, "font-size");
	$text = _v($_GET, "text");
	$bg = _v($_GET, "background-color");
	$textbg = _v($_GET, "text-color");
	$family = _v($_GET, "font-family");
	
	if(mb_strlen($text) == 0) {
		echo Response::run(array("error"=>"Please input text"));
		exit(0);	
	}
	
	$crypt = new Crypt(ConfigFactory::load('crypt'));
	$e = $crypt->encode($text);	

	$antispam_img = create_url(array("hide"), "php").
		htmlspecialchars($amp).'iv='.rawurlencode(base64_encode($e['iv'])).
		'&amp;en='.rawurlencode(base64_encode($e['encrypted'])).
		'&amp;size='.rawurlencode($fontsize).
		'&amp;bg='.rawurlencode($bg).
		'&amp;textbg='.rawurlencode($textbg).
		'&amp;family='.rawurlencode($family)
		;
	
	ob_start();
	include ROOT."tmpl".DS."service".DS."antispam_result.php";
	$content = ob_get_contents();
	ob_end_clean();
	echo Response::run(array("html"=>$content));
	exit(0);	
}

HtmlHead::setCss(array("colorpicker" => base_url()."static/css/colorpicker.css"));
HtmlHead::setJs(array("colorpicker" => base_url()."static/js/colorpicker.js"));

include ROOT."tmpl".DS."service".DS."antispam.php";