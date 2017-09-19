<?php
function libraries_autoload($class_name) {
	require realpath($_SERVER['DOCUMENT_ROOT']).'/includes/libraries/'.$class_name.'.class.php';
}
function google_api_php_client_autoload($class_name) {
	$class_path = explode('_', $class_name);
	if ($class_path[0] != 'Google') {
		return;
	}
	if (count($class_path) > 3) {
		// Maximum class file path depth in this project is 3.
		$class_path = array_slice($class_path, 0, 3);
	}
	$file_path = realpath($_SERVER['DOCUMENT_ROOT']).'/includes/' . implode('/', $class_path) . '.php';
	if (file_exists($file_path)) {
		require($file_path);
	}
}
spl_autoload_register('libraries_autoload');
spl_autoload_register('google_api_php_client_autoload');
function is_public_server(): bool {
	if (substr($_SERVER['REMOTE_ADDR'],0,7) == '108.162' || substr($_SERVER['REMOTE_ADDR'],0,7) == '173.245' || substr($_SERVER['REMOTE_ADDR'],0,6) == '66.220' || substr($_SERVER['REMOTE_ADDR'],0,7) == '173.252' || substr($_SERVER['REMOTE_ADDR'],0,5) == '31.13' || substr($_SERVER['REMOTE_ADDR'],0,10) == '64.233.172' || substr($_SERVER['REMOTE_ADDR'],0,5) == '69.63' || substr($_SERVER['REMOTE_ADDR'],0,6) == '69.171' || substr($_SERVER['REMOTE_ADDR'],0,6) == '66.102' || substr($_SERVER['REMOTE_ADDR'],0,9) == '66.249.83' || substr($_SERVER['REMOTE_ADDR'],0,6) == '66.249' || $_SERVER['REMOTE_ADDR'] == '23.92.24.198') {
		return true;
	} else {
		return false;
	}
}
function is_bot(): bool {
	if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider/i', $_SERVER['HTTP_USER_AGENT'])) {
		return true;
	} else {
		return false;
	}
}
function prevent_xss($query_string) {
	return htmlentities($query_string, ENT_QUOTES, 'UTF-8');
}
function init_timezone() {
	global $geoip_record;
	if (is_public_server() || is_bot() || !isset($geoip_record)) {
		date_default_timezone_set('Asia/Ho_Chi_Minh');
	} else if (!is_public_server() && !is_bot() && isset($geoip_record)) {
		$timezone = get_time_zone($geoip_record->country_code,$geoip_record->region);
		date_default_timezone_set($timezone);
	}
}
function init_lang_code(): string {
	global $geoip_record;
	global $lang_codes;
	global $one_lang;
	global $first_domain;
	global $second_domain;
	if ($_SERVER['SERVER_NAME'] == $first_domain) {
		$lang_code = 'vi';
		$country_code = isset($geoip_record) ? $geoip_record->country_code: 'VN';
	} else if ($_SERVER['SERVER_NAME'] == $second_domain) {
		$lang_code = 'en';
		$country_code = isset($geoip_record) ? $geoip_record->country_code: 'US';
	}
	$country_codes = array(
		'vi' => array('VN'),
		'en' => array('UK', 'US', 'CA', 'NZ', 'AU'),
		'ru' => array('RU', 'AM', 'AZ', 'BY', 'KZ', 'KG', 'MD', 'TJ', 'UZ', 'TM', 'UA'),
		'es' => array('ES', 'CO', 'PE', 'VE', 'EC', 'GT', 'CU', 'BO', 'HN', 'PY', 'SV', 'CR', 'PA', 'GQ', 'MX', 'AR', 'CL', 'DO', 'NI', 'UY', 'PR'),
		'zh' => array('CN', 'HK', 'MO', 'TW', 'SG'),
		'ja' => array('JP')
	);
	if (isset($one_lang)) {
		setcookie('NSH:lang', $one_lang, time()+(10*365*24*60*60), '/'.$one_lang.'/');
		$lang_code = $one_lang;
	}
	if (!isset($_COOKIE['NSH:lang'])) {
		if ($_SERVER['SERVER_NAME'] == $first_domain && (in_array($country_code, $country_codes['vi']) || is_public_server() || is_bot())) {
			setcookie('NSH:lang', 'vi', time()+(10*365*24*60*60), '/');
			$lang_code = 'vi';
		} else if ($_SERVER['SERVER_NAME'] == $second_domain && (in_array($country_code, $country_codes['en']) || is_public_server() || is_bot())) {
			setcookie('NSH:lang', 'en', time()+(10*365*24*60*60), '/');
			$lang_code = 'en';
		} else if (in_array($country_code, $country_codes['ru'])) {
			setcookie('NSH:lang', 'ru', time()+(10*365*24*60*60), '/');
			$lang_code = 'ru';
		} else if (in_array($country_code, $country_codes['es'])) {
			setcookie('NSH:lang', 'es', time()+(10*365*24*60*60), '/');
			$lang_code = 'es';
		} else if (in_array($country_code, $country_codes['zh'])) {
			setcookie('NSH:lang', 'zh', time()+(10*365*24*60*60), '/');
			$lang_code = 'zh';
		} else if (in_array($country_code, $country_codes['ja'])) {
			setcookie('NSH:lang', 'ja', time()+(10*365*24*60*60), '/');
			$lang_code = 'ja';
		}
	} else if (isset($_COOKIE['NSH:lang'])) {
		$lang_code = $_COOKIE['NSH:lang'];
	}
	$lang_code = (isset($one_lang)) ? $one_lang: ((isset($_GET['lang']) && in_array($_GET['lang'], $lang_codes)) ? prevent_xss($_GET['lang']): $lang_code);
	return $lang_code;
}
function get_timezone() {
	global $geoip_record;
	$timezone = get_time_zone($geoip_record->country_code,$geoip_record->region);
	return $timezone;
}
function get_timezone_offset($remote_tz, $origin_tz = 'UTC') {
	$origin_dtz = new DateTimeZone($origin_tz);
	$remote_dtz = new DateTimeZone($remote_tz);
	$origin_dt = new DateTime('now', $origin_dtz);
	$remote_dt = new DateTime('now', $remote_dtz);
	$offset = $remote_dtz->getOffset($remote_dt) - $origin_dtz->getOffset($origin_dt);
	return $offset/3600;
}
function digitval($number): int {
	$sum = 0;
	while ($number > 0) {
	    $rem = $number % 10;
	    $number = $number / 10;
	    $sum = $sum + $rem;
	}
	if (strlen($sum) >= 2)
		$res = digitval($sum);
	elseif ($sum == 11 || $sum == 22)
		$res = $sum;
	else
		$res = $sum;
	return $res;
}
function calculate_life_path($dob): int {
	$life_path_number = 0;
	$year = date('Y',strtotime($dob));
	$month = date('m',strtotime($dob));
	$day = date('d',strtotime($dob));
	$life_path_number = digitval(digitval($year) + digitval($month) + digitval($day));
	return $life_path_number;
}
function has_one_lang(): bool {
	global $hide_lang_bar;
	global $hide_nav;
	if ($hide_lang_bar == true && $hide_nav == true) {
		return true;
	} else {
		return false;
	}
}
function is_paid_member($email): bool {
	$path = realpath($_SERVER['DOCUMENT_ROOT']).'/member/'.strtolower($email);
	$receipt_path = $path.'/paid.txt';
	if (file_exists($receipt_path)) {
		return true;
	} else {
		return false;
	}
}

/* General Functions */
function load_all_array($table_name): array { //Put all table records into an array
	global $pdo;
	$array = array();
	$result = $pdo->prepare('SELECT * FROM "'.$table_name.'"');
	$result->execute();
	if ($result) {
		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$array[] = $row;
		}
	}
	return $array;
}
function load_array_with_operator($table_name,$identifier,$value,$operator): array { //Put specific table records according to condition into an array
	global $pdo;
	$array = array();
	$result = $pdo->prepare('SELECT * FROM "'.$table_name.'" WHERE '.$identifier.$operator.':value');
	$result->execute(array(':value' => $value));
	if ($result) {
		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$array[] = $row;
		}
	}
	return $array;
}
function load_array_with_two_identifiers($table_name,$identifier1,$value1,$identifier2,$value2): array { //Load array from database with 2 identifiers
	global $pdo;
	$array = array();	
	$result = $pdo->prepare('SELECT * FROM "'.$table_name.'" WHERE '.$identifier1.'=:value1 AND '.$identifier2.'=:value2');
	$result->execute(array(':value1' => $value1, ':value2' => $value2));
	if ($result) {
		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$array[] = $row;
		}
	}
	return $array;
}
function load_array_with_two_values($table_name,$identifier,$value1,$value2): array { //Load array from database with 1 identifier and 2 values
	global $pdo;
	$array = array();	
	$result = $pdo->prepare('SELECT * FROM "'.$table_name.'" WHERE '.$identifier.'=:value1 OR '.$identifier.'=:value2');
	$result->execute(array(':value1' => $value1, ':value2' => $value2));
	if ($result) {
		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$array[] = $row;
		}
	}
	return $array;
}
function load_array($table_name,$identifier,$value): array { //Load array from database with 1 identifier and 1 value
	$array = load_array_with_operator($table_name,$identifier,$value,'=');
	return $array;
}
function search_array($table_name,$identifier,$value): array {
	global $pdo;
	$array = array();
	$result = $pdo->prepare('SELECT * FROM "'.$table_name.'" WHERE '.$identifier.' LIKE :value');
	$result->execute(array(':value' => "%$value%"));
	if ($result) {
		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$array[] = $row;
		}
	}
	return $array;
}
function insert_record($array = array(), $table_name) { //Insert table record
	global $pdo;
	$keys = array_keys($array);
	$values = array_values($array);
	$execute_array = array();
	$count = count($array);
	$query = "";
	$query .= 'INSERT INTO "'.$table_name.'"(';
	for ($k = 0; $k < $count; ++$k) {
		$query .= $keys[$k].(($k < ($count - 1)) ? ',': "");
	}
	$query .= ') VALUES(';
	for ($i = 0; $i < $count; ++$i) {
		$query .= '?'.(($i < ($count - 1)) ? ',': "");
	}
	$query .= ')';
	for ($e = 0; $e < $count; ++$e) {
		$execute_array[$e] = $values[$e];
	}
	$result = $pdo->prepare($query);
	$result->execute($execute_array);
}
function update_record_with_operator($array = array(), $identifier, $value, $table_name, $operator) { //Update table record
	global $pdo;
	$keys = array_keys($array);
	$values = array_values($array);
	$execute_array = array();
	$count = count($array);
	$query = "";
	$query .= 'UPDATE "'.$table_name.'" SET ';
	for ($i = 0; $i < $count; ++$i) {
		$query .= $keys[$i].'=?'.(($i < ($count - 1)) ? ',': "");
	}
	$query .= ' WHERE '.$identifier.$operator.'?';
	for ($e = 0; $e < $count; ++$e) {
		$execute_array[$e] = $values[$e];
	}
	$execute_array[$count] = $value;
	$result = $pdo->prepare($query);
	$result->execute($execute_array);
}
function update_record($array = array(), $identifier, $value, $table_name) { //Update table record
	update_record_with_operator($array, $identifier, $value, $table_name, '=');
}
function delete_record($identifier, $value, $table_name) { //Delete table records with 1 identifier
	global $pdo;
	$result = $pdo->prepare('DELETE FROM "'.$table_name.'" WHERE '.$identifier.'=:value');
	$result->execute(array(':value' => $value));
}
function delete_record_with_two_identifier($identifier1, $value1, $identifier2, $value2, $table_name) { //Delete table records with 2 identifiers
	global $pdo;
	$result = $pdo->prepare('DELETE FROM "'.$table_name.'" WHERE '.$identifier1.'=:value1 AND '.$identifier2.'=:value2');
	$result->execute(array(':value1' => $value1, ':value2' => $value2));
}
function table_row_class($id): string { //Identify the table row class based on counter
	$output = "";
	if ((($id+1) % 2) == 1) {
		$output .= ' odd';
	} else {
		$output .= ' even';
	}
	return $output;
}
function pluralize($count, $singular, $plural = false): string {
	if (!$plural) $plural = $singular . 's';
	return (($count == 0 || $count == 1) ? $singular : $plural) ;
}
function substr_word($str,$start,$end): string { //Substract words from content
	$end_pos = strpos($str,' ',$end);
	if ($pos !== false) {
		return substr($str,$start,$end_pos);
	}
}
function load_user($uid): array { //Load user array from user ID
	$users = load_array('nsh_users','uid',$uid);
	sort($users);
	return $users[0];
}
function load_user_from_name($name): array { //Load user array from username
	$users = load_array('nsh_users','name',$name);
	sort($users);
	return $users[0];
}
function load_rhythm($rid): array { //Load rhythm array from rhythm ID
	$rhythms = load_array('nsh_rhythms','rid',$rid);
	sort($rhythms);
	return $rhythms[0];
}
function load_credential($id): array { //Load credential array from ID
	$credentials = load_array('nsh_@dm!n','id',$id);
	sort($credentials);
	return $credentials[0];
}
function get_rhythm_title($rid,$lang): string {
	$rhythm = load_rhythm($rid);
	switch ($lang) {
		case 'vi':
			return $rhythm['name'];
			break;
		case 'en':
			return $rhythm['description_en'];
			break;
		case 'ru':
			return $rhythm['description_ru'];
			break;
		case 'es':
			return $rhythm['description_es'];
			break;
		case 'zh':
			return $rhythm['description_zh'];
			break;
		case 'ja':
			return $rhythm['description_ja'];
			break;
	}
}