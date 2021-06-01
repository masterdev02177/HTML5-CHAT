<?php
session_start();
include ("../Config.php");
include_once('protect.php');
checkCanEnter('adminReports');

$webmasterid = 1;
$webmaster = Webmaster::get($webmasterid);

include ("lng/language.php");
!empty($_GET['lang']) ? $lng = new Language($_GET['lang']) : $lng = new Language();
$lng = $lng->getData();
include("../chatadmin/xcrud/xcrud.php");

$xcrud = Xcrud::get_instance();
$table = 'chat_alerts_webmaster';
$xcrud->table($table);
$xcrud->pass_var('webmasterid', $webmasterid);
$xcrud->where('webmasterid =', $webmasterid);
$xcrud->columns('date, emailUserWhoReports, usernameAuthor, usernameProblem, description, reportReason');
$xcrud->fields('date, emailUserWhoReports, usernameAuthor, usernameProblem, description, reportReason');
$xcrud->order_by ('id', 'desc');
$xcrud->hide_button('save_new');
$xcrud->hide_button('return');
$xcrud->unset_add();
$xcrud->unset_title();
$xcrud->unset_view();
$xcrud->unset_csv();
$xcrud->unset_limitlist();
$xcrud->unset_numbers();
$xcrud->unset_print();
$xcrud->unset_sortable();
$xcrud->change_type('message', 'textarea');

$xcrud2 = Xcrud::get_instance();
$table = 'chat_alerts_webmaster_room';
$xcrud2->table($table);
$xcrud2->pass_var('webmasterid', $webmasterid);
$xcrud2->where('webmasterid =', $webmasterid);
$xcrud2->columns('date, emailUserWhoReports,  roomNameProblem, description, reportReason');
$xcrud2->fields('date, emailUserWhoReports,  roomNameProblem, description, reportReason');
$xcrud2->order_by ('id', 'desc');
$xcrud2->hide_button('save_new');
$xcrud2->hide_button('return');
$xcrud2->unset_add();
$xcrud2->unset_title();
$xcrud2->unset_view();
$xcrud2->unset_csv();
$xcrud2->unset_limitlist();
$xcrud2->unset_numbers();
$xcrud2->unset_print();
$xcrud2->unset_sortable();
$xcrud2->change_type('message', 'textarea');


//	$xcrud->emails_label(' email');
//$xcrud->show_primary_ai_column(true);
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name=viewport content="width=device-width, initial-scale=1">
    <?php include('css.php');?>
    <?php include('js.php');?>
		<link rel="stylesheet" type="text/css" href="../css/common.css">

    <title>Abuse reports</title>
</head>

<body>
 <div class="panel panel-default admin-panel">
  <div class="panel-heading">
  	<Button class="btn a-header-btn" id="logout"><i class="fa fa-sign-out"></i> <?php echo $lng['menu']['logout']; ?></Button>
    <ul class="breadcrumb">
        <li><a href="loggedon.php"><?php echo $lng['menu']['loggedon']; ?></a></li>
        <li class="active">Abuse reports</li>
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
