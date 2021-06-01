<?php
session_start();
include ("../Config.php");
include_once('protect.php');
checkCanEnter('adminpanelSounds');

ini_set('display_errors', 0);
$webmasterid = 1;
$webmaster = Webmaster::get($webmasterid);
$configid = DB::getOne('chat_config', "where webmasterid=$webmasterid")->id;
include 'xcrud/xcrud.php';
include 'lng/language.php';
!empty($_GET['lang']) ? $lng = new Language($_GET['lang']) : $lng = new Language();
$lng = $lng->getData();
$lngPage = $lng['calendar'];

$xcrud = Xcrud::get_instance();
$table = 'chat_config';
$xcrud->table($table);
$xcrud->pass_var('webmasterid', $webmasterid);
$xcrud->where('webmasterid =', $webmasterid);


$xcrud->columns('id, soundUserEntersChat, soundUserLeavesChat, soundMessageReceived, soundPrivateMessageReceived, soundPrivateRequested');
$xcrud->fields('soundUserEntersChat, soundUserLeavesChat, soundMessageReceived, soundPrivateMessageReceived, soundPrivateRequested');

$xcrud->field_tooltip('soundUserEntersChat', 'Url of MP3 Sound when user enters the chat (leave blank if none)');
$xcrud->field_tooltip('soundUserLeavesChat', 'Url of MP3 Sound when user leaves the chat (leave blank if none)');
$xcrud->field_tooltip('soundMessageReceived', 'Url of MP3 Sound when message received (leave blank if none)');
$xcrud->field_tooltip('soundPrivateMessageReceived', 'Url of MP3 Sound when private message received (leave blank if none)');
$xcrud->field_tooltip('soundPrivateRequested', 'Url of MP3 Sound when private message requested (leave blank if none)');

$xcrud->change_type('soundUserEntersChat','text');

//view, edit, remove, duplicate, add, csv, print, save_new, save_edit, save_return, return.
$xcrud->hide_button('save_new');
$xcrud->hide_button('save_return');
$xcrud->hide_button('return');
$xcrud->hide_button('add');
$xcrud->hide_button('search');
$xcrud->hide_button('remove');
//$xcrud->unset_add();
//$xcrud->unset_list();
$xcrud->unset_title();
$xcrud->unset_search();
$xcrud->unset_view();
$xcrud->unset_csv();
$xcrud->unset_limitlist();
$xcrud->unset_numbers();
$xcrud->unset_print();
$xcrud->unset_sortable();
$xcrud->label(array(
    'soundUserEntersChat' => 'MP3 Sound when user enters the chat',
    'soundUserLeavesChat' => 'MP3 Sound when user leaves the chat',
    'soundMessageReceived'  => 'MP3 Sound when message received',
    'soundPrivateMessageReceived'  => 'MP3 Sound when private message received',
    'soundPrivateRequested'  => 'MP3 Sound when private message requested'
));



?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name=viewport content="width=device-width, initial-scale=1">
    <?php include('css.php');?>
    <?php include('js.php');?>
		<link rel="stylesheet" type="text/css" href="../css/common.css">
    <title>Sounds</title>

    <style>
        .panel.panel-default .xcrud-details-table td:first-child {
            width: 121px;
        }
    </style>
</head>

<body>
 <div class="panel panel-default admin-panel">
  <div class="panel-heading">
  	<Button class="btn a-header-btn" id="logout"><i class="fa fa-sign-out"></i> <?php echo $lng['menu']['logout']; ?></Button>
    <ul class="breadcrumb">
        <li><a href="loggedon.php"><?php echo $lng['menu']['loggedon']; ?></a></li>
        <li class="active">Sounds</li>
    </ul>

  </div>
  <div class="panel-body">
		<div class="flex-property adition-box margin-btm">
			<?php include('freeAccount.php');?>
		</div>
      <div class="admin-table rooms-table">
          <?php echo $xcrud->render('edit', $configid); ?>
      </div>


  </div>
</div>
 <script>


 </script>
<?php include ('footer.php');?>

</body>
</html>
