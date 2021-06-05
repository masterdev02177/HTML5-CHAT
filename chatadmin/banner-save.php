<?php
include_once '../classes/DB.php';

$image=$_POST['file'];
$position=$_POST['option1'];
$state=$_POST['option2'];
$url=$_POST['option3'];

$current_banner=DB::getOne('chat_banner',"WHERE image='$image'");
$banner = DB::getOne('chat_banner',"WHERE setstatus='set'");
if($banner && $current_banner->setstatus != 'set'){
    echo "You can set only one banner";
}else{
DB::update2('chat_banner',array('position'=>$position , 'setstatus' => $state , 'url' => $url),"WHERE image='$image'");
echo "success";
}
?>