<?php
$url = 'http://www.nationalgeographic.com/photography/photo-of-the-day/_jcr_content/.gallery.json';
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);
$result = curl_exec($ch);
curl_close($ch);
$obj = json_decode($result);
$item = $obj->items[0];
$parts = (array) $item->sizes;
ksort($parts, SORT_NUMERIC);
$part = end(array_values($parts));
header('Location: ' . $item->url . $part);
exit;