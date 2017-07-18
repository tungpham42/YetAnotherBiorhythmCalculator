<?php
function catalogUpdate($domain) {
	$data['dmoz'] = SearchCatalog::dmoz($domain);
	$data['alexa'] =SearchCatalog::inAlexa($domain);
	$data['yahoo'] =SearchCatalog::yahoo($domain);
	$data['yandex'] =SearchCatalog::yandex($domain);
	$data['Modified'] = date("Y-m-d H:i:s");
	Model::update("catalog", $data, $domain);
	return $data;
}

function catalogInsert($domain) {

	$data['dmoz'] = SearchCatalog::dmoz($domain);
	$data['alexa'] =SearchCatalog::inAlexa($domain);
	$data['yahoo'] =SearchCatalog::yahoo($domain);
	$data['yandex'] =SearchCatalog::yandex($domain);
	$data['Domain'] = $domain;
	$data['Added'] = date("Y-m-d H:i:s");
	$data['Modified'] = date("Y-m-d H:i:s");
	Model::insert("catalog", $data);
	return $data;
}

if(isAjaxRequest()) {
	$table = $controller;
	include ROOT."controllers".DS."process_ajax.php";
}
include ROOT."tmpl".DS."service".DS."catalog.php";