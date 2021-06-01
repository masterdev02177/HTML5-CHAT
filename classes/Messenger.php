<?php


class Messenger
{
    private static $table = 'chat_messenger_messages';

    public static function deleteMessengerMessages($userid, $user2id, $webmasterid, $debug = false) {
        $sql = "
        update chat_messenger_messages set fromidDeleted=1 
        WHERE webmasterid=$webmasterid AND fromid=$user2id and toid=$userid
        ";
        if($debug) echo $sql;
        DB::execSQL($sql);

        $sql = "
        update chat_messenger_messages set toidDeleted=1 
        WHERE webmasterid=$webmasterid AND fromid=$userid and toid=$user2id
        ";
        if($debug) echo $sql;
        DB::execSQL($sql);
    }

    public static function getUnreadMessages($toid, $webmasterid) {
        $sql ="
        SELECT chat_messenger_messages.*
        FROM chat_messenger_messages 
        WHERE webmasterid=$webmasterid AND toid=$toid AND wasRead=0
        GROUP by fromid
        ORDER by id DESC
        ";
        $res = DB::selectAllBySQL($sql);
        return $res;
    }

    public static function getCountMessengerUnread($toid, $webmasterid) {
        $sql ="
        SELECT (count(DISTINCT chat_messenger_messages.fromid)) as messageCount 
        FROM chat_messenger_messages
        WHERE webmasterid=$webmasterid AND toid=$toid AND wasRead=0
        ";
        //return $sql;
        $res = DB::selectOneBySQL($sql);
        return $res->messageCount;
    }


    public static function getMessages($fromid, $toid, $webmasterid, $max=10)
    {
        $messages = DB::getAll(self::$table, "WHERE (fromid=$fromid AND toid=$toid AND fromidDeleted=0) OR (fromid=$toid AND toid=$fromid AND toidDeleted=0)",
            "order by id DESC LIMIT 0,$max",false);
        return json_encode($messages);
    }

    public static function setWasRead($fromid, $toid, $webmasterid)
    {
        DB::updateWhere(self::$table,"WHERE toid=$toid AND fromid=$fromid AND webmasterid=$webmasterid", array('wasRead'=>1) );
        //DB::update(, array('fromid'=>$fromid, 'toid'=>$toid, 'webmasterid'=>$webmasterid, 'wasRead'=>1));
    }

    public static function getUsersWhoSentMeMessages($userid, $webmasterid) {
        $sql ="
        SELECT chat_messenger_messages.*
        FROM chat_messenger_messages 
        WHERE webmasterid=$webmasterid AND toid=$userid
        GROUP by fromid
        ORDER by id DESC
        ";
        $res = DB::selectAllBySQL($sql);
        return $res;
    }

}