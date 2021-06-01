<?php
session_start();
include '../Config.php';
include_once 'protect.php';
checkCanEnter('adminpanelUsers');

$webmasterid = 1;
$webmaster = Webmaster::get($webmasterid);

$config = DB::getOne('chat_config', "where webmasterid=$webmasterid");
$configid = $config->id;
$chatType = $config->chatType;


include 'xcrud/xcrud.php';
include 'lng/language.php';
!empty($_GET['lang']) ? $lng = new Language($_GET['lang']) : $lng = new Language();
$lng = $lng->getData();
$lngPage = $lng['users'];

$xcrud = Xcrud::get_instance();
$table = 'chat_users';
$xcrud->table($table);
$xcrud->pass_var('webmasterid', $webmasterid);
$xcrud->where('webmasterid =', $webmasterid);
$xcrud->validation_required('username');
$xcrud->validation_required('email');

$xcrud->columns('username, email, status, role, genderid, role');

if ($chatType=='conference') {
    $xcrud->fields('username, email, password, status, confirmed, role, bannedUntil, IP, genderid, image, conference_bigPhoto');
    $xcrud->change_type('conference_bigPhoto', 'image', '',
        array('width' => 640, 'height' => 480, 'crop' => true, 'path' => UPLOAD_THUMBS)
    );

} else {
    $xcrud->fields('username, email, password, status, confirmed, role, bannedUntil, IP, genderid, image');
}
/*$xcrud->change_type('image', 'image', '',
    array('width' => 64, 'height' => 64, 'crop' => true, 'folder' => '/upload/thumb/')
);*/



$xcrud->relation('genderid','chat_gender','id', 'gender', "webmasterid=$webmasterid");

$xcrud->label(array('genderid' => 'Gender', 'bannedUntil'=>'Banned until', 'image'=>'Avatar image', 'conference_bigPhoto'=>'Big photo for conference'));

$xcrud->validation_pattern('email', 'email');
$xcrud->validation_required('username');

$xcrud->change_type('password', 'password');
$xcrud->change_type('welcome', 'textarea');

$xcrud->field_tooltip('username', $lngPage['username']);
$xcrud->field_tooltip('image', $lngPage['image']);
$xcrud->field_tooltip('bannedUntil', $lngPage['bannedUntil']);
$xcrud->field_tooltip('password', $lngPage['password']);
$xcrud->field_tooltip('IP', $lngPage['IP']);
$xcrud->field_tooltip('role', $lngPage['role']);
$xcrud->field_tooltip('invisible', $lngPage['invisible']);
$xcrud->field_tooltip('canOpenAnyWebcam', $lngPage['canOpenAnyWebcam']);
$xcrud->field_tooltip('conference_bigPhoto', 'Big avatar for conferencer. will be displayed when his webcam if off and also in the listing of online conferences');
$xcrud->field_tooltip('confirmed', 'User confirmed this account by email');

$xcrud->disabled_on_create(array('bannedUntil', 'IP','status'));

//$xcrud->label(array('quitUrl' => 'Url where quit', 'showSmileys'=>'Show smileys'));

//view, edit, remove, duplicate, add, csv, print, save_new, save_edit, save_return, return.
$xcrud->hide_button('save_new');
//$xcrud->hide_button('save_return');
$xcrud->hide_button('return');
//$xcrud->unset_add();
//$xcrud->unset_list();
//$xcrud->unset_title();
$xcrud->unset_view();
$xcrud->unset_csv();
$xcrud->unset_limitlist();
$xcrud->unset_numbers();
$xcrud->unset_print();
$xcrud->unset_sortable();
$xcrud->table_name('Users (available ONLY if <a href="config.php">guest</a> users are disabled)', 'Users (available ONLY if guest users are disabled)');


$table = 'chat_room_role';
$xcrud2 = Xcrud::get_instance();
$xcrud2->table($table);
$xcrud2->pass_var('webmasterid', $webmasterid);
$xcrud2->where('webmasterid =', $webmasterid);

$xcrud2->columns('userid, roleid, roomid');
$xcrud2->fields('userid, roleid, roomid', false);

$xcrud2->relation('userid','chat_users','id','username', "webmasterid = $webmasterid");
$xcrud2->relation('roleid','chat_roles','id','role', "webmasterid = $webmasterid");
$xcrud2->relation('roomid','chat_room','id','name', "webmasterid = $webmasterid");

$xcrud2->label(array('userid'=>'Username', 'roleid'=>'Role', 'roomid'=>'Room'));
$xcrud2->unset_view();
$xcrud2->unset_csv();
$xcrud2->unset_limitlist();
$xcrud2->unset_numbers();
$xcrud2->unset_print();
$xcrud2->unset_sortable();
$xcrud2->table_name('Users Roles per room (available ONLY if <a href="config.php">guest</a> users are disabled)', 'Users Roles per room (available ONLY if guest users are disabled)');



//	$xcrud->emails_label(' email');
//$xcrud->show_primary_ai_column(true);
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta name=viewport content="width=device-width, initial-scale=1">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
			<link rel="stylesheet" type="text/css" href="../css/common.css">
	<script src="../js/jquery.min.js"></script>
	<script src="../js/bootstrap.min.js"></script>
    <title><?php echo $lngPage['metaTtitle']; ?></title>
</head>

<body>
 <div class="panel panel-default admin-panel">
  <div class="panel-heading">
  	<Button class="btn a-header-btn" id="logout"><i class="fa fa-sign-out"></i> <?php echo $lng['menu']['logout']; ?></Button>
    <ul class="breadcrumb">
        <li><a href="loggedon.php"><?php echo $lng['menu']['loggedon']; ?></a></li>
        <li class="active"><?php echo $lng['menu']['users']; ?></li>
    </ul>
  </div>
  <div class="panel-body">
		<div class="flex-property adition-box margin-btm">
			<?php include('freeAccount.php');?>
		</div>
		<div class="admin-table rooms-table">
			<?php echo $xcrud->render($webmasterid); ?>
		</div>

      <div class="admin-table rooms-table">
          <?php echo $xcrud2->render($webmasterid); ?>
      </div>
  </div>
</div>

<?php include ("footer.php");?>

</body>
</html>
