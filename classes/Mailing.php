<?php
@require_once(__DIR__.'/../Config.php');
@require_once('DB.php');
$a = isset($_POST['a'])?$_POST['a']:'';
switch ($a) {
	case 'sql':
		$sql = $_POST['sql'];
		echo Mailing::getCountSQL($sql);
		break;
}

class Mailing {
	public static $table = 'chat_mailing_webmaster';

	public static function get($sql) {
		$rows = DB::fetchArrayObjects($sql);
		return $rows;

	}

		public static function getCountSQL($sql) {
			$rows = DB::fetchArrayObjects($sql);
            return count($rows);

		}

	public static function testQueueIsEmpty ()	{
		$res = DB::getOne(self::$table)?'false':'true';
		return $res;
		}

	public static function prepare($sql, $subject, $content) {
		DB::executeSQL("truncate table ".self::$table);
		$mailings = self::get($sql);

		foreach($mailings as $mailing):
			$mailing->subject = DB::real_escape_string($subject);
			foreach ($mailing as $key => $value) {
				//echo "key:$key val:$value content:$content<br>";
				$content = str_replace("[$key]", $value, $content);
			}
			$mailing->content = DB::real_escape_string($content);

			DB::insert(self::$table, (array)$mailing, false);
		endforeach;
		return count($mailings);
	}

	public static function pickRandom() {
		return DB::getOne(self::$table, "WHERE sent = 0 Order by rand()");
	}

	public static function sendEmailSMTP($to, $subject, $message, $from, $debug=false) {
		require_once(__DIR__.'/phpmailer/class.phpmailer.php');
		$mail = new PHPMailer(true);
		$mail->IsSMTP();
		$mail->SMTPDebug  = $debug;
		$mail->Host       = SMTPHost;
		$mail->SMTPDebug  = SMTPDebug;
		$mail->SMTPAuth   = SMTPAuth;
		$mail->Port       = SMTPPort;
		$mail->Username   = SMTPUsername;
		$mail->Password   = SMTPPassword;
		$mail->SMTPSecure = 'ssl';
		$mail->IsHTML(true);
		$mail->CharSet = 'UTF-8';
		$mail->AddAddress($to, $to);
		$mail->SetFrom($from, $from);
		$mail->AddReplyTo($from, $from);

		$mail->Subject = $subject;
		$mail->MsgHTML($message);
		//$mail->Body = $message;
		//die($message);

		$res = $mail->Send();
		// die("SENT to:$to, $subject, $from $message");
		return 'ok';
	}

	public static function sendEmailWithTemplate($username, $to, $subject, $content, $from, $template="mailing.php")  {
		ob_start();
		include(__DIR__."/../email/$template");
		$body = ob_get_clean();
		$body = str_replace("[content]", $content, $body);
		$body = str_replace("[username]", $username, $body);
		$body = str_replace("[email]", $to, $body);
		self::sendEmailSMTP($to, $subject, $body, $from);

	}


	public static function send($mailing) {
		self::sendEmailWithTemplate($mailing->username, $mailing->email, $mailing->subject, $mailing->content, EMAILFROM, 'mailing.php');
		DB::update(self::$table, $mailing->id, array('sent'=>1));
	}

	public static function unsubscribe($username, $email) {
		$username = DB::real_escape_string($username);
		$email = DB::real_escape_string($email);
		$sql ="update chat_webmaster set mailing=0 WHERE email='$email' AND username='$username'";
		DB::executeSQL($sql);
	}

}

