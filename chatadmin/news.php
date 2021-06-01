<?php
session_start();
include '../Config.php';
include_once 'protect.php';
include_once '../classes/Room.php';

checkCanEnter('adminpanelNews');

$webmasterid = $_SESSION['admin'];
$webmaster = Webmaster::get($webmasterid);

include 'xcrud/xcrud.php';
include 'lng/language.php';
!empty($_GET['lang']) ? $lng = new Language($_GET['lang']) : $lng = new Language();
$lng = $lng->getData();
$lngPage = $lng['news'];

$xcrud = Xcrud::get_instance();
$table = 'chat_news';
$xcrud->table($table);
$xcrud->pass_var('webmasterid', $webmasterid);
$xcrud->where('webmasterid =', $webmasterid);

$xcrud->columns('news, active, isPopup, startHour, endHour');
$now = date("H:i:s");
$xcrud->label(array(
    'startHour'=>"Start Hour",
    'endHour'=>"End Hour",
    'isPopup'=>'Show this news as modal popup window',
    'display_news_minutes'=>'Display frequency (in minutes)'
));

$xcrud->change_type('isPopup','bool', '', array('id'=>'isPopup'));
$xcrud->change_type('startHour','time', '', array('id'=>'startHour'));
$xcrud->change_type('endHour','time', '', array('id'=>'endHour'));
$xcrud->change_type('display_news_minutes','int', '', array('id'=>'display_news_minutes2'));



$xcrud->field_tooltip('startHour', "What hour news should be active. (Use Paris time, Paris time is now: $now)<br>(leave 00:00:00 if none)");
$xcrud->field_tooltip('endHour', "Until what hour news should be active. (Use Paris time, Paris time is now: $now)<br>(leave 00:00:00 if none)");
$xcrud->field_tooltip('isPopup', "Should that news be popped up in a modal window ? (Use that with parsimony only for very important news)");
$xcrud->field_tooltip('display_news_minutes', "Frequency of a news: how often should a news be displayed (in minutes)");


$xcrud->fields('news, active, isPopup, startHour, endHour, display_news_minutes');
$xcrud->field_tooltip('news', $lngPage['news']);
$xcrud->field_tooltip('active', $lngPage['active']);



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

$config = DB::getOne('chat_config', "where webmasterid = $webmasterid",false);

$xcrud2 = Xcrud::get_instance();
$table = 'chat_config';
$xcrud2->table($table);
$xcrud2->pass_var('webmasterid', $webmasterid);
$xcrud2->where('webmasterid =', $webmasterid);

$xcrud2->columns('display_news_minutes');
$xcrud2->fields('display_news_minutes');
$xcrud2->change_type('display_news_minutes','text','', array('id'=>'display_news_minutes'));


$xcrud2->label(array('display_news_minutes'=>'News frequency (in minutes)'));
$xcrud2->validation_pattern('display_news_minutes', 'integer');
//$xcrud2->change_type('display_news_minutes','integer','',5);

$xcrud2->field_tooltip('display_news_minutes', 'Display News Every N minutes');
$xcrud2->unset_add();
$xcrud2->unset_list();
$xcrud2->unset_title();
$configid = DB::getOne('chat_config', "where webmasterid=$webmasterid")->id;
$frequency = $config->display_news_minutes;


// RSS
$xcrud3 = Xcrud::get_instance();
$table3 = 'chat_rss';
$xcrud3->table($table3);
$xcrud3->pass_var('webmasterid', $webmasterid);
$xcrud3->where('webmasterid =', $webmasterid);
$roomsRows = Room::getAll($webmasterid);
$rooms = array('0'=>'All rooms');
foreach($roomsRows as $room) {
    $rooms[$room->id] =  $room->name;
}
$xcrud3->columns('url, roomid, enabled, startHour, endHour');
$xcrud3->fields('url, roomid, enabled, startHour, endHour');
$xcrud3->validation_required('url');
$xcrud3->change_type('roomid','select','0', $rooms);

$xcrud3->label(array(
    'url'=>'RSS URL',
    'roomid'=>'Room',
    'titleTag'=>'Tile Tag',
    'linkTag'=>'Link Tag',
    'descriptionTag'=>'Desscription Tag',
    'enabled'=>'Enabled',
    'startHour'=>'Start hour',
    'endHour'=>'End hour',
));

$xcrud3->field_tooltip('url', 'Url of the XML RSS');
$xcrud3->field_tooltip('roomid', 'Room where the RSS will be displayed');
$xcrud3->field_tooltip('titleTag', 'Tag that contains the title');
$xcrud3->field_tooltip('linkTag', 'Tag that contains the URL');
$xcrud3->field_tooltip('descriptionTag', 'Tag that contains the description');
$xcrud3->field_tooltip('enabled', 'Should be enabled ?');
$xcrud3->field_tooltip('startHour', "What hour RSS should be active. (Use Paris time, Paris time is now: $now)<br>(leave 00:00:00 if none)");
$xcrud3->field_tooltip('endHour', "Until what hour RSS should be active. (Use Paris time, Paris time is now: $now)<br>(leave 00:00:00 if none)");

$xcrud3->hide_button('save_new');
$xcrud3->hide_button('return');
$xcrud3->unset_title();
$xcrud3->unset_view();
$xcrud3->unset_csv();
$xcrud3->unset_limitlist();
$xcrud3->unset_numbers();
$xcrud3->unset_print();
$xcrud3->unset_sortable();
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name=viewport content="width=device-width, initial-scale=1">
    <?php include 'css.php';?>
    <?php include 'js.php';?>
		<link rel="stylesheet" type="text/css" href="../css/common.css">
    <title><?php echo $lngPage['metaTtitle']; ?></title>
    <style>
        #display_news_minutes {
            max-width: 80px;
            width: 80px;
            min-width:80px;
        }
        .admin-table .xcrud-top-actions {
            padding: 0;
        }
        .admin-panel .adition-box {
            height:0;
        }
        .xcrud-nav:first-child {
            display: none;
        }
        div#news a[data-primary="2"] {
            position: absolute;
            left:570px;
            top: 15px;
        }
    </style>
</head>

<body>
 <div class="panel panel-default admin-panel">
  <div class="panel-heading">
  	<Button class="btn a-header-btn" id="logout"><i class="fa fa-sign-out"></i> <?php echo $lng['menu']['logout']; ?></Button>
    <ul class="breadcrumb">
        <li><a href="loggedon.php"><?php echo $lng['menu']['loggedon']; ?></a></li>
        <li class="active"><?php echo $lng['menu']['news']; ?></li>
    </ul>

  </div>
  <div class="panel-body">
		<div class="flex-property adition-box margin-btm">
			<?php include('freeAccount.php');?>
		</div>

      <h2>News and RSS</h2>

      <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#news">News</a></li>
          <li><a data-toggle="tab" href="#RSS">RSS</a></li>
      </ul>

      <div class="tab-content">
          <div id="news" class="tab-pane fade in active">
              <?php //echo $xcrud2->render('edit', $configid); ?>
              <?php echo $xcrud->render($webmasterid); ?>
          </div>
          <div id="RSS" class="tab-pane fade">
              <div>
                  <h2>RSS</h2>
                  A RSS should be composed of these elements:
                  <b>title</b>, <b>description</b>, <b>link</b>, <b>pubDate</b>
              </div>
              <?php echo $xcrud3->render($webmasterid); ?>
          </div>

      </div>

  </div>
</div>
 <script>
     function updateDisplay() {
         if ($('#isPopup').prop('checked')) {
             $('#startHour').text('').parent().parent().hide();
             $('#endHour').text('').parent().parent().hide();
             $('#display_news_minutes2').parent().parent().hide();
         } else {
             $('#startHour').parent().parent().show();
             $('#endHour').parent().parent().show();
             $('#display_news_minutes2').parent().parent().show();
         }
     }
     $(document).on('saved', function() {
         updateDisplay();
     })

         $(document).click('#isPopup', function(e) {
             e.stopPropagation();
             e.stopImmediatePropagation();
             updateDisplay();
         });


 </script>
<?php include 'footer.php';?>

</body>
</html>
