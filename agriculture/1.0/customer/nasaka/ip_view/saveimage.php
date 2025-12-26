<?php
session_start(); 
//set random name for the image, used time() for uniqueness
 
$filename =  $_SESSION['seasoncode'].'_'.$_SESSION['plotnumber'] . '.jpg';
$filepath = '../saved_images/';
 
move_uploaded_file($_FILES['webcam']['tmp_name'], $filepath.$filename);
echo $filepath.$filename;
$_SESSION['photofile'] = $filepath.$filename;
?>