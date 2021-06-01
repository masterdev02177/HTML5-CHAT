<?php
require_once(__DIR__.'/Role.php');
require_once(__DIR__.'/Webmaster.php');
require_once(__DIR__.'/Gender.php');
require_once __DIR__.'/../vendor/autoload.php';
use \Firebase\JWT\JWT;
ini_set('display_errors', 0);

class User {
	private static $table = 'chat_users';	
	
	public function __construct() {		
	}

    public static function geoip_country_code_by_name($ip)
    {

        $sql = "SELECT country  FROM chat_ip2nation WHERE ip < INET_ATON('$ip') ORDER BY ip DESC LIMIT 0,1";
        $res = DB::selectOneBySQL($sql);
        return $res->country;
    }


	public static function vote($userid, $user2id, $username, $username2, $webmasterid) {
		return DB::insert('chat_contest', array('userid'=>$userid, 'user2id'=>$user2id, 'username1'=>$username, 'username2'=>$username2, 'webmasterid'=>$webmasterid), false);
	}

	public static function getBestVotes($webmasterid, $max=10, $debug=false) {
		$sql = "SELECT count(chat_contest.id) as total, username2 as username
				FROM chat_contest
				WHERE chat_contest.webmasterid=$webmasterid
				GROUP BY username2
				ORDER BY total DESC LIMIT 0,$max";
		if ($debug) echo $sql;
		return DB::selectAllBySQL($sql);
	}



	public static function addUsers_videoTimeSpent($userid, $webmasterid, $seconds=30) {
		$userid = DB::real_escape_string($userid);
		$webmasterid = DB::real_escape_string($webmasterid);
		$sql = "
		insert into chat_users_videoTimeSpent(userid, webmasterid, seconds) values ($userid, $webmasterid, $seconds)
		ON DUPLICATE KEY UPDATE seconds=seconds+$seconds
		";
		DB::execSQL($sql);
		$secondsSpent = self::getVideoTimeSpent($userid, $webmasterid);
		return $secondsSpent;
	}

	public static function getVideoTimeSpent($userid, $webmasterid) {
		$res = DB::getOne('chat_users_videoTimeSpent',"WHERE userid='$userid' AND webmasterid='$webmasterid'",false);
		if ($res) {
			return $res->seconds;
		} else  {
			return 0;
		}
	}

	public static function mute($webmasterid, $userid, $muteduserid, $debug=false) {
		DB::insert('chat_muted_users', array('webmasterid'=>$webmasterid, 'userid'=>$userid, 'muteduserid'=>$muteduserid),$debug);
	}

	public static function unmute($webmasterid, $userid, $muteduserid, $debug=false) {
		DB::deleteFromTable('chat_muted_users', "WHERE webmasterid=$webmasterid AND userid=$userid and muteduserid=$muteduserid");
	}

	public static function getMutedUsers($webmasterid, $userid) {
		if ($userid) {
			$res = DB::selectOneFieldAsArray('chat_muted_users', 'muteduserid', "WHERE webmasterid=$webmasterid and userid=$userid");
		} else {
			$res = [];
		}
		return $res;
	}

    public static function getJailedUsers($webmasterid)  {
        $res = DB::selectOneFieldAsArray('chat_mute', 'userid', "WHERE webmasterid=$webmasterid");
        return $res;

    }

	public static function getAllByRole($webmasterid, $role) {
		return DB::getAll(User::$table, "WHERE webmasterid=$webmasterid and role='$role'");
	}

	public static function getPerformers($webmasterid) 	{
		$sql = "
			SELECT  id, username,image, genderid, roleid, creditsPerMinute, conference_bigPhoto
			FROM chat_users
			WHERE webmasterid=$webmasterid and role='performer'
			";
		return DB::selectAllBySQL($sql);
	}

	public static function get($id, $webmasterid) {
		return DB::get($id, User::$table, "and webmasterid=$webmasterid");
	}

    public static function getById($id) {
        return DB::get($id, User::$table);
    }
	
	public static function createNewAccount($webmasterid, $username, $email, $password, $genderid, $birthyear, $debug=false) {
		$config = DB::getOne('chat_config', "where webmasterid=$webmasterid");
		$token = uniqid();
		$arr = array('username'=>$username, 'email'=>$email, 'password'=>$password, 'genderid'=>$genderid, 'token'=>$token, 'webmasterid'=>$webmasterid, 'birthyear'=>$birthyear);
		$gender = Gender::get($genderid, $webmasterid);
		//print_r($gender);
		$mappedGender = $gender->mappedGender;


		switch ($mappedGender) {
			case 'male':
				$index = rand(1,20);
				$arr['image'] = "https://server2.buychatroom.com/img/avatars/m/$index.svg";
				break;
			case 'female':
				$index = rand(1,8);
				$arr['image'] = "https://server2.buychatroom.com/img/avatars/f/$index.svg";
				break;
			case 'couple':
				$index = rand(1,2);
				$arr['image'] = "https://server2.buychatroom.com/img/avatars/m/$index.svg";
				break;
		}

		$user = User::insert($arr, false);
		if ($debug)  {
			echo "user:";print_r($user);
		}
		if (!$user) {			
			die(json_encode(array('result'=>'ko', 'message'=>"$email or $username : already used")));
		}
		$siteUrl = $config->siteUrl;
		if (!$siteUrl) {
			$siteUrl = HOME_HTTP;
		}
		if ($config->userMustConfirmEmail) {
			// $siteUrl
			$id = $user->id;
			$link = HOME_HTTP."confirmUser.php?id=$id&token=$token";
			ob_start();
			include(__DIR__."/../email/registerUser.php");
			$body = ob_get_clean();		
			Services::sendEmail($email, $email, "Your account is set up!", $body, EMAILFROM);
			die(json_encode(array('result'=>'ok', 'message'=>"Your account has been set up. You must confirm first: an email has been sent to $email")));
		}
		die(json_encode(array('result'=>'ok', 'message'=>"Your account has been set up. ")));
	}

	public static function checkUsernameTaken($webmasterid, $username )
	{
		$username = DB::real_escape_string($username);
		$user = DB::getOne(self::$table,"WHERE webmasterid=$webmasterid and username='$username'",false);
		return ($user)?'ko':'ok';
	}

	public static function isBanned($ip, $webmasterid, $userid=0) {
		$userCheck = ($userid)?"OR userid=$userid":"";
		$banIP = DB::getOne('chat_ban', "WHERE webmasterid=$webmasterid and (IP='$ip' $userCheck) and until>now()",false);
		$banRange = DB::getOne('chat_ban_range', " WHERE webmasterid=$webmasterid AND fromIP<='$ip' AND toIP>='$ip'");
		return $banIP || $banRange;

	}

	public static function isMuted($ip, $webmasterid) {
		$res = DB::selectOneBySQL("select UNIX_TIMESTAMP(until)*1000 as mutedUntil from chat_mute WHERE webmasterid=$webmasterid and IP='$ip' and until>now()");
		return $res;
	}

	public static function loginJWT($usernameOrEmail, $password, $webmasterid) {
		$usernameOrEmail = DB::real_escape_string($usernameOrEmail);
		$password = DB::real_escape_string($password);
		$config = DB::getOne('chat_config', "where webmasterid=$webmasterid",false);
		$webmaster = DB::getOne('chat_webmaster', "where id=$webmasterid");
		$passwordWebmaster = $webmaster->password;

		$user = DB::getOne(User::$table, "where webmasterid=$webmasterid AND password='$password' AND (username='$usernameOrEmail' or email='$usernameOrEmail')", false);
		if (!$user) {
			return array('result'=>'ko', 'message'=>'No such username in database');
		}
		if ($config->userMustConfirmEmail && !$user->confirmed) {
			return array('result'=>'ko', 'message'=>'You must confirm your email !');
		}
		$user2 = $user;
		$user2->password = md5($webmaster->password);
		//$user2->image = '';
		$jsonString = json_encode($user2);

		$user->jwt = JWT::encode($jsonString, $passwordWebmaster);
		$webmaster = Webmaster::get($webmasterid);
		$user->expired = $webmaster->expired;
		$user->entries = $webmaster->entries;
		$user->free = $webmaster->free;
		$user->redirectUrl = Webmaster::generateLinkJWT($webmasterid, $user->jwt);
		$user->roles = Role::getAll($webmasterid);
		$user->gender = Gender::get($user->genderid, $webmasterid)->gender;
		return($user);
	}

	public static function loginWPAdmin($email, $host) {
		$email = DB::real_escape_string($email);
		$host = DB::real_escape_string($host);
		return DB::getOne('chat_webmaster', "WHERE email='$email' AND wp_url='$host'",false);
	}

	public static function login($usernameOrEmail, $password, $webmasterid) {
		$usernameOrEmail = DB::real_escape_string($usernameOrEmail);
		$password = DB::real_escape_string($password);
		$config = DB::getOne('chat_config', "where webmasterid=$webmasterid");

		$user = DB::getOne(User::$table, "where webmasterid=$webmasterid AND password='$password' AND (username='$usernameOrEmail' or email='$usernameOrEmail')", false);
		if (!$user) {
			return array('result'=>'ko', 'message'=>'No such username in database');
		}
		if ($config->userMustConfirmEmail && !$user->confirmed) {
			return array('result'=>'ko', 'message'=>'You must confirm your email !');
		}
		$webmaster = Webmaster::get($webmasterid);
		$user->expired = $webmaster->expired;
		$user->entries = $webmaster->entries;
		$user->free = $webmaster->free;
		$user->roles = Role::getAll($webmasterid);

		$user->gender = Gender::get($user->genderid, $webmasterid)->gender;
		return($user);
	}

	function uploadPhoto($file , $userid, $webmasterid) {
	$filename = $file['name'];
	if(preg_match('/[.](jpg)|(png)|(jpeg)$/', $filename)) {
		die("Error image format");
	}
	$source = $file['tmp_name'];
	$user = User::get($userid);	
	$targetFileName = sprintf("%s_%s_%s_%s", $user->username, $userid, $webmasterid, $filename);
	$target = "upload/$targetFileName";	
	$targetThumb = "upload/thumb/$targetFileName";	
	move_uploaded_file($source, $target);
	User::update($userid, array('image'=>$targetThumb), $webmasterid);
	
	Services::createThumbnail($target, $target, 640);		
	Services::createCroppedThumbnail($target, $targetThumb, 128, 128);
}
	
	public static function updateAvatar($userid, $password, $webmasterid, $file) {
		$user = User::get($userid, $webmasterid);
		if (!$user) {
			die('ko');
		}
		$userid =  DB::real_escape_string($userid);
		$filename = $file['name'];
		$source = $file['tmp_name'];		
		$targetFileName = sprintf("%s.jpg", $userid);
		$target = "upload/$targetFileName";
		$thumbTarget = "upload/thumb/$targetFileName";
		$thumbTargetCropped = "upload/thumb/$targetFileName";
		if(preg_match('/[.](jpg)|(png)|(jpeg)$/', $filename)) {
			move_uploaded_file($source, $target);
			DB::update(User::$table, $userid, array('image'=>'/'.$thumbTarget));
			Services::createCroppedThumbnail($target, $thumbTargetCropped, 64, 64);
			return $thumbTargetCropped.'?rnd='.rand();
		}		
	}
	
	public static function forgotten($email, $webmasterid) {
		$user = DB::getOne(User::$table, "WHERE webmasterid=$webmasterid and email='$email'");
		if (!$user) {
			return 'ko';
		} else {
			$config = DB::selectOne('chat_config', array('forgottenEmailTemplate','langue', 'fromEmail', 'siteUrl'), "Where webmasterid=$webmasterid", false);
			$siteUrl = $config->siteUrl;
			if (!$siteUrl) {
				$siteUrl = HOME_HTTP;
			}

			$fileJson = file_get_contents(__DIR__.'/../lang/'.$config->langue.".json");

			$traductions = json_decode($fileJson, true);
			$forgottenEmailTemplate = $config->forgottenEmailTemplate;

			$fromEmail = $config->fromEmail;
			if (!$fromEmail) {
				$fromEmail = EMAILFROM;
			}
			$user = DB::getOne('chat_users', "WHERE email='$email'");
			$password = $user->password;
			ob_start();
			include(__DIR__."/../email/forgottenUser.php");
			$body = ob_get_clean();
			Services::sendEmail($email, $email, $traductions['forgottenPassword'], $body, EMAILFROM);
			return 'ok';
		}
	}
	
	public static function update($id, $values, $webmasterid) {
		return DB::update(User::$table, $id, "and webmasterid=$webmasterid", $values);
	}

	public static function insert($user, $debug=false) {
		// create andom avatar
		$number = round(rand(0,30));
		if (!isset($user['image']) || !$user['image']) {
			$image = HOME_HTTP . "img/avatars/$number.svg";
			$user['image'] = $image;
		}
		$id = DB::insert(User::$table, $user, $debug);
		return DB::getOneById($id, User::$table);
	}
	
	public static function delete($id, $webmasterid) {
		return DB::delete(User::$table, $id, "and webmasterid=$webmasterid)");
	}

	public static function getAll($webmasterid) {
		return DB::getAll(User::$table, "WHERE webmasterid=$webmasterid", 'id');
	}
	
}
