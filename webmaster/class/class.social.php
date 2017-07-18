<?php
class Social extends Request
{
	private static $fbUrl = 'http://api.facebook.com/restserver.php?method=links.getStats&urls=%s';
	private static $twUrl = 'http://urls.api.twitter.com/1/urls/count.json?url=%s';
	private static $piUrl = 'http://api.pinterest.com/v1/urls/count.json?url=%s';
	private static $gpUrl = 'https://clients6.google.com/rpc?key=AIzaSyCKSbrvQasunBoV16zDH9R33D88CeLr9gQ';
	private static $deUrl = 'http://feeds.delicious.com/v2/json/urlinfo/data?url=%s';
	private static $stUrl = 'http://www.stumbleupon.com/services/1.01/badge.getinfo?url=%s';
	private static $liUrl = 'http://www.linkedin.com/countserv/count/share?url=%s';

	public static function gplus($url)
	{
		if(!preg_match("#(.*)\.(.*)/(.*)#ui", $url))
			$url = $url.'/';
		if(!preg_match("#^https?://#ui", $url))
			$url = 'http://'.$url;
		$ch = curl_init(self::$gpUrl);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS,'[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"'.$url.'","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
		$output = curl_exec ($ch);
		$data = !curl_errno($ch) ? $output : false;
		curl_close ($ch);
		if(!$data) return 0;
		if(!$json = json_decode($data, true))
			return 0;
		return isset($json[0]['result']['metadata']['globalCounts']['count']) ?
		(int)$json[0]['result']['metadata']['globalCounts']['count'] : 0;
	}

	public static function facebook($url)
	{
		$pattern = array(
			'share_count'=>0,
			'like_count'=>0,
			'comment_count'=>0,
			'total_count'=>0,
			'click_count'=>0,
		);
		$url = sprintf(self::$fbUrl, $url);
		if(!$response = parent::run($url))
			return $pattern;
		libxml_use_internal_errors(true);
		if(!$xml = simplexml_load_string($response)) {
			libxml_clear_errors();
			return $pattern;
		}
		$stat = isset($xml->link_stat) ? (array)$xml->link_stat : false;
		if(!$stat) return $pattern;
		$pattern['share_count'] = isset($stat['share_count']) ? $stat['share_count'] : 0;
		$pattern['like_count'] = isset($stat['like_count']) ? $stat['like_count'] : 0;
		$pattern['comment_count'] = isset($stat['comment_count']) ? $stat['comment_count'] : 0;
		$pattern['total_count'] = isset($stat['total_count']) ? $stat['total_count'] : 0;
		$pattern['click_count'] = isset($stat['click_count']) ? $stat['click_count'] : 0;
		return $pattern;
	}

	public static function twitter($url)
	{
		$url = sprintf(self::$twUrl, $url);
		if(!$response = parent::run($url))
			return 0;
		if(!$json = json_decode($response, true))
			return 0;
		return isset($json['count']) ? (int)$json['count'] : 0;
	}

	public static function pinterest($url)
	{
		if(!preg_match("#^https?://#ui", $url))
			$url = 'http://'.$url;
		$url = sprintf(self::$piUrl, $url);
		if(!$response = parent::run($url))
			return 0;
		$response = str_replace(array('(', ')'), '', $response);
		$response = str_replace("receiveCount", '', $response);
		if(!$json = json_decode($response, true))
			return 0;
		return isset($json['count']) ? (int)$json['count'] : 0;
	}

	public static function delicious($url)
	{
		$url = sprintf(self::$deUrl, $url);
		if(!$response = parent::run($url))
			return 0;
		if(!$json = json_decode($response, true))
			return 0;
		return isset($json[0]['total_posts']) ? (int)$json[0]['total_posts'] : 0;
	}

  public static function stumbleupon($url)
  {
		$url = sprintf(self::$stUrl, $url);
		if(!$response = parent::run($url))
			return 0;
		if(!$json = json_decode($response, true))
			return 0;
		return $json['result']['in_index'] == true ? (int)$json['result']['views'] : 0;
  }

  public static function linkedin($url)
  {
		$url = sprintf(self::$liUrl, $url);
		if(!$response = parent::run($url))
			return 0;
		preg_match('#\{(.*?)\}#', $response, $match);
		if(isset($match[0]) AND $json = json_decode($match[0], true))
			return isset($json['count']) ? (int)$json['count'] : 0;
		else
			return 0;
  }
}