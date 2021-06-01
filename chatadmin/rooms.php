<?php
session_start();
include('protect.php');
checkCanEnter('adminpanelRooms');
include ('../Config.php');

$webmasterid = $_SESSION['admin'];
$webmaster = Webmaster::get($webmasterid);
include 'xcrud/xcrud.php';

include ("lng/language.php");
!empty($_GET['lang']) ? $lng = new Language($_GET['lang']) : $lng = new Language();
$lng = $lng->getData();
$lngPage = $lng['rooms'];

$xcrud = Xcrud::get_instance();
$table = 'chat_room';
$xcrud->table($table);
$xcrud->pass_var('webmasterid', $webmasterid);
$xcrud->where('webmasterid =', $webmasterid);

$xcrud->columns('id, name, description, webcam, orderRoom, image, isAdult');
$xcrud->fields('name, webcam, colorPicker, description, welcome, password, image, maxUsers, reservedToGenderid, reservedToRoles, isAdult, orderRoom, tags, country, destructionDate, isHidden', false, 'Config');
$xcrud->change_type('image', 'image', false, array(
        'width' => 48,
        'path' => UPLOAD_ROOMS
        ));

$genders = DB::getAll('chat_gender', "WHERE webmasterid=$webmasterid");
$gendersSelect = array('0'=>'Any gender can enter');
foreach($genders as $gender) {
    $gendersSelect[$gender->id] = $gender->gender.' ONLY';
}
$roles = DB::getAll('chat_roles', "WHERE webmasterid=$webmasterid");
$rolesSelect = array('0'=>'Any role can enter');
foreach($roles as $role) {
    $rolesSelect[$role->id] = $role->role.' ONLY';
}


//print_r($genders);
$xcrud->change_type('reservedToGenderid','select','', $gendersSelect);
$xcrud->change_type('reservedToRoles','select','', $rolesSelect);

$xcrud->change_type('description', 'textarea');
$xcrud->change_type('welcome', 'textarea');

$xcrud->field_tooltip('name', $lngPage['name']);
$xcrud->field_tooltip('webcam', $lngPage['webcam']);
$xcrud->field_tooltip('description', $lngPage['description']);
$xcrud->field_tooltip('welcome', $lngPage['welcome']);
$xcrud->field_tooltip('password', $lngPage['password']);
$xcrud->field_tooltip('image', $lngPage['image']);
$xcrud->field_tooltip('colorPicker', $lngPage['colorPicker']);
$xcrud->field_tooltip('maxUsers', 'Maximum number of users per room');
$xcrud->field_tooltip('reservedToGenderid', 'Room only reserved to gender');
$xcrud->field_tooltip('reservedToRoles', 'Room only reserved to role');
$xcrud->field_tooltip('orderRoom', 'Order of the room (numeric only)');
$xcrud->field_tooltip('isAdult', 'Is this room for adult only ? (in case this room is adult, you can then decide in <a href="#">security panel</a> what to do when user enters an adult room');
$xcrud->field_tooltip('tags', 'Tags of the room');
$xcrud->field_tooltip('country', 'Country');
$xcrud->field_tooltip('destructionDate', 'Room will be destroyed at that date (do not fill it if you want a permanent room)');
$xcrud->field_tooltip('isHidden', 'Is this room hidden to users ? (only useful when you want users to start chat into specific room)');

$xcrud->label(array('colorPicker' => $lngPage['colorPickerL'], 'maxUsers'=>'Max users per room', 'reservedToGenderid'=>'Reserve room to gender',
    'reservedToRoles'=>'Reserve room to role', 'isAdult'=>'Adult room', 'orderRoom'=>'Order of the room', 'destructionDate'=>'Date of the destruction', 'isHidden'=>'Is hidden ?'));

$xcrud->validation_pattern('orderRoom', 'integer');


//view, edit, remove, duplicate, add, csv, print, save_new, save_edit, save_return, return.
$xcrud->hide_button('save_new');
//$xcrud->hide_button('save_return');
$xcrud->hide_button('return');
//$xcrud->unset_add();
//$xcrud->unset_list();
$xcrud->unset_title();
$xcrud->unset_view();
$xcrud->unset_csv();
$xcrud->unset_limitlist();
$xcrud->unset_numbers();
$xcrud->unset_print();
$xcrud->unset_sortable();


?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name=viewport content="width=device-width, initial-scale=1">
    <?php include('css.php');?>
    <?php include('js.php');?>

		<link rel="stylesheet" type="text/css" href="../css/common.css">
    <title><?php echo $lngPage['metaTtitle']; ?></title>
</head>

<body>
 <div class="panel panel-default admin-panel">
  <div class="panel-heading">
  	<Button class="btn a-header-btn" id="logout"><i class="fa fa-sign-out"></i> <?php echo $lng['menu']['logout']; ?></Button>
    <ul class="breadcrumb">
        <li><a href="loggedon.php"><?php echo $lng['menu']['loggedon']; ?></a></li>
        <li class="active"><?php echo $lng['menu']['rooms']; ?></li>
    </ul>
  </div>
  <div class="panel-body">
		<div class="flex-property adition-box margin-btm">
			<?php include('freeAccount.php');?>
		</div>
		<div class="admin-table rooms-table">
			<?php echo $xcrud->render($webmasterid); ?>
		</div>
  </div>
</div>

<?php include ('footer.php');?>

</body>
</html>
