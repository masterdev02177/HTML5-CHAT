<?php
session_start();
include ("../Config.php");
include ("../classes/User.php");
include_once('protect.php');
checkCanEnter('adminpanelHistory');

$webmasterid = 1;
$webmaster = Webmaster::get($webmasterid);

include ("lng/language.php");
!empty($_GET['lang']) ? $lng = new Language($_GET['lang']) : $lng = new Language();
$lng = $lng->getData();
$votes = User::getBestVotes($webmasterid, 20, false);

include("../chatadmin/xcrud/xcrud.php");
$xcrud = Xcrud::get_instance();
$table = 'chat_contest';
$xcrud->table($table);
$xcrud->pass_var('webmasterid', $webmasterid);
$xcrud->where('webmasterid =', $webmasterid);

$xcrud->columns('date, username1, username2');
$xcrud->fields('date, username1, username2');
$xcrud->order_by ('id', 'desc');


//$xcrud->label(array('quitUrl' => 'Url where quit', 'showSmileys'=>'Show smileys'));

//view, edit, remove, duplicate, add, csv, print, save_new, save_edit, save_return, return.
$xcrud->hide_button('save_new');
$xcrud->hide_button('return');
$xcrud->unset_add();
//$xcrud->hide_button('save_return');
//$xcrud->unset_list();
//$xcrud->unset_csv();
$xcrud->unset_title();
$xcrud->unset_view();
$xcrud->unset_limitlist();
$xcrud->unset_numbers();
$xcrud->unset_print();
$xcrud->unset_sortable();
//$xcrud->relation('userid','chat_users','id', 'username', "webmasterid=$webmasterid");
//$xcrud->relation('user2id','chat_users','id', 'username', "webmasterid=$webmasterid");

$xcrud->label(array(
    'username1'=>'Username who voted',
    'username2'=>'Voted for',
));



//	$xcrud->emails_label(' email');
//$xcrud->show_primary_ai_column(true);
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name=viewport content="width=device-width, initial-scale=1">
    <?php include 'css.php';?>
    <?php include 'js.php';?>
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <title>Chat contest</title>
</head>

<body>
 <div class="panel panel-default admin-panel">
  <div class="panel-heading">
  	<button class="btn a-header-btn" id="logout"><i class="fa fa-sign-out"></i> <?php echo $lng['menu']['logout']; ?></button>
    <ul class="breadcrumb">
        <li><a href="loggedon.php"><?php echo $lng['menu']['loggedon']; ?></a></li>
        <li class="active">Chat contest</li>
    </ul>
  </div>


  <div class="panel-body">
		<div class="flex-property adition-box margin-btm">

		</div>

      <div class="admin-table rooms-table">
          <p>
              The contest alows to chatters to vote for other chatter. Each chatter can only vote <b>once</b>.
              This option is useful if you want to organize a contest for instance. To allow an user to vote,
              <a href="/chatadmin/roles.php">go to roles, advanced. Guest users cannot vote.</a>
          </p>
        <table class="xcrud-list table table-striped table-hover table-bordered">
            <thead class="xcrud-th">
                <th>Username</th>
                <th>points</th>
            </thead>
            <?php foreach($votes as $vote):?>
                <tr>
                    <td><?=$vote->username?></td>
                    <td><?=$vote->total?></td>

                </tr>
            <?php endforeach?>

        </table>
      </div>

      <h4>Details</h4>

		<div class="admin-table rooms-table">
			<?php echo $xcrud->render($webmasterid); ?>
		</div>
        <div>
            <button id="deleteAllContestBtn" style="margin: 20px;" class="btn btn-danger pull-right">Delete All Contest Data</button>
        </div>
  </div>
</div>
<?php include ("footer.php");?>
 <script>
     $('#deleteAllContestBtn').click(function() {
         bootbox.confirm('Are you sure you want to delete ALL contest ?', function(res) {
             if (!res) {
                 return;
             }
             $.post('ajax.php', {a: 'deleteAllContest'}, function (res) {
                 window.location = window.location;
             });

         })
     })
 </script>
</body>
</html>
