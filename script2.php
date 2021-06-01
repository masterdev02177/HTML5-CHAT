<?php
//ini_set('display_errors', 1);error_reporting(E_ALL);
//$webmasterid/$token (simple)
// ou bien $webmasterid/$token/$username/$sex
// <script src='https://server2.buychatroom.com/script/1/1010/yarek/cpl'></script>
// BASE 64 avatar !!!
// <script src='https://server2.buychatroom.com/script/1544/5a0440c6c992c/Cams/male/aHR0cHM6Ly9zdGlla2VtYW5vbmllbS5ubC91cGxvYWRzL21vbnRobHlfMjAxN18xMS9hbmEudGh1bWIuanBnLjZmYTFhN2JkNzg3ZmUwYTcwMmQ2YzNkMDU2NGNlYmRlLmpwZw=='></script>
//
include_once('Config.php');
require 'vendor/autoload.php';
include_once ('classes/DB.php');
include_once ('classes/Webmaster.php');
use \Firebase\JWT\JWT;
if (count($args)<2) {
    exit;
}


$width = $height = "100%";
$src = HOME_HTTP . '/chat2/';
foreach ($args as $arg) {
    $src.=$arg.'/';
}
$uri = $_SERVER['REQUEST_URI'];
$queryString = strpos($uri, '?') ? substr($uri, strpos($uri, '?') + 0) : '';
$src.=$queryString;

if ($args && strlen($args[1])>120) {
    $webmasterid = (isset($args[0]))?$args[0]:1;
    $webmaster = Webmaster::get($webmasterid);
    $jwt = $args[1];
    try {
        $decoded = JWT::decode($jwt, $webmaster->password, array('HS256'));
    } catch(Exception $e) {
        exit('Security error JWT');
    }
    if (gettype($decoded)=='object') {
        $myuser = (array)($decoded);
    } else {
        $myuser = json_decode($decoded, true);
    }
    //print_r($myuser);
    $width = isset($myuser['width'])?$myuser['width']:'100%';
    $height = isset($myuser['height'])?$myuser['height']:'100%';

    if (stripos($width,'px')===FALSE && stripos($width,'%')===FALSE) {
        $width.='px';
    }

    if (stripos($height,'px')===FALSE && stripos($height,'%')===FALSE) {
        $height.='px';
    }
}

ob_start();
?>
<style>
body, html {
	width: 100%;
	height:100%;
	margin:0px;
    overflow-x: hidden;
}
.wrapperChat{
	margin:0 auto;
	width:<?=$width?>;
	height:<?=$height?>;
}
#iframeChat{
	height:100%;
	width:100%;
	border:none;
}
</style>
<div class="wrapperChat">
	<iframe id="iframeChat" allowfullscreen  src="<?=$src?>" allow="geolocation; microphone; camera; allowfullscreen; autoplay"></iframe>
</div>

<?php $script = ob_get_clean(); 
foreach(preg_split("/((\r?\n)|(\r\n?))/", $script) as $line){
    echo "document.write('$line');";
} 
?>