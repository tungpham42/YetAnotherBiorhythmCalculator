<?php
if(isAjaxRequest()) {
    $sCaptcha = _v($_SESSION, 'captcha');
    $gCaptcha = _v($_GET, 'captcha');
    $keyword = _v($_GET, 'keyword');
    $lg = substr(_v($_GET, 'lg'), 0, 2);

    if(ConfigFactory::load("captcha")->$controller) {
        if(empty($sCaptcha) or empty($gCaptcha) or $sCaptcha != $gCaptcha) {
            echo Response::run(array("error"=>"Invalid captcha"));
            exit(0);
        }
    }

    if(empty($keyword)) {
        echo Response::run(array("error"=>"Please enter at least one keyword"));
        exit(0);
    }
    if(empty($lg)) {
        echo Response::run(array("error"=>"Please specify language"));
        exit(0);
    }

    $suggestions = Suggest::google($keyword, $lg);
    ob_start();
    include ROOT."tmpl".DS."service".DS."suggest_result.php";
    $content = ob_get_contents();
    ob_end_clean();
    echo Response::run(array("html"=>$content));
    exit(0);
}

include ROOT."tmpl".DS."service".DS."suggest.php";