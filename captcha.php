<?php
define('INDEX_CHECK', 1);
define('ROOT_PATH', dirname( __FILE__ ) .'/');

include('nuked.php');
include_once('Includes/nkCaptcha.php');

$text = $_SESSION['captcha'];

header('Content-type: image/jpeg');
$im = imagecreatefromjpeg('images/captcha.jpg');
$id = imagecreatefromjpeg('images/captcha.jpg');
$grey = imagecolorallocate($im, 0, 0, 0);
$black = imagecolorallocate($im, 200, 200, 200);
$font = 'Includes/font/LondonBetween.ttf';

for($i=0;$i<5;$i++)
{
    $angle = mt_rand(5,30);
    if (mt_rand(0,1) == 1) $angle =- $angle;
    imagettftext($im, 12, $angle, 11+(22*$i), 15, $grey, $font, substr($text,$i,1));
    imagettftext($im, 12, $angle, 10+(22*$i), 16, $black, $font, substr($text,$i,1));
}

imagecopymerge ($im, $id, 0, 0, 0, 0, 120, 20, 40);
imagejpeg($im);
imagedestroy($im);
imagedestroy($id);

?>