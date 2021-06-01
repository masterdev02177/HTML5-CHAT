<?php
class Friend
{
	public static $table = 'chat_friends';

	public static function get($userid, $webmasterid){

		$friendsIneedToApproveTemp = Array();
		$friendsIRequestedTemp = Array();

		$sql = "SELECT friendid from ".self::$table. " WHERE (userid=$userid) AND webmasterid=$webmasterid";
		$rows = DB::fetchArrayObjects($sql);
		foreach($rows as $row) $friendsIRequestedTemp[] = $row->friendid;

		$sql = "SELECT userid from ".self::$table. " WHERE (friendid=$userid) AND webmasterid=$webmasterid";
		$rows = DB::fetchArrayObjects($sql);
		foreach($rows as $row) $friendsIneedToApproveTemp[] = $row->userid;

		$friendsApproved = array_intersect($friendsIRequestedTemp, $friendsIneedToApproveTemp);
		$friendsIRequested = array_diff($friendsIRequestedTemp, $friendsApproved);
		$friendsIneedToApprove = array_diff($friendsIneedToApproveTemp, $friendsApproved);
		$res = array('friendsApproved'=>array_values($friendsApproved), 'friendsIRequested'=>array_values($friendsIRequested), 'friendsIneedToApprove'=>array_values($friendsIneedToApprove));
		return $res;

	}

	public static function add($userid, $friendid, $webmasterid, $debug = false) {
		$id = DB::insert(self::$table, array(userid=>$userid, friendid=>$friendid, 'webmasterid'=>$webmasterid),$debug);
		return $id;
	}


	public static function delete($userid, $friendid, $webmasterid) {
		$sql = "delete from ".self::$table." WHERE userid=$userid and friendid=$friendid and webmasterid=$webmasterid;";
		DB::executeSQL($sql);
		echo $sql;

		$sql = "delete from ".self::$table." WHERE userid=$friendid and friendid=$userid and webmasterid=$webmasterid;";
		DB::executeSQL($sql);
		echo $sql;

	}

	public static function refuse($userid, $friendid, $webmasterid, $true) {
		$sql = "delete from ".self::$table." WHERE userid=$friendid and friendid=$userid and webmasterid=$webmasterid;";
		DB::executeSQL($sql);
		echo $sql;
	}
}
