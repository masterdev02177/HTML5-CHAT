<?php
ini_set('display_errors', 1);error_reporting(E_ALL);
session_start();
include_once '../Config.php';

if (!isset($_SESSION['admin']) && !isset($_SESSION['adminpanel'])) {
    header('location:/chatadmin');
    exit;
}
$webmasterid = $_SESSION['admin'];
$webmasterid = $_SESSION['admin'];
$configid = DB::getOne('chat_config', "where webmasterid=$webmasterid")->id;
$webmaster = Webmaster::get($webmasterid);

include_once '../classes/DB.php';
include_once '../classes/Room.php';
include_once '../classes/Role.php';
include_once '../classes/Webmaster.php';
include_once '../classes/User.php';
include_once '../classes/Gender.php';
include_once '../classes/Background.php';
include_once '../classes/Services.php';
$myuser = array('role'=>'guest');
$defaultRoom = Room::getDefault($webmasterid);
$ip = Services::getMyIp();
$roles = Role::getAll($webmasterid);
$config = DB::getOne('chat_config', "WHERE webmasterid=$webmasterid");


 {
    if (isset($_GET['startRoom'])) {
        $startRoom = $_GET['startRoom'];
        $defaultRoom = Room::getRoom($startRoom, $webmasterid);
    } else {
        $startRoom = '';
    }

    if (isset($_SESSION['admin']) && $_SESSION['admin'] == $_SESSION['webmasterid']) {
        $webmaster = Webmaster::get($webmasterid);
        $myuser = array('username'=>'admin', 'password'=>$webmaster->password, 'email'=>$webmaster->email, 'id'=>$webmaster->id,  'role'=>'admin');
    }
     $args = array();
    $username = (count($args)>2)?$args[2]:'';
    $gender = (count($args)>3)?$args[3]:'';
    $avatar = (count($args)>4)?base64_decode($args[4]):'';

    if ($username) {
        $myuser = array('username'=>$username, 'password'=>'', 'email'=>'', 'id'=>rand(1,100000),  'role'=>'user');
    }
    if ($gender) {
        $myuser['gender'] = $gender;
    }
    $image = (isset($_GET['avatar']))? urldecode($_GET['avatar']) : '';
    $myuser['image'] = $image;

    if ($avatar) {
        $myuser['image'] = $avatar;
    }
    $myuser['startRoom'] = $startRoom;
}

$myuser['expired'] = $webmaster->expired;
$myuser['free'] = $webmaster->free;
$myuser['entries'] = $webmaster->entries;
Webmaster::incrementEntries($webmaster->id);


if ($config->disableTOR && Services::IsTorExitPoint()) {
    exit("403 : Not allowed");
}
if ($config->disableVPN && Services::checkProxy($ip) ) {
    exit("403 : Not allowed");
}


$rooms = Room::getAll($webmasterid);
$filename = '../lang/'.$config->langue.".json";

$fileJson = file_get_contents($filename);
$traductions = json_decode($fileJson, true);

$config->server = HOME_HTTP;
$config->node = (stripos($_SERVER['REQUEST_URI'],'chat2'))?HTTP_NODE_MULTI2:HTTP_NODE_MULTI;

$config->rtmp = RTMP;
$genders = Gender::getAll($webmasterid);
$config->genders = $genders;
$widthGenderIcon = $config->widthGenderIcon;
$heightGenderIcon = $config->heightGenderIcon;

// check if I am banned !

if (User::isBanned($ip, $webmasterid)) {
    header('location:'.$config->bannedUrl);
    exit;
}
$muted = User::isMuted($ip, $webmasterid);
if ($muted) {
    $myuser['mutedUntil'] = $muted->mutedUntil;
}
$myuser['streamName'] = time();
if (!$config->webrtc) {
    $config->webrtcServer = '';
}
$myuser['roles'] = $roles;
$myuser['country'] = 'us';
$myuser['webmasterid'] = $webmasterid;
$scriptChat = ((basename(__FILE__))!='chat2.php')?"js/chatHTML54.js?cache=".rand():"js/chatHTML54.js?cache=".rand();
include_once '../chatTemplateFlex.php';
?>

<script src="/js/cssEditor/drags.js"></script>
<script src="/js/cssEditor/store2.js"></script>
<script src="/js/cssEditor/selectorator.js"></script>
<script src="/js/cssEditor/style.js"></script>
<script src="/js/cssEditor/cle.js?time=<?=time()?>"></script>
<script src="/js/cssEditor/cle.init.js?time=<?=time()?>"></script>