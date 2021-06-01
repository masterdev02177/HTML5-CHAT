<?php
session_start();
include ("../Config.php");
include ("../classes/Tips.php");
include_once('protect.php');
checkCanEnter('adminpanelRoles');
if (!isset($_REQUEST['userid'])) {
    header('Location:conferenceReports.php');
}
$userid = $_REQUEST['userid'];
$webmasterid = 1;
$webmaster = Webmaster::get($webmasterid);
$userid = $_REQUEST['userid'];
$credits = Tips::getCredits($webmasterid, $userid, false);
if (!$credits) {
    header('Location:conferenceReports.php');
}

$config = DB::getOne('chat_config', "where webmasterid=$webmasterid");
$webmaster = Webmaster::get($webmasterid);
$chatType = $config->chatType;

$beginMonth = date('');
$endMonth = date('');

$startDate = (isset($_REQUEST['startDate']))?$_REQUEST['startDate']:$beginMonth;
$endDate = (isset($_REQUEST['endDate']))?$_REQUEST['endDate']:$endMonth;




?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name=viewport content="width=device-width, initial-scale=1">
    <?php include('css.php');?>
    <?php include('js.php');?>

    <link rel="stylesheet" type="text/css" href="../css/common.css">

    <title>Roles</title>
</head>

<body>
<div class="panel panel-default admin-panel">
    <div class="panel-heading">
        <Button class="btn a-header-btn" id="logout"><i class="fa fa-sign-out"></i> <?php echo $lng['menu']['logout']; ?></Button>
        <ul class="breadcrumb">
            <li><a href="loggedon.php"><?php echo $lng['menu']['loggedon']; ?></a></li>
            <li class="active">Detail Credits</li>
        </ul>
    </div>
    <div class="panel-body">
        <div class="flex-property adition-box margin-btm">
            <?php include('freeAccount.php');?>
        </div>
        <div class="admin-table rooms-table">

        </div>
    </div>
</div>

<?php include ('footer.php');?>

</body>

</html>
