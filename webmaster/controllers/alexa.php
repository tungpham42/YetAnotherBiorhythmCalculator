<?php
function alexaUpdate($domain) {
	$data = SearchCatalog::alexa($domain);
	$data['Modified'] = date("Y-m-d H:i:s");
	Model::update("alexa", $data, $domain);
	return $data;
}

function alexaInsert($domain) {
	$data = SearchCatalog::alexa($domain);
	$data['Domain'] = $domain;
	$data['Added'] = date("Y-m-d H:i:s");
	$data['Modified'] = date("Y-m-d H:i:s");
	Model::insert("alexa", $data);
	return $data;
}

if(isAjaxRequest()) {
	$table = $controller;
	include ROOT."controllers".DS."process_ajax.php";
}
include ROOT."tmpl".DS."service".DS."alexa.php";