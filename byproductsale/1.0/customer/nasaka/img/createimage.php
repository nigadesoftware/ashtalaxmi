<?php
// Print two names on the picture, which accepted by query string parameters.
$name = $_GET['name'];
$width = $_GET['width'];
$size = $_GET['size'];
$r = $_GET['r'];
$g = $_GET['g'];
$b = $_GET['b'];
$r1 = $_GET['r1'];
$g1 = $_GET['g1'];
$b1 = $_GET['b1'];
if(extension_loaded('gd'))
{
// Create a blank image and add some text
//$im = imagecreatetruecolor(120, 20);
$im = imagecreate($width,21);
$bg = imagecolorallocatealpha($im,$r,$g,$b,0);
//$background  = imagecolorallocate( $im, 255,   255,   255 );
$text_color = imagecolorallocate($im, $r1,$g1,$b1);
//imagestring($im, 1, 5, 5,  $n1, $text_color);
$font = dirname(__FILE__) . '/../../../../../fonts/siddhanta.ttf';
imagettftext($im,$size, 0, 0, 15, $text_color, $font, $name);
// Return output.
ImageJPEG($im, NULL, 93);
ImageDestroy($im);
}
?>