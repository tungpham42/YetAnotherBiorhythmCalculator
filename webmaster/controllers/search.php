<?php
function searchUpdate($domain) {
	$data['yandex'] = SearchEngine::yandex($domain);
	$data['yahoo'] = SearchEngine::yahoo($domain);
	$data['google'] = SearchEngine::google($domain);
	$data['bing'] = SearchEngine::bing($domain);
	$data['Modified'] = date("Y-m-d H:i:s");
	Model::update("search", $data, $domain);
	return $data;
}

function searchInsert($domain) {
	$data['yandex'] = SearchEngine::yandex($domain);
	$data['yahoo'] = SearchEngine::yahoo($domain);
	$data['google'] = SearchEngine::google($domain);
	$data['bing'] = SearchEngine::bing($domain);
	$data['Domain'] = $domain;
	$data['Added'] = date("Y-m-d H:i:s");
	$data['Modified'] = date("Y-m-d H:i:s");
	Model::insert("search", $data);
	return $data;
}

if(isAjaxRequest()) {
	$table = $controller;
	include ROOT."controllers".DS."process_ajax.php";
}
include ROOT."tmpl".DS."service".DS."search.php";