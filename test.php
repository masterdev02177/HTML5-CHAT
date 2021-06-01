<?php
ini_set('display_errors', 1);error_reporting(E_ALL);
Images::createThumbnail('upload/messages/5de92a6f9df76.jpg',  'upload/messages/5de92a6f9df76_thumb.jpg', 50);
exit('ok');

$img= '';
$imagick = new Imagick(realpath($img));
echo "ok2";
exit();
//echo phpinfo();
ini_set('display_errors', 1);error_reporting(E_ALL);
exec("convert -thumbnail 1200 /var/sentora/hostdata/zadmin/public_html/server3_buychatroom_com/upload/messages/5de92aa250f40.jpg /var/sentora/hostdata/zadmin/public_html/server3_buychatroom_com/upload/messages/5de92aa250f40.jpg");
exit();

$json = json_encode(
    array(
        'username'      =>'yarek',
        'password'      =>'marcel1!',
        'gender'        =>'male',
        'role'          =>'user',
        'image'         =>base64_encode('https://server2.buychatroom.com/img/malecostume.svg'),
        'profile'       =>'https://server2.buychatroom.com/profile/myUserername')
);
$encoded = file_get_contents("https://server2.buychatroom.com/protect/".base64_encode($json));
header("Location:https://server2.buychatroom.com/chat/1/$encoded");
