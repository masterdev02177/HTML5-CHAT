<?php
class Role
{
	public static $table = 'chat_roles';

	// update `chat_roles` set canCreateDynamicRoomNumber=5 where role in ('admin', 'moderator')
	public static function createDefaultRoles($webmasterid, $wordpress=false) {
	    $arr = array('role' => 'admin', 'webmasterid' => $webmasterid, 'canKick'=>1, 'canBan'=>1, 'canBroadcast'=>1, 'canPostYouTube'=>1, 'canDeleteUserMessages'=>1, 'canBeKicked'=>0, 'canBeBanned'=>0, 'canBeMuted'=>0, 'canBeMutedPrison'=>0,  'canCreateDynamicRoomNumber'=>5,
            'canMute'=>1, 'canAccessPasswordProtecedRooms'=>1, 'canPromote'=>1, 'canBePromoted'=>0, 'power'=>9, 'canEnterChatAdmin'=>1,	'canMutePrison'=>1,
            'canChangeAvatar'=>1, 'conference_canUsersShowWebcamInPublic'=>1, 'canGetIP'=>1, 'canInviteToWatchCam'=>1, 'canAudioMute'=>1, 'canAudioBeMuted'=>0);
	    if($wordpress) {
	        $arr['mappedRole'] = 'administrator';
        }
		DB::insert(Role::$table, $arr);

		$arr = array('role' => 'moderator', 'webmasterid' => $webmasterid, 'canKick'=>1, 'canBan'=>1, 'canBroadcast'=>1, 'canPostYouTube'=>1, 'canDeleteUserMessages'=>1, 'canBeKicked'=>0,
            'canBeBanned'=>0, 'canBeMuted'=>0, 'canBeMutedPrison'=>0,'canCreateDynamicRoomNumber'=>5, 'canMute'=>1, 'canAccessPasswordProtecedRooms'=>1, 'power'=>8, 'canMutePrison'=>1,
            'canChangeAvatar'=>1, 'conference_canUsersShowWebcamInPublic'=>1, 'canGetIP'=>1, 'canInviteToWatchCam'=>1, 'canAudioMute'=>1, 'canAudioBeMuted'=>0);
        if($wordpress) {
            $arr['mappedRole'] = 'author';
        }
		DB::insert(Role::$table, $arr);

        $arr = array('role' => 'dj', 'webmasterid' => $webmasterid, 'canKick'=>1, 'canBan'=>0, 'canBroadcast'=>1, 'canPostYouTube'=>1, 'canDeleteUserMessages'=>0, 'power'=>6,
            'canChangeAvatar'=>1, 'conference_canUsersShowWebcamInPublic'=>1, 'canInviteToWatchCam'=>1, 'canAudioMute'=>1, 'canAudioBeMuted'=>0);
        if($wordpress) {
            $arr['mappedRole'] = 'contributor';
        }
        DB::insert(Role::$table, $arr);


        $arr = array('role' => 'performer', 'webmasterid' => $webmasterid, 'canKick'=>1, 'canBan'=>1, 'canBroadcast'=>1, 'canPostYouTube'=>1, 'canDeleteUserMessages'=>1, 'canBeKicked'=>0,
            'canBeBanned'=>0, 'canBeMuted'=>0, 'canBeMutedPrison'=>0, 'canCreateDynamicRoomNumber'=>5, 'canMute'=>1, 'power'=>7, 'canChangeAvatar'=>1, 'conference_canUsersShowWebcamInPublic'=>1, 'canInviteToWatchCam'=>1);
		DB::insert(Role::$table, $arr);

		//
        $arr = array('role' => 'user', 'webmasterid' => $webmasterid, 'power'=>5, 'canChangeAvatar'=>1, 'canInviteToWatchCam'=>1);
        if($wordpress) {
            $arr['mappedRole'] = 'subscriber';
        }

		DB::insert(Role::$table, $arr);

		DB::insert(Role::$table, array('role' => 'custom1', 'webmasterid' => $webmasterid, 'power'=>4));
		DB::insert(Role::$table, array('role' => 'custom2', 'webmasterid' => $webmasterid, 'power'=>4));
		DB::insert(Role::$table, array('role' => 'custom3', 'webmasterid' => $webmasterid, 'power'=>4));
		DB::insert(Role::$table, array('role' => 'seller', 'webmasterid' => $webmasterid, 'power'=>4, 'selectUserAndShowWebcam'=>1));
		DB::insert(Role::$table, array('role' => 'buyer', 'webmasterid' => $webmasterid, 'power'=>4));
		DB::insert(Role::$table, array('role' => 'guest', 'webmasterid' => $webmasterid, 'canSendPrivate'=>1, 'canWhisper'=>1, 'power'=>0,  'canAddRoomToFavori'=>0));
	}
/*
 * insert into chat_roles (webmasterid, role,canKick, canBan, canBroadcast, canPostYouTube, canDeleteUserMessages, canBeKicked, canBeBanned, canBeMuted, canCreateDynamicRoomNumber, canMute )
  select webmasterid, 'performer', 1,1,1,1,1,0,0,0,0,1 from chat_roles where role = 'admin'

  update `chat_roles` set canPromote=1 where role="admin"
*/

	public static function getAll($webmasterid)
	{
		$roles = array();
		$rows = DB::getAll(Role::$table, "WHERE webmasterid=$webmasterid");
		foreach($rows as $role) {
			$roles[$role->role] = $role;
		}
		return $roles;
	}

	public static function get($id, $webmasterid)
	{
		return DB::get($id, Role::$table, "and webmasterid=$webmasterid order by id");
	}


	public static function update($id, $values, $webmasterid)
	{
		return DB::update(Role::$table, $id, "and webmasterid=$webmasterid", $values);
	}

	public static function insert($values, $debug = false)
	{
		$id = DB::insert(Role::$table, $values, $debug);
		return DB::getOneById($id);
	}

	public static function delete($id, $webmasterid)
	{
		return DB::delete(Role::$table, $id, "and webmasterid=$webmasterid)");
	}
}
