<?php
include_once '../classes/DB.php';

$image=$_POST['file'];
$position=$_POST['option1'];
$state=$_POST['option2'];
DB::update2('chat_banner',array('position'=>$position , 'setstatus' => $state),"WHERE image='$image'")

?>