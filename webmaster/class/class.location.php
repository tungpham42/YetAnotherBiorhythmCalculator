<?php
class Location extends Request
{
	//private static $locApi = "http://api.hostip.info/get_json.php?ip=%s&position=true";
	private static $locApi = "http://freegeoip.net/json/%s";

	public static function get($ip)
	{
		$pattern = array(
			'city'=>'Unknown',
			'region_name'=>'Unknown',
			'ip'=>'Unknown',
			'longitude'=>0,
			'country_name'=>'Unknown',
			'country_code'=>'XX',
			'latitude'=>0,
		);
		$url = sprintf(self::$locApi, $ip);
		if(!$response = parent::run($url))
			return $pattern;
		if(!$json = json_decode($response, true))
			return $pattern;

		return array(
			'city'=>_v($json, 'city', 'Unknown'),
			'region_name'=>_v($json, 'region_name', 'Unknown'),
			'ip'=>_v($json, 'ip', 'Unknown'),
			'longitude'=>_v($json, 'longitude', 0),
			'country_name'=>_v($json, 'country_name', 'Unknown'),
			'country_code'=>_v($json, 'country_code', 'XX'),
			'latitude'=>_v($json, 'latitude', 0),
		);
	}
}