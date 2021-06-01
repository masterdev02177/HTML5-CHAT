<?php
session_start();
include '../Config.php';
include '../classes/DB.php';
include '../classes/Services.php';
include_once 'protect.php';
checkCanEnter('adminpanelSounds');

ini_set('display_errors', 0);
$webmasterid = $_SESSION['admin'];
$webmaster = Webmaster::get($webmasterid);
$configid = DB::getOne('chat_config', "where webmasterid=$webmasterid")->id;
include 'lng/language.php';
!empty($_GET['lang']) ? $lng = new Language($_GET['lang']) : $lng = new Language();
$lng = $lng->getData();
$models = DB::getAll('chat_template'," WHERE active=1");
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name=viewport content="width=device-width, initial-scale=1">
    <?php include 'css.php';?>
    <?php include 'js.php';?>
		<link rel="stylesheet" type="text/css" href="../css/common.css">
    <title>Template</title>

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
        <li class="active">Template</li>
    </ul>

  </div>
  <div class="panel-body">
		<div class="flex-property adition-box margin-btm">
			<?php include('freeAccount.php');?>
		</div>
      <div class="admin-table rooms-table">
          <div>
              Templates allow you to quickly set up a chat configuration and pick up the way a chat should work.
          </div>
          <br>
          <div class="alert alert-danger">Warning: Setting a template will rewrite ALL your <a href="config.php">Config</a> settings.</div>
          <div class="col-md-4">
              <select class="form-control" name="templateModelSelect" id="templateModelSelect">
                  <?php foreach ($models as $model): ?>
                      <option value="<?php echo $model->id;?>"><?php echo $model->description;?></option>
                  <?php endforeach;?>
              </select>
          </div>


          <button id="saveModelBtn" class="btn btn-primary">Set this template for my chat.</button>
      </div>


  </div>
</div>
 <script>
    $('#saveModelBtn').click(function() {
        bootbox.confirm("Do you want to use it as template for your chat ?<br>All your chat config will be overwrite", function(res) {
            if (!res) {
                return;
            }
            var templateid = $('#templateModelSelect').val();
            $.post('ajax.php', {a:'setTemplate', templateid:templateid}, function(res) {
                res = JSON.parse(res);
                console.log(res);
            });
        })

    });

 </script>
<?php include 'footer.php';?>

</body>
</html>
