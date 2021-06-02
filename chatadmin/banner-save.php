<?php
include_once '../classes/DB.php';

$image=$_POST['file'];
$position=$_POST['option1'];
$state=$_POST['option2'];
$banner = DB::getOne('chat_banner',"WHERE setstatus='set'");
if($banner){
    echo "You can set only one banner";
}else{
DB::update2('chat_banner',array('position'=>$position , 'setstatus' => $state),"WHERE image='$image'");
echo "success";
}
?>