<?php
class SearchCatalog extends Request
{
    private static $dmUrl = 'http://www.dmoz.org/search?q=u:%s';
    private static $alUrl = 'http://data.alexa.com/data?cli=10&dat=snbamz&url=%s';
    private static $yahUrl = 'http://dir.search.yahoo.com/search?ei=UTF-8&h=c&p=%s';
    private static $yanUrl = 'http://yaca.yandex.ru/yca/cat/?text=%s';

    public static function alexa($domain)
    {
        $stat = array(
            'rank'=>0,
            'linksin'=>0,
            'review_count'=>0,
            'review_avg'=>0,
            'country_code'=>'',
            'country_name'=>'',
            'country_rank'=>0,
        );
        $alexa = false;

        $url = sprintf(self::$alUrl, $domain);
        if(!$response = parent::run($url))
            return $stat;

        if(preg_match('/\<popularity url\="(.*?)" text\="([\d]+)" source="(.*?)"\/\>/si', $response, $matches)) {
            $stat['rank'] = $matches[2];
            $alexa = true;
        }
        if(!$alexa)
            return $stat;

        if(preg_match('/\<linksin num\="([\d]+)"\/\>/si', $response, $matches))
            $stat['linksin'] = $matches[1];
        if(preg_match('/\<reviews avg="(.*?)" num="([\d]+)"\/\>/si', $response, $matches)) {
            $stat['review_avg'] = $matches[1];
            $stat['review_count'] = $matches[2];
        }

        if(preg_match('/\<country code="([a-z]{2})" name="(.*?)" rank="([\d]+)"\/\>/si', $response, $matches)) {
            $stat['country_code'] = $matches[1];
            $stat['country_name'] = $matches[2];
            $stat['country_rank'] = $matches[3];
        }

        return $stat;
    }

    public static function yahoo($domain)
    {
        $url = sprintf(self::$yahUrl, $domain);
        if(!$response = parent::run($url))
            return 0;
        preg_match_all('#<([^>]*) (?:[^>]*)class="url"(?:[^>]*)>(.*?)<\/\\1>#is', $response, $snippets);
        if(empty($snippets)) {
            return 0;
        }
        foreach($snippets[2] as $id=>$url) {
            if(mb_strpos(strip_tags($url), $domain) !== false) {
                preg_match('#<span id="resultCount" class="count">(.*?)</span>#ui', $response, $matches);
                $total = isset($matches[1]) ? (float)preg_replace("#\D#", "", $matches[1]) : 0;
                return $total > 0 ? $total : 1;
            }
        }
        return 0;
    }

    public static function inAlexa($domain)
    {
        $url = sprintf(self::$alUrl, $domain);
        if(!$response = parent::run($url))
            return 0;
        return preg_match('/\<popularity url\="(.*?)" text\="([\d]+)" source="(.*?)"\/\>/si', $response) ? 1 : 0;
    }

    public static function yandex($domain)
    {
        $url = sprintf(self::$yanUrl, $domain);
        if(!$response = parent::run($url))
            return 0;
        preg_match_all('#<div class="z-counter">(.*)</div>#ui', $response, $matches);
        return isset($matches[1][0]) ? (float)preg_replace("#\D#", "", $matches[1][0]) : 0;
    }

    public static function dmoz($domain)
    {
        $url = sprintf(self::$dmUrl, $domain);
        if(!$response = parent::run($url))
            return 0;
        preg_match_all('#\(\d+\-\d+\ of (\d+)\)#ui', $response, $matches);
        return isset($matches[1][1]) ? $matches[1][1] : 0;
    }
}