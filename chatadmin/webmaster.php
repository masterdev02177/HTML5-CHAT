<?php
session_start();
include ("../Config.php");
include_once('protect.php');
checkCanEnter('adminpanelMydata');

$webmasterid = 1;
$webmaster = Webmaster::get($webmasterid);

include('xcrud/xcrud.php');
include ("lng/language.php");
!empty($_GET['lang']) ? $lng = new Language($_GET['lang']) : $lng = new Language();
$lng = $lng->getData();
$lngPage = $lng['webmaster'];

$xcrud = Xcrud::get_instance();
$table = 'chat_webmaster';
$xcrud->table($table);
$xcrud->fields('email, email, username, password');

$xcrud->label(array('email' => $lngPage['email'], 'username' => $lngPage['username'], 'password' => $lngPage['password']));
$xcrud->field_tooltip('showSmileys', $lngPage['showSmileys']);

$xcrud->change_type('password','password');

$xcrud->unset_add();
$xcrud->unset_list();
$xcrud->unset_title();

$xcrud->validation_pattern('email', 'email');
$xcrud->validation_pattern('username', '[a-zA-Z0-9_-]{3,14}');

//	$xcrud->emails_label(' email');
//$xcrud->show_primary_ai_column(true);
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name=viewport content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/admin.css">

		<link rel="stylesheet" type="text/css" href="../css/common.css">

    <title><?php echo $lngPage['metaTtitle']; ?></title>
</head>

<body>
 <div class="panel panel-default admin-panel">
  <div class="panel-heading">
  	<Button class="btn a-header-btn" id="logout"><i class="fa fa-sign-out"></i> <?php echo $lng['menu']['logout']; ?></Button>
    <ul class="breadcrumb">
        <li><a href="loggedon.php"><?php echo $lng['menu']['loggedon']; ?></a></li>
        <li class="active"><?php echo $lng['menu']['config']; ?></li>
    </ul>

  </div>
  <div class="panel-body">
		<div class="flex-property adition-box margin-btm">
			<?php include('freeAccount.php');?>
		</div>
		<div class="admin-table rooms-table">
			<?php echo $xcrud->render('edit', $webmasterid); ?>
		</div>

	</div>
  </div>
</div>
<?php include ("footer.php");?>


</body>
</html>
