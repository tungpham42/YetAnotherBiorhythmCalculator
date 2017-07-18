<?php
function socialUpdate($domain) {
	$data['gplus'] = Social::gplus($domain);
	$facebook = Social::facebook($domain);
	$data['twitter'] = Social::twitter($domain);
	$data['pinterest'] = Social::pinterest($domain);
	$data['delicious'] = Social::delicious($domain);
	$data['stumbleupon'] = Social::stumbleupon($domain);
	$data['linkedin'] = Social::linkedin($domain);;
	$data = array_merge($data, $facebook);
	$data['Modified'] = date("Y-m-d H:i:s");
	Model::update("social", $data, $domain);
	return $data;
}

function socialInsert($domain) {
	$data['gplus'] = Social::gplus($domain);
	$facebook = Social::facebook($domain);
	$data['twitter'] = Social::twitter($domain);
	$data['pinterest'] = Social::pinterest($domain);
	$data['delicious'] = Social::delicious($domain);
	$data['stumbleupon'] = Social::stumbleupon($domain);
	$data['linkedin'] = Social::linkedin($domain);
    $data = array_merge($data, $facebook);
    $data['Domain'] = $domain;
	$data['Added'] = date("Y-m-d H:i:s");
	$data['Modified'] = date("Y-m-d H:i:s");
	Model::insert("social", $data);
	return $data;
}

if(isAjaxRequest()) {
	$table = $controller;
	include ROOT."controllers".DS."process_ajax.php";
}
include ROOT."tmpl".DS."service".DS."social.php";