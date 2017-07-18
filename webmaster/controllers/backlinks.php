<?php
function backlinksUpdate($domain) {
    $data['Cnt'] = SearchEngine::googleBackLinks($domain);
    $data['Modified'] = date("Y-m-d H:i:s");
    Model::update("backlinks", $data, $domain);
    return $data;
}

function backlinksInsert($domain) {
    $data['Cnt'] = SearchEngine::googleBackLinks($domain);
    $data['Domain'] = $domain;
    $data['Added'] = date("Y-m-d H:i:s");
    $data['Modified'] = date("Y-m-d H:i:s");
    Model::insert("backlinks", $data);
    return $data;
}

if(isAjaxRequest()) {
    $table = $controller;
    include ROOT."controllers".DS."process_ajax.php";
}
include ROOT."tmpl".DS."service".DS."backlinks.php";