<?php
session_start();
include '../Config.php';
include_once 'protect.php';
include 'lng/language.php';
checkCanEnter('adminpanelCalendar');

$webmasterid = 1;
$webmaster = Webmaster::get($webmasterid);
$config = DB::getOne('chat_config', "where webmasterid=$webmasterid");

!empty($_GET['lang']) ? $lng = new Language($_GET['lang']) : $lng = new Language();
$lng = $lng->getData();
$lngPage = $lng['calendar'];


?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name=viewport content="width=device-width, initial-scale=1">
    <?php include('css.php');?>
    <?php include('js.php');?>
		<link rel="stylesheet" type="text/css" href="../css/common.css">
    <title>Translate</title>

    <style>

        .panel.panel-default .xcrud-details-table td:first-child {
            width: 121px;
        }
        li {
            padding: 10px;
        }

    </style>
</head>

<body>
 <div class="panel panel-default admin-panel">
  <div class="panel-heading">
  	<Button class="btn a-header-btn" id="logout"><i class="fa fa-sign-out"></i> <?php echo $lng['menu']['logout']; ?></Button>
    <ul class="breadcrumb">
        <li><a href="loggedon.php"><?php echo $lng['menu']['loggedon']; ?></a></li>
        <li class="active">Translate</li>
    </ul>

  </div>
  <div class="panel-body">
		<div class="flex-property adition-box margin-btm">

		</div>
		<div class="admin-table rooms-table">
            <br>
            <br>
            <h3>You can use your own translation using our file reference</h3>
            <h3>How to proceed ? Use these 4 steps:</h3>
            <ol>
                <li>
                    Download this <a href="translateJSONDownload.php" target="_blank"><b>translate.json</b> file reference we provide (JSON format)</a>
                </li>
                <li>
                    Edit this <b>translate.json</b> file with any text editor. (of course you need to translate the SECOND part of each line)
                </li>
                <li>
                    <p>
                        Upload the translate.json file to your server, so it is accessible from outside. <br>
                        ex: https://yoursite.com/translate.json (you must able to download that file and make sure JSON is valid !)
                    </p>
                </li>

                <li>
                        Enter the URL of your JSON file and press Update button
                        <div class="col-md-11">
                            <input type="text" value="<?=$config->urlJson?>" name="translateUrl" id="translateUrl" class="form-control" value="" title="" required="required" placeholder="enter the url of your own translate.json file. Should start with https:// (leave blank to use our translation file)">
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-warning form-control" id="checkUplBtn">Update</button>
                        </div>

                </li>
            </ol>
            <p style="text-align: right">
                <i>
                    * if you have JSON format problems, you can use that <a href="https://jsonlint.com/" target="_blank">Online tool</a> to validate your JSON.
                </i>
            </p>


		</div>
  </div>
</div>
 <script>
    $('#checkUplBtn').click(function() {
        var url = $('#translateUrl').val();
        $.post('ajax.php', {a: 'setTranslate', url:url}, function (res) {
            bootbox.alert(res);
        });
    })

 </script>
<?php include 'footer.php';?>

</body>
</html>
