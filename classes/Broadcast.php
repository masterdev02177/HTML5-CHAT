<?php
//ini_set('display_errors', 1);error_reporting(E_ALL);
@session_start();
@require_once('DB.php');
@require_once('DB.php');
$a = (isset($_REQUEST['a']))?$_REQUEST['a']:'';
switch($a) {
    case 'get':
        $webmasterid = $_SESSION['webmasterid'];
        $broadcasts = Broadcast::getAll($webmasterid);
        echo json_encode($broadcasts);
        break;

    case 'add':
        break;
}



class Broadcast {
    private static $table = 'chat_broadcast';

    public static function getAll($webmasterid) {
        return DB::getAll(self::$table, "WHERE webmasterid=$webmasterid","",false);
    }


    public static function save($webmasterid, $filename, $videourl, $thumburl, $duration, $debug=false)    {
        DB::insert(self::$table,array('webmasterid'=>$webmasterid, 'filename'=>$filename, 'videourl'=>$videourl, 'thumburl'=>$thumburl, 'duration'=>$duration),$debug);
    }
}
