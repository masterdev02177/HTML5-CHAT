<?php
session_start();
include '../Config.php';
include_once 'protect.php';
checkCanEnter('adminpanelCalendar');

ini_set('display_errors', 0);
$webmasterid = $_SESSION['admin'];
$webmaster = Webmaster::get($webmasterid);
$configid = DB::getOne('chat_config', "where webmasterid=$webmasterid")->id;
include 'xcrud/xcrud.php';
include 'lng/language.php';
!empty($_GET['lang']) ? $lng = new Language($_GET['lang']) : $lng = new Language();
$lng = $lng->getData();
$lngPage = $lng['calendar'];

$xcrud = Xcrud::get_instance();
$table = 'chat_calendar';
$xcrud->table($table);
$xcrud->pass_var('webmasterid', $webmasterid);
$xcrud->where('webmasterid =', $webmasterid);

$xcrud->columns('date, title');
$xcrud->fields('date, title, body');

$xcrud->field_tooltip('title', $lngPage['titleCalendar']);
$xcrud->field_tooltip('body', $lngPage['bodyCalendar']);


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


$xcrud2 = Xcrud::get_instance();
$table = 'chat_config';
$xcrud2->table($table);
$xcrud2->pass_var('webmasterid', $webmasterid);
$xcrud2->where('webmasterid =', $webmasterid);
$xcrud2->unset_add();
//$xcrud2->hide_button('save_edit');
$xcrud2->unset_list();
$xcrud2->unset_title();
$xcrud2->fields('displayCalendar');
$xcrud2->label(array(
    'displayCalendar' => 'Display Calendar'
));
$xcrud2->change_type('displayCalendar','bool','',array('id'=>'saveCalendarBtn'));

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

    <style>
        div.xcrud-top-actions a.xcrud-button.xcrud-green.xcrud-action {
            opacity: 0;

        }
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
        <li class="active"><?php echo $lng['menu']['calendar']; ?></li>
    </ul>

  </div>
  <div class="panel-body">
		<div class="flex-property adition-box margin-btm">
			<?php include('freeAccount.php');?>
		</div>


		<div class="admin-table rooms-table">
            <?php echo $xcrud2->render('edit', $configid); ?>
			<?php echo $xcrud->render($webmasterid); ?>
		</div>
  </div>
</div>
 <script>
     $(document).on('saved', function(e) {

     })
     $('#saveCalendarBtn').on('change', function() {
         //var container = $(this).closest(".xcrud-ajax");
         //Xcrud.request(container, Xcrud.list_data(container,{task:'save'}));
         $('a[data-task="save"]').click();
         setTimeout(function() {
             window.location = window.location;
         }, 350)
     })

 </script>
<?php include 'footer.php';?>

</body>
</html>
