<?php
@require_once('DB.php');
//ini_set('display_errors', 1);
//error_reporting(E_ALL);




class Conference {
    private static $table = 'chat_conference';

    /**
     * @param $webmasterid
     * @return conferenceid
     */
    public static function start($roomid, $perfoermid, $userid ) {
        $conf = DB::getOne(self::$table, "WHERE webmasterid = $webmasterid");
        if (!$conf) {
            $id = DB::insert(self::$table, array('webmasterid'=>$webmasterid));
            $conf = DB::get($id, self::$table);
        }
        return $conf->id;
    }


}
