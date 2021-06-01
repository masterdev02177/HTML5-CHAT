<?php
//ini_set('display_errors', 1);error_reporting(E_ALL);
@require_once('DB.php');
@session_start();
$a = (isset($_REQUEST['a']))?$_REQUEST['a']:'';
switch($a) {
    case 'get':
        $webmasterid = $_SESSION['webmasterid'];
        $month = $_REQUEST['month'];
        $year = $_REQUEST['year'];
        $calendar = Calendar::get($webmasterid, $month, $year);
        echo json_encode($calendar);
        break;

    case 'add':
        break;
}



class Calendar {
    private static $table = 'chat_calendar';

    public static function get($webmasterid, $month, $year) {
        return DB::getAll(self::$table, "WHERE webmasterid=$webmasterid and month(date)=$month and year(date)=$year","",false);
    }


    public static function add($webmasterid, $debug=false) {
        return DB::getObjectsFromSql("select date, title, body, true as badge from chat_calendar where webmasterid=$webmasterid");

        //return DB::select(Calendar::$table, array('id', 'title', 'event', 'badge'), "WHERE webmasterid=$webmasterid");
    }
}
