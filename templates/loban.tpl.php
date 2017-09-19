<?php
$font_file = realpath($_SERVER['DOCUMENT_ROOT']).'/css/fonts/Montserrat-Regular.ttf';
$thang_do = realpath($_SERVER['DOCUMENT_ROOT']).'/images/thuoc.png';
$loai_thuoc = array(
    '522' => realpath($_SERVER['DOCUMENT_ROOT']).'/images/522.png',
    '429' => realpath($_SERVER['DOCUMENT_ROOT']).'/images/429.png',
    '388' => realpath($_SERVER['DOCUMENT_ROOT']).'/images/388.png'
);
$trim_start = isset($_GET['trimStart']) && $_GET['trimStart'] > 0 ? $_GET['trimStart'] * 10 : 0;
$ruler_length = isset($_GET['rulerLength']) && $_GET['rulerLength'] > 0 ? $_GET['rulerLength'] * 10 : 10000;
$type = isset($_GET['type']) ? $_GET['type'] : 522;
$type = intval($type);
$image_bg = isset($loai_thuoc['522']) ? $loai_thuoc['522'] : realpath($_SERVER['DOCUMENT_ROOT']).'/images/522.png';
if(429 == $type) {
    $image_bg = isset($loai_thuoc['429']) ? $loai_thuoc['429'] : realpath($_SERVER['DOCUMENT_ROOT']).'/images/429.png';
} else if(388 == $type) {
    $image_bg = isset($loai_thuoc['388']) ? $loai_thuoc['388'] : realpath($_SERVER['DOCUMENT_ROOT']).'/images/388.png';
}
$width = $ruler_length;
$height = 100;
if($trim_start >= $width) {
    $page = $trim_start / $width;
} else {
    $page = 0;
}
if($page > 0) {
    $am = ($page * $width) % 3880;
}
$image = ImageCreate($width, $height);
$image1 = imagecreatefrompng($image_bg);
$image2 = imagecreatefrompng($thang_do);
$black = ImageColorAllocate($image, 63, 63, 63);
$out_img = imagecreatetruecolor($width, $height);
$curr_x = 0;
while($curr_x < $width) {
    imagecopy($out_img, $image1, $page > 0 && $curr_x == 0 ? -$am : $curr_x, 0, 0, 0, imagesx($image1), imagesy($image1));
    $curr_x += $page > 0 && $curr_x == 0 ? imagesx($image1) - $am : imagesx($image1);
}
imagecopymerge($image, $out_img, 0, 0, 0, 0, $width, imagesy($image1), 100);
$out_img2 = imagecreatetruecolor($width, $height);
$curr_x = 0;
while($curr_x < $width) {
    imagecopy($out_img2, $image2, $curr_x, 0, 0, 0, imagesx($image2), imagesy($image2));
    $curr_x += imagesx($image2);
}
imagecopymerge($image, $out_img2, 0, 0, 0, 0, $width, imagesy($image2), 100);
if($page > 0) {
    imagefttext($image, 8, 0, -9, 25, $black, $font_file, (($page * $width) / imagesx($image2)) . ' cm');
} else {
    imagefttext($image, 8, 0, 1, 25, $black, $font_file, 'cm');
}
$curr_x = 0;
while($curr_x <= $width) {
    imagefttext($image, 8, 0, $curr_x - 9, 25, $black, $font_file, ($curr_x > 0 ? (($page * $width + $curr_x) / imagesx($image2)) . ' cm' : ''));
    $curr_x += imagesx($image2);
}
header("Content-Type: image/png");
imagepng($image);
ImageDestroy($image);
exit();