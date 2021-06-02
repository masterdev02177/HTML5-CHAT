<?php
include_once '../classes/DB.php';
$image=$_POST['file'];
print_r($image);
$file='../bannerImg/'.$_POST['file'];
$file1='../'.$_POST['file1'];
DB::deleteFromTable('chat_banner',"WHERE image='$image'" );

unlink($file);
unlink($file1);
?>