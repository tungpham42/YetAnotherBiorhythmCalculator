<?php
/*
diagnose = array (
	'caution', 'warning', 'untested', 'safe'
);
*/
class Diagnostic extends Request
{
    private static $mcUrl = 'http://www.siteadvisor.com/sites/%s';
    private static $glUrl = 'http://www.google.com/safebrowsing/diagnostic?site=%s&hl=en';
    private static $norUrl = 'http://safeweb.norton.com/report/show?url=%s';
    private static $avgUrl = 'http://www.avgthreatlabs.com/website-safety-reports/domain/%s';

    public static function google($domain)
    {
        $url = sprintf(self::$glUrl, $domain);
        if(!$response = parent::run($url))
            return 'untested';
        return (bool)preg_match("#This site is not currently listed as suspicious#ui", $response) ? 'safe' : 'caution';
    }

    public static function mcafee($domain)
    {
        static $inc = 0;
        $max = 3;
        $inc++;
        $state = array(
            'siteRed'=>'caution',
            'siteGray'=>'untested',
            'siteGreen'=>'safe',
        );
        $url = sprintf(self::$mcUrl, $domain);
        if(!$response = parent::run($url)) {
            return $inc >= $max ? 'untested' : self::mcafee($domain);
        }
        preg_match('#<div id="siteVerdict" class="(.*?)">#ui', $response, $matches);
        $diagnose = isset($matches[1]) ? trim($matches[1]) : 'untested';
        if(!isset($state[$diagnose])) {
            return $inc >= $max ? 'untested' : self::mcafee($domain);
        } else {
            return $state[$diagnose];
        }
        return isset($state[$diagnose]) ? $state[$diagnose] : 'untested';
    }

    public static function avg($domain) {
        $url = sprintf(self::$avgUrl, $domain);
        $diagnose = array(
            'green' => 'safe',
            'yellow' => 'warning',
            'orange' => 'warning',
            'red' => 'caution',
            'gray' => 'untested',
        );
        if(!$response = parent::run($url))
            return 'untested';

        preg_match('#<span id="linkscanner_icon" class="linkscanner (.+?)" (.+?)></span>#is', $response, $matches);
        $d = isset($matches[1]) ? trim($matches[1]) : 'untested';
        return isset($diagnose[$d]) ? $diagnose[$d] : 'untested';
    }

    public static function norton($domain) {
        $url = sprintf(self::$norUrl, $domain);
        $diagnose = array(
            'icoSafe' => 'safe',
            'icoUntested' => 'untested',
            'icoWarning' => 'warning',
            'icoCaution' => 'caution',
            'icoNSecured' => 'safe',
        );
        if(!$response = parent::run($url))
            return 'untested';
        preg_match('#<img(?:[^>]*)class="big_clip (.*?)"(?:[^>]*)>#is', $response, $matches);
        $d = isset($matches[1]) ? trim($matches[1]) : 'untested';
        return isset($diagnose[$d]) ? $diagnose[$d] : 'untested';
    }
}