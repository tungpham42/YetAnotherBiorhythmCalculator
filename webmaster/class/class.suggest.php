<?php
class Suggest extends Request {
    private static $googleSuggest = 'http://suggestqueries.google.com/complete/search?output=firefox&client=firefox&q=%s&hl=%s';

    public static function google($keyword, $lang) {
        $keywords = array();
        $url = sprintf(self::$googleSuggest, urlencode($keyword), $lang);
        if(!$response = parent::run($url)) {
            return array();
        }
        if (($data = json_decode($response, true)) !== null) {
            $keywords = isset($data[1]) ? $data[1] : array();
        }
        return $keywords;
    }
}