<?php
//@session_start();
class Room {
	private static $table = 'chat_room';


	public static function addFavori($webmasterid, $userid, $roomid) 	{
		DB::insert('chat_room_favori', array('webmasterid'=>$webmasterid, 'userid'=>$userid, 'roomid'=>$roomid),false);

	}
	public static function removeFavori($webmasterid, $userid, $roomid) {
		DB::deleteFromTable('chat_room_favori',"WHERE webmasterid=$webmasterid AND roomid=$roomid AND userid=$userid", false);
	}

	public static function getMyFavoris($webmasterid, $userid) {
		if ($userid) {
			$res = DB::getAll('chat_room_favori', "WHERE webmasterid=$webmasterid AND userid=$userid");
		} else {
			$res = [];
		}
		return $res;
	}

	public static function checkPassword($id, $password, $webmasterid) {		
		$room = DB::get($id, Room::$table, "AND password='$password' and webmasterid='$webmasterid'");
		if ($room) {
			return 'ok';
		} else {
			return 'ko';
		}
	}


	public static function insertUserRoleInRoom($webmasterid, $userid, $roomid, $roleid){
		DB::replace('chat_room_role', array('webmasterid'=>$webmasterid, 'userid'=>$userid, 'roomid'=>$roomid, 'roleid'=>$roleid),true);
	}

	public static function createRoom($webmasterid, $name, $password, $ownerid, $description, $reservedToGenderid=0, $isTemporary=true, $reservedToRoles=0) {
		$id = DB::insert(Room::$table, array('webmasterid'=>$webmasterid, 'name'=>$name, 'password'=>$password, 'ownerid'=>$ownerid, 'description'=>$description,
				'reservedToGenderid'=>$reservedToGenderid, 'isTemporary'=>$isTemporary, 'reservedToRoles'=>$reservedToRoles));
		if ($id) {
			return DB::getOneById($id, Room::$table);
		} else {
			return false;
		}
	}


	public static function getChatMessages($roomid, $maxChats=20) {
		//$webmasterid = $_SESSION['webmasterid'];
		$sql = "select * from chat_messages WHERE roomid='$roomid' and private=0 order by id DESC LIMIT 0, $maxChats";
		return DB::fetchArrayObjects($sql);
	}


	public static function getDefault($webmasterid ,$debug=false) {
		return DB::getOne(Room::$table, " WHERE webmasterid=$webmasterid order by `orderRoom` DESC, id ASC ",$debug);
	}
	
	public static function createDefaultRoom($webmasterid) {
		$id = DB::insert(Room::$table, array('name'=>'lobby', 'description'=>'Default room', 'welcome'=>'Welcome {{username}} into room {{room}}', 'webmasterid'=>$webmasterid));
		return DB::getOneById($id, Room::$table);
	}
	
	public static function get($id, $webmasterid) {
		return DB::get($id, Room::$table, "and webmasterid=$webmasterid");
	}
	
	public static function update($id, $values, $webmasterid) {
		return DB::update(Room::$table, $id, "and webmasterid=$webmasterid", $values);
	}
	public static function insert($values, $debug=false) {
		$id = DB::insert(Room::$table, $values, $debug);
		return DB::getOneById($id);
	}
	
	public static function delete($id, $webmasterid) {
		return DB::delete(Room::$table, $id, " and webmasterid=$webmasterid");
	}

	public static function getAll($webmasterid) {
		$sql ="
	SELECT chat_room.id, name, description, users, welcome, image, webcam, reservedToGenderid, reservedToRoles, ownerid, `password`<>'' as isPasswordProtected, chat_room.webmasterid,chat_room.isTemporary,
 	GROUP_CONCAT(chat_room_role.userid) as moderators,
 	GROUP_CONCAT(chat_room_role.roleid) as roles,
 	isAdult
 	FROM chat_room LEFT join chat_room_role ON chat_room.id = chat_room_role.roomid
 	WHERE chat_room.webmasterid=$webmasterid
	Group by chat_room.id
	ORDER by orderRoom DESC
	";
	return DB::getBySQL($sql);


		//return DB::select(Room::$table, array('id', 'name', 'description', 'users', 'welcome', 'image', 'reservedToGenderid',  'ownerid', '`password`<>"" as isPasswordProtected', 'webmasterid'), "WHERE webmasterid=$webmasterid", false);
	}

	public static function getRoomById($roomid, $webmasterid,$debug=false) {
		return DB::getOne(Room::$table, " WHERE webmasterid=$webmasterid  and id='$roomid' ",$debug);
	}

	public static function getRoom($startRoom, $webmasterid) {
		return DB::getOne(Room::$table, " WHERE webmasterid=$webmasterid  and name='$startRoom' ");
	}

	public static function getAllJSON($webmasterid, $token) {
		$rooms = self::getAll($webmasterid);
		return $rooms;
	}


}
