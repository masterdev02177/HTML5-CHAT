<?php include_once 'classes/DB.php';?>
<?php include_once 'classes/User.php';?>
<?php include_once 'classes/Room.php';?>
<?php include_once 'classes/Webmaster.php';?>
<?php include_once 'classes/Friend.php';?>
<?php include_once 'classes/Tips.php';?>
<?php
$a = $_REQUEST['a'];

function post($url, $data) {
    //$data = array('field1' => 'value', 'field2' => 'value');
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        )
    );

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    echo $result;
}

switch($a) {
    case 'updateCredits':
        $webmasterid = $_REQUEST['webmasterid'];
        $config = DB::getOne('chat_config', "WHERE webmasterid=$webmasterid");
        echo post($config->conferenceUrlUpdateCredits, $_POST);
        break;

    case 'sendCredits':
        $webmasterid = $_REQUEST['webmasterid'];
        $config = DB::getOne('chat_config', "WHERE webmasterid=$webmasterid");
        echo post($config->conferenceUrlSendCredits, $_POST);
        break;

    case 'getCredits':
        $userid = $_REQUEST['userid'];
        $webmasterid = $_REQUEST['webmasterid'];
        $credits = Tips::getCredits($webmasterid, $userid);
        echo $credits;
        break;
    case 'getPerformers':
        $webmasterid = $_REQUEST['webmasterid'];
        $token = $_REQUEST['token'];
        $checkToken = Webmaster::checkToken($webmasterid, $token);
        if (!$checkToken) {
            die('ko');
            exit;
        }
        $performers = User::getPerformers($webmasterid);
        echo json_encode($performers);
        break;

    case 'checkToken':
        $webmasterid = $_REQUEST['webmasterid'];
        $token = $_REQUEST['token'];
        return Webmaster::checkToken($webmasterid, $token);
        break;

    case 'voteContest':
        $userid = $_REQUEST['userid'];
        $user2id = $_REQUEST['user2id'];
        $username = $_REQUEST['username'];
        $username2 = $_REQUEST['username2'];
        $webmasterid = $_REQUEST['webmasterid'];
        echo User::vote($userid, $user2id, $username, $username2, $webmasterid);
        break;

    case 'insertUserRoleInRoom':
        $webmasterid = $_REQUEST['webmasterid'];
        $userid = $_REQUEST['userid'];
        $roomid = $_REQUEST['roomid'];
        $roleid = $_REQUEST['roleid'];
        Room::insertUserRoleInRoom($webmasterid, $userid, $roomid, $roleid);
        break;

    case 'deleteRoom':
        $roomid = $_REQUEST['roomid'];
        $webmasterid = $_REQUEST['webmasterid'];
        Room::delete($roomid, $webmasterid);
        break;

    case 'createRoom':
        $webmasterid = $_REQUEST['webmasterid'];
        $name = $_REQUEST['name'];
        $password = $_REQUEST['password'];
        $ownerid = $_REQUEST['ownerid'];
        $description = $_REQUEST['description'];
        $reservedToGenderid = $_REQUEST['reservedToGenderid'];
        $reservedToRoles = $_REQUEST['reservedToRoles'];
        echo json_encode(Room::createRoom($webmasterid,$name,$password, $ownerid, $description, $reservedToGenderid, true, $reservedToRoles));
        break;

    case 'refuseFriend':
        $userid = $_POST['userid'];
        $webmasterid = $_POST['webmasterid'];
        $friendid = $_POST['friendid'];
        Friend::refuse($userid, $friendid, $webmasterid, true);
        break;

    case 'requestFriend':
        $userid = $_POST['userid'];
        $webmasterid = $_POST['webmasterid'];
        $friendid = $_POST['friendid'];
        Friend::add($userid,$friendid,$webmasterid, true);
        break;

    case 'deleteFriend':
        $userid = $_POST['userid'];
        $webmasterid = $_POST['webmasterid'];
        $friendid = $_POST['friendid'];
        Friend::delete($userid,$friendid,$webmasterid);
        break;

    case 'getFriends':
        $userid = $_POST['userid'];
        $webmasterid = $_POST['webmasterid'];
        $friends = Friend::get($userid, $webmasterid);
        echo json_encode($friends, JSON_NUMERIC_CHECK);
        break;

    case 'deleteUserMessages':
        $webmasterid = $_REQUEST['webmasterid'];
        $username = $_REQUEST['username'];
        $sql = "delete from chat_messages where webmasterid=$webmasterid and username = '$username' ";
        DB::executeSQL($sql);
        break;

    case 'getRooms':
        $webmasterid = $_REQUEST['webmasterid'];
        echo json_encode(Room::getAll($webmasterid));
        break;

    case 'incrementUsersInRoom':
        $roomid = $_REQUEST['roomid'];
        DB::executeSQL("update chat_room set users=users+1 WHERE id=$roomid");
        break;

    case 'saveMessage':
        if (!isset($_REQUEST['webmasterid'])) {
            return false;
        }
        $webmasterid = $_REQUEST['webmasterid'];
        $message = $_REQUEST['message'];
        $message = preg_replace('/[\n\r]/','',$message);
        $message = nl2br($message);

        $message = DB::clearData($message);
        $username = $_REQUEST['username'];
        $roomid = $_REQUEST['roomid'];
        $extras = $_REQUEST['extras'];
        $date = $extras['date'];
        $extras = json_encode($extras);
        $user = json_encode($_REQUEST['user']);
        $private = $_REQUEST['private'];
        $ip = isset($_REQUEST['ip'])?ip2long($_REQUEST['ip']):'';
        $id = DB::insert('chat_messages', array('webmasterid'=>"$webmasterid", 'uid'=>$date, 'message'=>"$message", 'username'=>"$username", 'roomid'=>$roomid, 'extras'=>"$extras", 'user'=>"$user", 'private'=>$private, 'ip'=>$ip), true);
        echo $id;
        break;

    case 'decrementtUsersInRoom':
        $roomid = $_REQUEST['roomid'];
        DB::executeSQL("update chat_room set users=users-1 WHERE id=$roomid");
        break;

    case 'loginAdmin':
        if (isset($_REQUEST['email'])) {
            $email = $_REQUEST['email'];
            //$password = $_REQUEST['password'];
            //$res = DB::getOne('chat_webmaster', "WHERE email='$email' and password='$password'");
            $res = DB::getOne('chat_webmaster', "WHERE email='$email'");
            echo json_encode($res);
        }
        break;

    case 'setStatus':
        $status = $_REQUEST['status'];
        $userid = $_REQUEST['userid'];
        $sql = "update chat_users set status='$status' where id='$userid'";
        DB::executeSQL($sql);
        break;

    case 'getQuizRooms':
        $sql = "SELECT chat_quiz.* FROM `chat_quiz`, chat_webmaster WHERE chat_quiz.webmasterid = chat_webmaster.id AND chat_webmaster.paiduntil>now()";
        $rooms = DB::selectAllBySQL($sql);
        //$rooms = DB::getAll('chat_quiz');
        echo json_encode($rooms);
        break;

    case 'getRandomQuestion':
        $webmasterid = $_REQUEST['webmasterid'];
        $question = DB::getOne('chat_quiz_questions', "Where webmasterid=$webmasterid order by rand()",false);
        echo json_encode($question);
        break;

    case 'ban':
        $webmasterid = $_REQUEST['webmasterid'];
        $IP = $_REQUEST['IP'];
        $minutes = $_REQUEST['minutes'];
        $description = $_REQUEST['description'];
        $userid = $_REQUEST['userid'];
        $username = $_REQUEST['username'];
        $moderatorid = $_REQUEST['moderatorid'];
        if (!$IP) {
            $message = DB::getOne('chat_messages', "WHERE uid=$userid", true);
            $IP = long2ip($message->ip);
        }
        $sql = "replace into chat_ban(webmasterid, ip, until, description, date, userid, username, moderatorid)
                values('$webmasterid', '$IP', (NOW() + INTERVAL $minutes MINUTE), '$description', now(), '$userid', '$username', '$moderatorid')";
        echo $sql;
        DB::executeSQL($sql);
        break;

    case 'mute':
        $webmasterid = $_REQUEST['webmasterid'];
        $IP = $_REQUEST['IP'];
        $minutes = $_REQUEST['minutes'];
        $description = $_REQUEST['description'];
        $userid = $_REQUEST['userid'];
        $username = $_REQUEST['username'];
        $moderatorid = $_REQUEST['moderatorid'];

        $sql = "replace into chat_mute(webmasterid, ip, until, description, date, userid, username, moderatorid)
                values('$webmasterid', '$IP', (NOW() + INTERVAL $minutes MINUTE), '$description', now(), '$userid', '$username', '$moderatorid')";
        echo $sql;
        DB::executeSQL($sql);
        break;

    case 'setAllOffline':
        DB::executeSQL('update chat_room set users=0');
        DB::executeSQL("delete from chat_room WHERE ownerid>0");
        break;

}
