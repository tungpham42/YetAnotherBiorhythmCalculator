<?php
Class SearchEngine extends Request
{
    private static $glUrl = 'http://www.google.com/search?q=site:%s';
    private static $biUrl = 'http://www.bing.com/search?q=site:%s';
    private static $yaUrl = 'http://search.yahoo.com/bin/search?p=site:%s';
    private static $yanUrl = 'http://webmaster.yandex.ru/check.xml?hostname=%s';
    //private static $googleBacklinkUrl = 'http://www.google.com/search?q=%22http%3a%2f%2f{Domain}%22+-site%3A{Domain}&filter=0';
    // private static $googleBacklinkUrl = 'https://www.google.com/search?q=%22{Domain}%22';
    private static $googleBacklinkUrl = 'http://www.google.com/search?q=%22{Domain}%22+-site%3A{Domain}&filter=0';

    public static function google($domain)
    {
        $url = sprintf(self::$glUrl, $domain);
        if(!$response = parent::run($url))
            return 0;
        return self::parseResults($response);
    }

    public static function googleBackLinks($domain) {
        $url = strtr(self::$googleBacklinkUrl, array(
            "{Domain}"=>$domain,
        ));
        if(!$response = parent::run($url))
            return 0;
        return self::parseResults($response);
    }

    private static function parseResults($html) {
        preg_match('#<div.*?id="resultStats"[^>]*>(.*?)<\/div[^>]*>#i', $html, $matches);
        return isset($matches[1]) ? (float)preg_replace("#\D#", "", html_entity_decode($matches[1])) : 0;
    }

    public static function bing($domain)
    {
        $url = sprintf(self::$biUrl, $domain);
        if(!$response = parent::run($url))
            return 0;
        preg_match('#<span.*?class="sb_count"[^>]*>(.*?)<\/span[^>]*>#i', $response, $matches);
        return isset($matches[1]) ? (float)preg_replace("#\D#", "", html_entity_decode($matches[1])) : 0;
    }

    public static function yahoo($domain)
    {
        $url = sprintf(self::$yaUrl, $domain);
        if(!$response = parent::run($url))
            return 0;
        preg_match('#<span[^>]*>[^<]*results<\/span[^>]*>#i', $response, $matches);
        return isset($matches[0]) ? (float)preg_replace("#\D#", "", html_entity_decode($matches[0])) : 0;
    }

    public static function yandex($domain) {
        if (!preg_match("#^www\.#i", $domain)) {
            $domain = "www.".$domain;
        }
        $url = sprintf(self::$yanUrl, $domain);
        if(!$response = parent::run($url))
            return 0;
        preg_match('#<div class="header g-line">(.+?)</div>#is', $response, $matches);
        return isset($matches[1]) ? (float)preg_replace("#\D#", "", html_entity_decode(strip_tags($matches[1]))) : 0;
    }
}

