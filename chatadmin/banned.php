<?php
session_start();
include ('../Config.php');
include_once('protect.php');
checkCanEnter('adminpanelBanned');

$webmasterid = $_SESSION['admin'];
$webmaster = Webmaster::get($webmasterid);
include("xcrud/xcrud.php");
include ("lng/language.php");
!empty($_GET['lang']) ? $lng = new Language($_GET['lang']) : $lng = new Language();
$lng = $lng->getData();
$lngPage = $lng['banned'];

$table = 'chat_ban';
$xcrud = Xcrud::get_instance();
$xcrud->table($table);
$xcrud->pass_var('webmasterid', $webmasterid);
$xcrud->where('webmasterid =', $webmasterid);
$xcrud->columns('date, ip, userid, username, until');
$xcrud->fields('date, ip, userid, until, username, description, moderatorid');
$xcrud->field_tooltip('date','Date when user was banned');
$xcrud->field_tooltip('ip','Banned Ip address');
$xcrud->field_tooltip('until','Date until IP is banned');
$xcrud->validation_pattern('ip', '^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$');
$xcrud->hide_button('save_new');
$xcrud->hide_button('return');
$xcrud->unset_title();
$xcrud->unset_view();
$xcrud->unset_csv();
$xcrud->unset_limitlist();
$xcrud->unset_numbers();
$xcrud->unset_print();
$xcrud->unset_sortable();
$xcrud->change_type('message', 'textarea');


$table = 'chat_mute';
$xcrud2 = Xcrud::get_instance();
$xcrud2->table($table);
$xcrud2->pass_var('webmasterid', $webmasterid);
$xcrud2->where('webmasterid =', $webmasterid);
$xcrud2->columns('date, ip, until');
$xcrud2->fields('date, ip, userid, username, until');
$xcrud2->fields('date, ip, userid, until, username, description, moderatorid');
$xcrud2->field_tooltip('ip','Banned Ip address');
$xcrud2->field_tooltip('until','Date until IP is banned');
$xcrud2->validation_pattern('ip', '^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$');
$xcrud2->hide_button('save_new');
$xcrud2->hide_button('return');
$xcrud2->unset_title();
$xcrud2->unset_view();
$xcrud2->unset_csv();
$xcrud2->unset_limitlist();
$xcrud2->unset_numbers();
$xcrud2->unset_print();
$xcrud2->unset_sortable();
$xcrud2->change_type('message', 'textarea');

$table = 'chat_alerts_webmaster';
$xcrud3 = Xcrud::get_instance();
$xcrud3->table($table);
$xcrud3->pass_var('webmasterid', $webmasterid);
$xcrud3->where('webmasterid =', $webmasterid);
$xcrud3->columns('date, emailUserWhoReports, usernameAuthor, usernameProblem, description');
$xcrud3->fields('date, emailUserWhoReports, usernameAuthor, usernameProblem, description');
$xcrud3->field_tooltip('date','Date when user was banned');
$xcrud3->field_tooltip('ip','Banned Ip address');
$xcrud3->field_tooltip('until','Date until IP is banned');
$xcrud3->hide_button('save_new');
$xcrud3->hide_button('return');
$xcrud3->unset_title();
$xcrud3->unset_view();
$xcrud3->unset_csv();
$xcrud3->unset_limitlist();
$xcrud3->unset_numbers();
$xcrud3->unset_print();
$xcrud3->unset_sortable();
$xcrud3->change_type('description', 'textarea');

$table = 'chat_ban_range';
$xcrud4 = Xcrud::get_instance();
$xcrud4->table($table);
$xcrud4->columns('fromIP, toIP');
$xcrud4->fields('fromIP, toIP');

$xcrud4->pass_var('webmasterid', $webmasterid);
$xcrud4->where('webmasterid =', $webmasterid);
$xcrud4->hide_button('save_new');
$xcrud4->hide_button('return');
$xcrud4->unset_title();
$xcrud4->unset_view();
$xcrud4->unset_csv();
$xcrud4->unset_limitlist();
$xcrud4->unset_numbers();
$xcrud4->unset_print();
$xcrud4->unset_sortable();

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
 <div class="">
  <div class="panel-heading">
  	<Button class="btn a-header-btn" id="logout"><i class="fa fa-sign-out"></i> <?php echo $lng['menu']['logout']; ?></Button>
    <ul class="breadcrumb">
        <li><a href="loggedon.php"><?php echo $lng['menu']['loggedon']; ?></a></li>
        <li class="active"><?php echo $lng['menu']['banned']; ?></li>
    </ul>

  </div>
  <div class="panel-body">
        <div class="flex-property adition-box margin-btm">
            <?php if ($webmaster->free || $webmaster->expired): ?>
                <div class="alert alert-danger flex-property">
                   <?php echo $lngPage['danger1']; ?> <br> <a class="badge" href="/purchase" target="new"><?php echo $lngPage['danger2']; ?></a>
                </div>
            <?php endif ?>

            <div>
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#bannedips">Banned Ips</a></li>
                    <li><a data-toggle="tab" href="#mutedips">Muted Ips</a></li>
                    <li><a data-toggle="tab" href="#webmasteralerts">Webmaster Alerts</a></li>
                    <li><a data-toggle="tab" href="#ipranges">IP Ranges</a></li>
                </ul>
            </div>

        </div>
      <div class="tab-content">
          <div id="bannedips" class="tab-pane fade in active">
              <?php echo $xcrud->render($webmasterid); ?>
          </div>

          <div id="mutedips" class="tab-pane fade">
              <?php echo $xcrud2->render($webmasterid); ?>
          </div>

          <div id="webmasteralerts" class="tab-pane fade">
              <?php echo $xcrud3->render($webmasterid); ?>
          </div>

          <div id="ipranges" class="tab-pane fade">
              <?php echo $xcrud4->render($webmasterid); ?>
          </div>
      </div>



  </div>
</div>


<?php include ("footer.php");?>
</body>
</html>
