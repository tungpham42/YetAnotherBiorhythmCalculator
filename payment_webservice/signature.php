<?php
$arParams['access_key'] = $_GET['access_key'] ? $_GET['access_key'] : '';
$arParams['command'] = $_GET['command'] ? $_GET['command'] : '';
$arParams['mo_message'] = $_GET['mo_message'] ? $_GET['mo_message'] : '';
$arParams['msisdn'] = $_GET['msisdn'] ? $_GET['msisdn'] : '';
$arParams['request_id'] = $_GET['request_id'] ? $_GET['request_id'] : '';
$arParams['request_time'] = $_GET['request_time'] ? $_GET['request_time'] : '';
$arParams['short_code'] = $_GET['short_code'] ? $_GET['short_code'] : '';
$data = "access_key=" . $arParams['access_key'] . "&command=" . $arParams['command'] . "&mo_message=" . $arParams['mo_message'] . "&msisdn=" . $arParams['msisdn'] . "&request_id=" . $arParams['request_id'] . "&request_time=" . $arParams['request_time'] . "&short_code=" . $arParams['short_code'];
$secret = 'x6i83eedtagl5462q9b4aysjiegy7wh8'; // serequire your secret key from 1pay
$signature = hash_hmac("sha256", $data, $secret); 
echo $signature;
?>