<?php
session_start();
include '../Config.php';
include_once'protect.php';
checkCanEnter('adminpanelGenders');

include '../classes/DB.php';
include 'lng/language.php';
!empty($_GET['lang']) ? $lng = new Language($_GET['lang']) : $lng = new Language();
$lng = $lng->getData();
$lngPage = $lng['genders'];

$webmasterid = $_SESSION['admin'];
$webmaster = Webmaster::get($webmasterid);
$config = DB::getOne('chat_config', "Where webmasterid=$webmasterid");
$widthGenderIcon = $config->widthGenderIcon;
$heightGenderIcon = $config->heightGenderIcon;

include 'xcrud/xcrud.php';
$xcrud = Xcrud::get_instance();
$table = 'chat_gender';
$xcrud->table($table);
$xcrud->pass_var('webmasterid', $webmasterid);
$xcrud->where('webmasterid =', $webmasterid);

$xcrud->columns('gender, image, color, canBroadcast, webcamAutoStart, showOnTopofUserList');
$xcrud->fields('gender, image, color, canBroadcast, webcamAutoStart, showOnTopofUserList');
$xcrud->change_type('image', 'image', false, array(
        'width' => $widthGenderIcon,
		'height' => $heightGenderIcon,
        'path' => UPLOAD_GENDERS
        ));
$xcrud->change_type('color','text','#000000', array('id'=>'colorpicker'));


$xcrud->field_tooltip('image', $lngPage['image']);
$xcrud->field_tooltip('color', $lngPage['color']);
$xcrud->field_tooltip('canBroadcast', $lngPage['canBroadcast']);
$xcrud->field_tooltip('webcamAutoStart', 'Should that gender auto starts his webcam (ex: females will auto start the webcam)');
$xcrud->field_tooltip('showOnTopofUserList', 'Should user be displayed on the top of the UserList ? (ex: girls on top)');

$xcrud->label(array('image' => sprintf($lngPage['imageL'], $widthGenderIcon, $heightGenderIcon), 'color' => $lngPage['colorL'], 'showOnTopofUserList','webcamAutoStart'=>'Webcam auto start',
    'canBroadcast' => $lngPage['canBroadcastL'], 'showOnTopofUserList' => 'Show on Top of UserList' ) );


//$xcrud->label(array('quitUrl' => 'Url where quit', 'showSmileys'=>'Show smileys'));
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



//	$xcrud->emails_label(' email');
//$xcrud->show_primary_ai_column(true);
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <?php include('css.php');?>
    <?php include('js.php');?>
			<link rel="stylesheet" type="text/css" href="../css/common.css">
			<link rel="stylesheet" type="text/css" href="../css/colorPicker.css">
    <title><?php echo $lngPage['metaTtitle']; ?></title>
</head>

<body>
 <div class="panel panel-default admin-panel">

  <div class="panel-heading">
  	<Button class="btn a-header-btn" id="logout"><i class="fa fa-sign-out"></i> <?php echo $lng['menu']['logout']; ?></Button>
    <ul class="breadcrumb">
        <li><a href="loggedon.php"><?php echo $lng['menu']['loggedon']; ?></a></li>
        <li class="active"><?php echo $lng['menu']['genders']; ?></li>
    </ul>

  </div>
  <div class="panel-body">
		<div class="flex-property adition-box margin-btm">
	        <?php include('freeAccount.php');?>
		</div>
		<div class="admin-table rooms-table">
            <div>
                Icon size <?=$widthGenderIcon?>px * <?=$heightGenderIcon?>px. <a href="config.php">You can change the size</a>
            </div>
			<?php echo $xcrud->render($webmasterid); ?>
		</div>
  </div>
</div>
<?php include ("footer.php");?>
</body>
<script src="../js/jquery.colorPicker.min.js"></script>

<script>
jQuery(document).on("ready xcrudafterrequest", function(){
    jQuery("#colorpicker").colorPicker();
    jQuery("table.xcrud-details-table td:eq(5)").append("<button class='btn btn-xs' id='resetBtn'>No color</button>");
    jQuery(document).on('click','#resetBtn', function(e) {
        jQuery("#colorpicker").val('');
        jQuery("div.colorPicker-picker").css('background-color', '#FFF');
    })
});
</script>

</html>
