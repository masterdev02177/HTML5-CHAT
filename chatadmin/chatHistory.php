<?php
session_start();
include ("../Config.php");
include_once('protect.php');
checkCanEnter('adminpanelHistory');

$webmasterid = 1;
$webmaster = Webmaster::get($webmasterid);

include ("lng/language.php");
!empty($_GET['lang']) ? $lng = new Language($_GET['lang']) : $lng = new Language();
$lng = $lng->getData();
$lngPage = $lng['chatHistory'];

include("../chatadmin/xcrud/xcrud.php");
$xcrud = Xcrud::get_instance();
$table = 'chat_messages';
$xcrud->table($table);
$xcrud->pass_var('webmasterid', $webmasterid);
$xcrud->where('webmasterid =', $webmasterid);

$xcrud->columns('date, username, message, private, ip');
$xcrud->fields('date, username, message, private, ip');
$xcrud->order_by ('id', 'desc');

$xcrud->field_tooltip('date', $lngPage['date']);
$xcrud->field_tooltip('username', $lngPage['username']);

//$xcrud->label(array('quitUrl' => 'Url where quit', 'showSmileys'=>'Show smileys'));

//view, edit, remove, duplicate, add, csv, print, save_new, save_edit, save_return, return.
$xcrud->hide_button('save_new');
//$xcrud->hide_button('save_return');
$xcrud->hide_button('return');
$xcrud->unset_add();
//$xcrud->unset_list();
$xcrud->unset_title();
$xcrud->unset_view();
//$xcrud->unset_csv();
$xcrud->unset_limitlist();
$xcrud->unset_numbers();
$xcrud->unset_print();
$xcrud->unset_sortable();
$xcrud->change_type('message', 'textarea');
$xcrud->change_type('ip', 'text');
//$xcrud->before_insert('setIP'); // automatic call of functions.php
//$xcrud->before_update('setIP'); // automatic call of functions.php
//$xcrud->field_callback('ip','nice_input');
$xcrud->column_callback('ip','convertIP');
$xcrud->field_callback('ip','convertIP');


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

    <title><?php echo $lngPage['metaTtitle']; ?></title>
</head>

<body>
 <div class="panel panel-default admin-panel">
  <div class="panel-heading">
  	<Button class="btn a-header-btn" id="logout"><i class="fa fa-sign-out"></i> <?php echo $lng['menu']['logout']; ?></Button>
    <ul class="breadcrumb">
        <li><a href="loggedon.php"><?php echo $lng['menu']['loggedon']; ?></a></li>
        <li class="active"><?php echo $lng['menu']['chatHistory']; ?></li>
    </ul>
  </div>
  <div class="panel-body">
		<div class="flex-property adition-box margin-btm">
			<?php include('freeAccount.php');?>
		</div>
		<div class="admin-table rooms-table">
			<?php echo $xcrud->render($webmasterid); ?>
		</div>
        <div>
            <button id="deleteAllMessagesBtn" style="margin: 20px;" class="btn btn-danger pull-right">Delete All Messages</button>
        </div>
  </div>
</div>
<?php include ("footer.php");?>
 <script>
     $('#deleteAllMessagesBtn').click(function() {
         bootbox.confirm('Are you sure you want to delete ALL messages ?', function(res) {
             if (!res) {
                 return;
             }
             $.post('/ajax.php', {a: 'deleteAllMessages'}, function (res) {
                 window.location = window.location;
             });

         })
     })
 </script>
</body>
</html>
