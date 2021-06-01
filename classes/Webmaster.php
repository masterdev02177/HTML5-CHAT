<?php
//ini_set('display_errors', 1);error_reporting(E_ERROR);
require_once (__DIR__.'/DB.php');
require_once (__DIR__.'/../vendor/autoload.php');
use \Firebase\JWT\JWT;


class Webmaster {
	private static $table = 'chat_webmaster';

	public static function createAccountWP($username, $email, $wp_url, $wp_register_url, $wp_login_url, $lang='en', $debug=false) {
        if (Services::isJunkMail($email)) {
            die(json_encode(array('result'=>'ko', 'message'=>"Error: Junk mail is not allowed here !")));
        }
        $token = uniqid();
        $password = uniqid();
		// test if ALREADY created
		$webmaster = Webmaster::getByWP_URL($wp_url);
		if (!$webmaster) {
			$webmaster = Webmaster::replace(
					array(  'username' => $username, 'email' => $email, 'password' => $password, 'token' => $token,
							'wp_url' => $wp_url, 'wp_register_url' => $wp_register_url, 'wp_login_url' => $wp_login_url, 'confirmed' => 1),
					$debug);
		}

        if ($debug)  {
            //echo "webmaster:";print_r($webmaster);
        }
        if (!$webmaster) {
            //die(json_encode(array('result'=>'ko', 'message'=>"Error: $email is already used")));
            $webmaster = DB::getOne(self::$table, "WHERE email='$email'");
        } else {
            Room::createDefaultRoom($webmaster->id);
            Gender::createDefaultGenders($webmaster->id);
            Config::createDefaultConfig($webmaster->id, $email, $lang);
			Role::createDefaultRoles($webmaster->id);
        }

        $script = Webmaster::generateScript($webmaster->id);
        $webmasterid = $webmaster->id;
        
        ob_start();
        include(__DIR__."/../email/registerWebmasterWP.php");
        $body = ob_get_clean();
        Services::sendEmail($email, $email, 'Your account is set up!', $body, EMAILFROM);
        die(json_encode(array('result'=>'ok', 'message'=>"Your account has been set up. An email has been sent to $email. Please check your email to setup your account.")));
	}

	public static function createNewAccount($username, $email, $password, $lang='en', $debug=false) {
		if (Services::isJunkMail($email)) {
			die(json_encode(array('result'=>'ko', 'message'=>"Error: Junk mail is not allowed here !")));
		}
		$token = uniqid();
		$webmaster = Webmaster::insert(array('username'=>$username, 'email'=>$email, 'password'=>$password, 'token'=>$token), $debug);
		if ($debug)  {
			echo "webmaster:";print_r($webmaster);
		}
		if (!$webmaster) {			
			die(json_encode(array('result'=>'ko', 'message'=>"Error: $email is already used")));
		}
		Room::createDefaultRoom($webmaster->id);
		Gender::createDefaultGenders($webmaster->id);
		Config::createDefaultConfig($webmaster->id, $email, $lang);
		Role::createDefaultRoles($webmaster->id);

		$script = Webmaster::generateScript($webmaster->id);
		$webmasterid = $webmaster->id; 
		$link = HOME_HTTP."confirm.php?id=$webmasterid&token=$token";
		ob_start();
		include(__DIR__."/../email/registerWebmaster.php");
		$body = ob_get_clean();		
		Services::sendEmail($email, $email, "Your account is set up!", $body, EMAILFROM);
		die(json_encode(array('result'=>'ok', 'message'=>"Your account has been set up. An email has been sent to $email. Please check your email to setup your account.")));
	}
	
	public static function generateScript($webmasterid, $token) {
		$script = sprintf("<script src='%s'></script>", HOME_HTTP. "script/$webmasterid/$token");
		return $script;
	}
	public static function generateLink($webmasterid, $token) {
		$link = HOME_HTTP. "chat/$webmasterid/$token";
		return $link;
	}

	public static function generateLinkJWT($webmasterid, $jwt) {
		$link = HOME_HTTP. "chat/$webmasterid/$jwt";
		return $link;
	}

	public static function loginJWT($jwt, $password) {
		try {
			$decoded = JWT::decode($jwt, $password, array('HS256'));
		} catch(Exception $e) {
			exit('error');
		}
		$myuser = (array)$decoded;
		//$myuser['isAdmin'] = ($myuser['role']=='admin');
		return $myuser;
	}

	public static function login($email, $password, $debug = false) {
		return DB::getOne(Webmaster::$table, "WHERE (email='$email' or username='$email') and password='$password'",$debug);
	}	
	
	public static function forgotten($email) {
		$email = DB::real_escape_string($email);
		$fromEmail = EMAILFROM;
		$webmaster = DB::getOne(Webmaster::$table, "WHERE email='$email'", false);
		$password = $webmaster->password;
		if (!$webmaster) {
			return 'ko';
		} else {
			ob_start();
			include(__DIR__."/../email/forgottenWebmaster.php");
			$body = ob_get_clean();
			Services::sendEmail($email, $email, 'Forgotten password', $body, $fromEmail); 
			return 'ok';
		}
	}
	public static function resendConfirmation($email) {
		$email = DB::real_escape_string($email);

		$fromEmail = EMAILFROM;
		$webmaster = DB::getOne(Webmaster::$table, "WHERE email='$email'", false);
		$webmasterid = $webmaster->id;
		$token = $webmaster->token;
		$link = $link = HOME_HTTP."confirm.php?id=$webmasterid&token=$token";

		if (!$webmaster) {
			return "ko";
		} else {
			ob_start();
			include(__DIR__."/../email/registerWebmaster.php");
			$body = ob_get_clean();
			Services::sendEmail($email, $email, 'Account activation', $body, $fromEmail);
			return "ok";
		}
	}
	
	public static function get($id, $debug = false) {
		$webmaster =  DB::get($id, Webmaster::$table, '', $debug);
		if ($webmaster) {
			$webmaster->free = false;
			$webmaster->expired = false;
			if ($webmaster->paiduntil == '0000-00-00') {
				$webmaster->free = true;
			} else if ($webmaster->paiduntil<date('Y-m-d')) {
				$webmaster->expired = true;
			} 
		}
		return $webmaster;
	}
	
	public static function update($id, $values) {
		return DB::update(Webmaster::$table, $id, $values);
	}
	public static function insert($values, $debug=false) {
		$id = DB::insert(Webmaster::$table, $values, $debug);
		return DB::getOneById($id, Webmaster::$table);
	}

	public static function replace($values, $debug=false) {
		$id = DB::replace(Webmaster::$table, $values, $debug);
		return DB::getOneById($id, Webmaster::$table);
	}

	public static function delete($id, $webmasterid) {
		return DB::delete(Webmaster::$table, $id);
	}

	public static function getAll() {
		return DB::getAll(Webmaster::$table);
	}

	public static function incrementEntries($id)
	{
		$sql = "update ".self::$table." set entries=entries+1 WHERE id=$id";
		DB::executeSQL($sql);
	}

	public static function generateScriptAutoConnect($webmasterid, $token, $sampleUsername, $sampleSex) {
		$script = sprintf("<script src='%s'></script>", HOME_HTTP. "script/$webmasterid/$token/$sampleUsername/$sampleSex");
		return $script;
	}
	public static function generateScriptAutoConnectWithAvatar($webmasterid, $token, $sampleUsername, $sampleSex,$avatar) {
		$script = sprintf("<script src='%s'></script>", HOME_HTTP. "script/$webmasterid/$token/$sampleUsername/$sampleSex/".base64_encode($avatar));
		return $script;
	}

	public static function getByWP_URL($wp_url) {
		$wp_url = DB::real_escape_string($wp_url);
		return DB::getOne(self::$table, "WHERE wp_url='$wp_url' Order by id DESC", false);
	}

	public static function getNumberActiveAccounts() 	{
		$sql = "select count(id) as nombre from ".self::$table." where entries>0";
		$obj = DB::fetchObject($sql);
		return $obj;
	}

	public static function reportUserEmail($webmasterid, $emailUserWhoReports, $usernameAuthor, $usernameProblem, $content='', $reportReason=''){
		$webmaster = Webmaster::get($webmasterid);
		// title et content
		$title = "Moderation requested by $usernameAuthor ($emailUserWhoReports) about $usernameProblem ($reportReason)";
		ob_start();
		include(__DIR__."/../email/template.php");
		$body = ob_get_clean();
		DB::insert('chat_alerts_webmaster',
				array('webmasterid'=>$webmasterid, 'emailUserWhoReports'=>$emailUserWhoReports,
						'usernameAuthor'=>$usernameAuthor, 'usernameProblem'=>$usernameProblem, 'description'=>$content, 'reportReason'=>$reportReason), false);
		Services::sendEmail($webmaster->email, $emailUserWhoReports, "Moderation required from $emailUserWhoReports", $body, EMAILFROM);

		$users = DB::getAll('chat_users',"WHERE webmasterid=$webmasterid and (role='admin' or role='moderator')");
		foreach($users as $user) {
			Services::sendEmail($user->email, $emailUserWhoReports, "Moderation required from $emailUserWhoReports", $body, EMAILFROM);
		}
		exit;
	}
	public static function reportUserRoomEmail($webmasterid, $emailUserWhoReports, $usernameAuthor, $roomNameProblem, $content='', $reportReason=''){
		$webmaster = Webmaster::get($webmasterid);
		// title et content
		$title = "Room Moderation requested by $usernameAuthor ($emailUserWhoReports) about $roomNameProblem ($reportReason)";
		ob_start();
		include(__DIR__."/../email/template.php");
		$body = ob_get_clean();
		DB::insert('chat_alerts_webmaster_room',
				array('webmasterid'=>$webmasterid, 'emailUserWhoReports'=>$emailUserWhoReports, 'roomNameProblem'=>$roomNameProblem, 'description'=>$content, 'reportReason'=>$reportReason), false);
		Services::sendEmail($webmaster->email, $emailUserWhoReports, "Room Moderation required from $emailUserWhoReports", $body, EMAILFROM);

		$users = DB::getAll('chat_users',"WHERE webmasterid=$webmasterid and (role='admin' or role='moderator')");
		foreach($users as $user) {
			Services::sendEmail($user->email, $emailUserWhoReports, "Room Moderation required from $emailUserWhoReports", $body, EMAILFROM);
		}
		exit;
	}
	public static function checkToken($webmasterid, $token) {
		return DB::getOne(self::$table,"WHERE id=$webmasterid and token = '$token'",false);
	}

	public static function getNumberCreatedAccounts($days) {
		$date = date('Y-m-d 00:00:00', strtotime("-$days days"));
		$sql = "select count(id) as nombre from ".self::$table." where date>'$date'";
		$obj = DB::fetchObject($sql);
		//print_r($obj);
		$obj->nombre;
		return $obj->nombre;
	}

}