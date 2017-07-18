<?php
function diagnosticUpdate($domain) {
	$data['norton'] = Diagnostic::norton($domain);
	$data['avg'] = Diagnostic::avg($domain);
	$data['mcafee'] = Diagnostic::mcafee($domain);
	$data['google'] = Diagnostic::google($domain);
	$data['Modified'] = date("Y-m-d H:i:s");
	Model::update("diagnostic", $data, $domain);
	return $data;
}

function diagnosticInsert($domain) {
    $data['norton'] = Diagnostic::norton($domain);
	$data['avg'] = Diagnostic::avg($domain);
	$data['mcafee'] = Diagnostic::mcafee($domain);
	$data['google'] = Diagnostic::google($domain);
	$data['Domain'] = $domain;
	$data['Added'] = date("Y-m-d H:i:s");
	$data['Modified'] = date("Y-m-d H:i:s");
	Model::insert("diagnostic", $data);
	return $data;
}

if(isAjaxRequest()) {
	$table = $controller;
	include ROOT."controllers".DS."process_ajax.php";
}
include ROOT."tmpl".DS."service".DS."diagnostic.php";