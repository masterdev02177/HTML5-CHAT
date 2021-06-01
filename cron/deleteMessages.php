<?php
include_once(__DIR__.'/../classes/DB.php');
// effacer tous les messages maruqés à deleted
$sql = "DELETE FROM `chat_messages` WHERE deleted=1";
echo "$sql\r\n";
DB::execSQL($sql);

//effacer tous les messages<5 jours
$sql = "DELETE FROM `chat_messages` WHERE date< (NOW() - INTERVAL 5 DAY)";
echo "$sql\r\n";
DB::execSQL($sql);

// gerder max 100 messages par compte
$sql = "SELECT webmasterid, count(id) as nombre FROM `chat_messages` group by `webmasterid` HAVING nombre>100 order by nombre desc";
$rows = DB::getBySQL($sql);
foreach ($rows as $row) {
    $numberToDelete = $row->nombre - 200; // on laisse 100
    $sql = "delete from chat_messages WHERE webmasterid=$row->webmasterid ORDER BY id  LIMIT $numberToDelete";
    echo "$sql\r\n";
    DB::executeSQL($sql);
}


//$sql = "DELETE from chat_messages WHERE data < NOW() - INTERVAL 7 DAY";
//DB::executeSQL($sql);