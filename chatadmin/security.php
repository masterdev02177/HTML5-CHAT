<?php
session_start();
include '../Config.php';
include_once 'protect.php';
checkCanEnter('adminpanelSecurity');

$webmasterid = $_SESSION['admin'];
$configid = DB::getOne('chat_config', "where webmasterid=$webmasterid")->id;
$webmaster = Webmaster::get($webmasterid);

include 'xcrud/xcrud.php';
include 'lng/language.php';
!empty($_GET['lang']) ? $lng = new Language($_GET['lang']) : $lng = new Language();
$lng = $lng->getData();
$lngPage = $lng['config'];

$xcrud = Xcrud::get_instance();
$table = 'chat_config';
$xcrud->table($table);
$xcrud->pass_var('webmasterid', $webmasterid);
$xcrud->where('webmasterid =', $webmasterid);

$xcrud->fields('disableTOR, disableVPN, reportUserToAdmin, urlProtection, forbidCountries, warnUsersWhenAdminSpiesWebcam, adultRoomAction,
maxLenthMessage, maxLinesMessages, minorCanContactAdult, forbiddenWordsApplyToUsername', false);

$xcrud->label(array(
    'disableTOR' => 'Disable TOR users',
    'disableVPN' => 'Disable VPN users (paid version only)',
    'reportUserToAdmin'=>'Report User to admin',
    'forbidCountries'=>'Forbid countries',
    'warnUsersWhenAdminSpiesWebcam'=>'Warn when admin spies an user',
    'adultRoomAction'=>'Adult room action',
    'maxLenthMessage'=>'Maximum Length of a message',
    'maxLinesMessages'=>'Maxium number of lines in message (RETURN char)',
    'minorCanContactAdult'=>'Can a minor contact an adult',
    'urlProtection'  => $lngPage['urlProtection' ],
    'forbiddenWordsApplyToUsername'=>'<a href="forbiddenWords.php">Forbidden words</a> also apply to usernames'
    )
);

if ($webmaster->free || $webmaster->expired) {
    $xcrud->disabled('disableTOR, disableVPN');
}

//$xcrud->validation_pattern('forbidCountries', '[a-zA-Z]{2}');

$xcrud->unset_add();
$xcrud->unset_list();
$xcrud->unset_title();
$xcrud->field_tooltip('reportUserToAdmin', "User can send an email to the admin to report another user's behaviour on chat.");
$xcrud->field_tooltip('urlProtection', $lngPage['urlProtectionL']);
$xcrud->field_tooltip('forbidCountries', 'Enter countries 2 letters code to be forbidden (separated by ,) Ex: fr, es, ru');
$xcrud->field_tooltip('adultRoomAction', "What to do if user enters an adult room.<br>If you want hide for minors, make sure you pass the parameter <a href='#'>birthyear</a>, which is the age of the user into the JWT encoding method");
$xcrud->field_tooltip('maxLenthMessage', "Maximum number of characters an user can send per message (protect against flooding)");
$xcrud->field_tooltip('maxLinesMessages', "Maximum number of RETURN character in a chat message (protect against flooding)");
$xcrud->field_tooltip('minorCanContactAdult', "Can minor contact an adult ? (make sure you pass the parameter <a href='#'>birthyear</a>, which is the age of the user into the JWT encoding method) ");
$xcrud->field_tooltip('forbiddenWordsApplyToUsername', "Users cannot chose usernames that are in the <a href='forbiddenWords.php'>forbidden words</a>   filters");


//	$xcrud->emails_label(' email');
//$xcrud->show_primary_ai_column(true);
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name=viewport content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/admin.css?cache=<?=time()?>">
    <link rel="stylesheet" type="text/css" href="/css/colorPicker.css">
    <link rel="stylesheet" type="text/css" href="../css/common.css">



    <style>
        td {
            font-size: 0.70em;
        }

    </style>
    <title><?php echo $lngPage['metaTtitle']; ?></title>
</head>

<body>
<div class="panel panel-default admin-panel">
    <div class="panel-heading">
        <Button class="btn a-header-btn" id="logout"><i class="fa fa-sign-out"></i> <?php echo $lng['menu']['logout']; ?></Button>
        <ul class="breadcrumb">
            <li><a href="loggedon.php">Home</a></li>
            <li class="active">Security</li>
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
<?php include ('footer.php');?>
<script src="/js/jquery.colorPicker.min.js"></script>
<script>
    $(document).on('saved', function() {
        window.location = window.location;
    })

</script>

</body>
</html>
