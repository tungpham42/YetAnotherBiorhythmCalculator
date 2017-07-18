<?php
function locationUpdate($domain, $ip) {
	$data = Location::get($ip);
	$data['Modified'] = date("Y-m-d H:i:s");
	Model::update("location", $data, $domain);
	return $data;
}

function locationInsert($domain, $ip) {
	$data = Location::get($ip);
	$data['Domain'] = $domain;
	$data['Added'] = date("Y-m-d H:i:s");
	$data['Modified'] = date("Y-m-d H:i:s");
	Model::insert("location", $data);
	return $data;
}

if(isAjaxRequest()) {
	$table = $controller;
	include ROOT."controllers".DS."process_ajax.php";
}
include ROOT."tmpl".DS."service".DS."location.php";