<?php
function dnsInsert($domain) {
	$dns = dns_get_record($domain);
	if(!is_array($dns))
		$dns = array();
		
	return array(
		'dns' => $dns,
	);
}

if(isAjaxRequest()) {
	$table = false;
	include ROOT."controllers".DS."process_ajax.php";
}

include ROOT."tmpl".DS."service".DS."dns.php";