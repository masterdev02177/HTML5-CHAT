<?php
//$webmasterid/$token (simple)
// ou bien $webmasterid/$token/$username/$sex
// <script src='https://server2.buychatroom.com/script/1/1010/yarek/cpl'></script>
// BASE 64 avatar !!!
// <script src='https://server2.buychatroom.com/script/1544/5a0440c6c992c/Cams/male/aHR0cHM6Ly9zdGlla2VtYW5vbmllbS5ubC91cGxvYWRzL21vbnRobHlfMjAxN18xMS9hbmEudGh1bWIuanBnLjZmYTFhN2JkNzg3ZmUwYTcwMmQ2YzNkMDU2NGNlYmRlLmpwZw=='></script>
//
ini_set('display_errors', 1);error_reporting(E_ALL);
include_once 'Config.php';
print_r($args);
if (count($args)<2) {
    exit;
}
$src = HOME_HTTP . 'chat/';
foreach ($args as $arg) {
    $src.=$arg.'/';
}
$uri = $_SERVER['REQUEST_URI'];
$queryString = strpos($uri, '?') ? substr($uri, strpos($uri, '?') + 0) : '';
$src.=$queryString;
ob_start();
?>
<style>
body, html {
	width: 100%;
	height:100%;
	margin:0px;
	overflow:auto;
}
.wrapperChat{
	margin:0 auto;
	width:100%;
	height:100%;
}
#iframeChat{
	height:100%;
	width:100%;
	border:none;
}
</style>
<div class="wrapperChat">
	<iframe  id="iframeChat" src="<?=$src?>" allowfullscreen allow="geolocation; microphone; camera; allowfullscreen; autoplay;"; ></iframe>
</div>

<?php $script = ob_get_clean(); 
foreach(preg_split("/((\r?\n)|(\r\n?))/", $script) as $line){
    echo "document.write('$line');";
} 
?>