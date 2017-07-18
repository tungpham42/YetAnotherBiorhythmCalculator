<?php
if(isAjaxRequest()) {
    $sCaptcha = _v($_SESSION, 'captcha');
    $gCaptcha = _v($_GET, 'captcha');
    if(ConfigFactory::load("captcha")->$controller) {
        if(empty($sCaptcha) or empty($gCaptcha) or $sCaptcha != $gCaptcha) {
            echo Response::run(array("error"=>"Invalid captcha"));
            exit(0);
        }
    }
    if(!_v($_GET, 'domain') AND !is_array($_GET['domain'])) {
        echo Response::run(array("error"=>"Invalid domain"));
        exit(0);
    }

    $domains = array();
    foreach($_GET['domain'] as $domain) {
        if(checkDomain($domain)) {
            $domains[] = $domain;
        }
    }

    if(empty($domains)) {
        echo Response::run(array("error"=>"Invalid domain"));
        exit(0);
    }

    $graph = _v($_GET, 'graph_type', 'r');
    $time = _v($_GET, 'time_span', '3m');
    if(!in_array($graph, array(
        "r", "n"
    ))) {
        echo Response::run(array("error"=>"Invalid graph type"));
        exit(0);
    }
    if(!in_array($time, array(
        "3m", "6m", "1y", "3y"
    ))) {
        echo Response::run(array("error"=>"Invalid time span"));
        exit(0);
    }

    $alexa_img = "http://traffic.alexa.com/graph?u=".implode("&u=", $domains)."&c=1&w=400&h=220&y={$graph}&r={$time}&b=e6f3fc";

    ob_start();
    include ROOT."tmpl".DS."service".DS."alexacomparison_result.php";
    $content = ob_get_contents();
    ob_end_clean();
    echo Response::run(array("html"=>$content));
    exit(0);
}
HtmlHead::setJs(array("alexacomparison" => base_url().'static/js/alexacomparison.js'));
include ROOT."tmpl".DS."service".DS."alexacomparison.php";

/*
Daily
http://traffic.alexa.com/graph?u=php5developer.com&amp;c=1&amp;w=400&amp;h=220&amp;y=r&amp;r=3m&amp;b=e6f3fc
Rank
http://traffic.alexa.com/graph?u=php5developer.com&amp;c=1&amp;w=400&amp;h=220&amp;y=n&amp;r=3m&amp;b=e6f3fc
*/