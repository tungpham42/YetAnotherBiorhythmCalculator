<?php
$arParams['access_key'] = $_GET['access_key'] ? $_GET['access_key'] : '';
$arParams['command'] = $_GET['command'] ? $_GET['command'] : '';
$arParams['mo_message'] = $_GET['mo_message'] ? $_GET['mo_message'] : '';
$arParams['msisdn'] = $_GET['msisdn'] ? $_GET['msisdn'] : '';
$arParams['request_id'] = $_GET['request_id'] ? $_GET['request_id'] : '';
$arParams['request_time'] = $_GET['request_time'] ? $_GET['request_time'] : '';
$arParams['short_code'] = $_GET['short_code'] ? $_GET['short_code'] : '';
$arParams['signature'] = $_GET['signature'] ? $_GET['signature'] : '';
$data = "access_key=" . $arParams['access_key'] . "&command=" . $arParams['command'] . "&mo_message=" . $arParams['mo_message'] . "&msisdn=" . $arParams['msisdn'] . "&request_id=" . $arParams['request_id'] . "&request_time=" . $arParams['request_time'] . "&short_code=" . $arParams['short_code'];
$secret = 'x6i83eedtagl5462q9b4aysjiegy7wh8'; // serequire your secret key from 1pay
$signature = hash_hmac("sha256", $data, $secret); 
$arResponse['type'] = 'text';

if ($arParams['signature'] == $signature) {
	//if sms content, amount,... are ok. return success
	$arResponse['status'] = 1;
	$arResponse['sms'] = 'Cam on ban da ung ho Nhip Sinh Hoc . VN , hotline: 01668571310';
}
else if ($arParams['signature'] != $signature || $arParams['mo_message'] != 'NSH' || $arParams['short_code'] != '8098' || $arParams['short_code'] != '8198' || $arParams['short_code'] != '8298' || $arParams['short_code'] != '8398' || $arParams['short_code'] != '8498' || $arParams['short_code'] != '8598' || $arParams['short_code'] != '8698' || $arParams['short_code'] != '8798') {
	//if not, return unsuccess
	$arResponse['status'] = 0;
	$arResponse['sms'] = 'Giao dich khong thanh cong. Tin nhan se duoc hoan cuoc sau 20 ngay. Hotline 01668571310';
}

// return json for 1pay system
echo json_encode($arResponse);