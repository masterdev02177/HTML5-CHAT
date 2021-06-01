<?php
session_start();
include ("../Config.php");
include_once('protect.php');
checkCanEnter('adminpanelQuiz');

$webmasterid = 1;
$webmaster = Webmaster::get($webmasterid);
include('xcrud/xcrud.php');

$xcrud = Xcrud::get_instance();
$table = 'chat_quiz';
$xcrud->table($table);
$xcrud->pass_var('webmasterid', $webmasterid);
$xcrud->where('webmasterid =', $webmasterid);

$xcrud->relation('roomid','chat_room','id','name', "webmasterid=$webmasterid");

$xcrud->columns('start, end, roomid, seconds, pointsForGoodAnwer');
$xcrud->fields('start, end, roomid, seconds, pointsForGoodAnwer');

$xcrud->field_tooltip('question','question', 'tolerance');
$xcrud->field_tooltip('answer','answer', 'tolerance');

//view, edit, remove, duplicate, add, csv, print, save_new, save_edit, save_return, return.
$xcrud->hide_button('save_new');
$xcrud->hide_button('save_return');
$xcrud->hide_button('return');
//$xcrud->unset_add();
//$xcrud->unset_list();
//$xcrud->unset_title();
$xcrud->unset_view();
$xcrud->unset_search();
$xcrud->unset_csv();
$xcrud->unset_limitlist();
$xcrud->unset_numbers();
$xcrud->unset_print();
$xcrud->unset_sortable();




$xcrud2 = Xcrud::get_instance();
$table = 'chat_quiz_questions';
$xcrud2->table($table);
$xcrud2->pass_var('webmasterid', $webmasterid);
$xcrud2->where('webmasterid =', $webmasterid);

$xcrud2->columns('question, answer, tolerance');
$xcrud2->fields('question, answer, tolerance');


$xcrud2->field_tooltip('question','question', 'tolerance');
$xcrud2->field_tooltip('answer','answer', 'tolerance');


//view, edit, remove, duplicate, add, csv, print, save_new, save_edit, save_return, return.






?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name=viewport content="width=device-width, initial-scale=1">
    <?php include('css.php');?>
    <?php include('js.php');?>
		<link rel="stylesheet" type="text/css" href="../css/common.css">

    <title>Quiz</title>
</head>

<body>
 <div class="panel panel-default admin-panel">
  <div class="panel-heading">
  	<Button class="btn a-header-btn" id="logout"><i class="fa fa-sign-out"></i> Logout</Button>
    <ul class="breadcrumb">
        <li><a href="loggedon.php">Home</a></li>
        <li class="active">Quiz </li>
    </ul>

  </div>
  <div class="panel-body">
		<div class="flex-property adition-box margin-btm">
			<?php include('freeAccount.php');?>
		</div>
		<div class="admin-table rooms-table">
			<?php echo $xcrud->render($webmasterid); ?>
	    <?php echo $xcrud2->render($webmasterid); ?>
		</div>
  </div>
</div>

<?php include ("footer.php");?>
 <script>


 </script>


</body>
</html>
