<?php 
	session_start(); 
    $text = rand(10000,99999); 
    
	$height = 40; 
    $width = 65;   
    //$image_p = imagecreate($width, $height); 
    if (rand(0,1)==0)
        {
            $image_p=imagecreatefromPng("../img/backgrnd3.png");  
            $text=str_replace('1','A',$text);
            $text=str_replace('8','H',$text);
            $text=str_replace('0','R',$text);
            $text=str_replace('7','N',$text);
            $text=str_replace('2','P',$text);
            $text=str_replace('9','G',$text);
        
        }
    else
        {
            $image_p=imagecreatefromPng("../img/backgrnd3.png");  
            $text=str_replace('3','W',$text);
            $text=str_replace('4','H',$text);
            $text=str_replace('5','Y',$text);
            $text=str_replace('6','B',$text);
            $text=str_replace('0','F',$text);
        }
	$_SESSION["capcode"] = $text; 
	$black = imagecolorallocate($image_p, 0, 5, 10); 
	$white = imagecolorallocate($image_p, 220, 230, 255); 
	$font_size = 40; 
    imagestring($image_p, $font_size, 17, 7, substr($text,0,1), $black); 
    imagestring($image_p, $font_size, 25, 3, substr($text,1,1), $black); 
    imagestring($image_p, $font_size, 35, 5, substr($text,2,1), $black); 
    imagestring($image_p, $font_size, 45, 9, substr($text,3,1), $black); 
    imagestring($image_p, $font_size, 53, 6, substr($text,4,1), $black); 
	imagejpeg($image_p, null, 80); 
?>