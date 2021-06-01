<?php
@require_once('DB.php');
class Tips {

    public static $table = "chat_credits";
    public static $tableHistory = "chat_credits_history";
    public static $tableGifts = "chat_gift";

    public static function give($webmasterid, $userid, $user2id, $credits, $type='tips', $description='')  {
        $userCredits = self::getCredits($webmasterid, $userid);
        if ($userCredits>$credits) {
            self::add($webmasterid, $userid, -$credits);
            self::add($webmasterid, $user2id, $credits);

            self::addHistory($webmasterid, $userid, -$credits, $type, $description);
            self::addHistory($webmasterid, $user2id, $credits, $type, $description);
            return true;
        } else {
            return false;
        }
    }

    public static function purchaseCredits($webmasterid, $userid, $credits) {
        self::add($webmasterid, $userid, $credits);
        $type = 'purchase';
        $description = "purchase $credits credits";
        self::addHistory($webmasterid, $userid, $credits, $type, $description);
    }


    public static function addHistory($webmasterid, $userid, $credits, $type, $description) {
        DB::insert(self::$tableHistory, array('webmasterid'=>$webmasterid, 'userid'=>$userid, 'credits'=>$credits, 'type'=>$type, 'description'=>$description));
    }

    public static function updateCredits($webmasterid, $userid, $credits, $username) {
        self::createIfNotExist($webmasterid, $userid, $credits, $username);
        $sql = "update chat_credits set credits = $credits WHERE  webmasterid=$webmasterid AND userid=$userid";
        DB::execSQL($sql);
        self::addHistory($webmasterid,$userid, $credits, 'update', 'update');

    }

    public static function createIfNotExist ($webmasterid, $userid, $credits=0, $username='')  {
        $userCredits = DB::getOne(self::$table,"WHERE webmasterid=$webmasterid and userid=$userid");
        if (!$userCredits) {
            DB::insert(self::$table, array('userid'=>$userid, 'webmasterid'=>$webmasterid, 'credits'=>$credits, 'username'=>$username));
        }
    }

    public static function add($webmasterid, $userid, $credits, $username) {
        self::createIfNotExist($webmasterid, $userid, $credits, $username);
        $sql = "update chat_credits set credits = credits + $credits WHERE webmasterid=$webmasterid and userid=$userid ";
        DB::execSQL($sql);
    }

    public static function getCredits($webmasterid, $userid, $debug=false)  {
        $res = DB::getOne(self::$table,"WHERE webmasterid=$webmasterid and userid=$userid",$debug);
        return $res->credits;
    }

    public static function purchaseGift($webmasterid, $userid, $user2id, $giftid) {
        $gift = self::getGift($giftid);
        $cost = $gift->credits;
        $user = self::get($webmasterid, $userid);
        if ($user>=$cost) {
            self::add($webmasterid, $userid, -$cost);
            self::addHistory($webmasterid, $userid, $userid, 'gift', $giftid);
        } else {
            return false;
        }
    }


    public static function getGifts() {
        return DB::getAll(self::$tableGifts);
    }


    public static function getGift($id) {
        return DB::get($id, self::$tableGifts);

    }

}