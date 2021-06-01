<?php
session_start();
include '../Config.php';
include_once 'protect.php';
include_once '../classes/Room.php';

checkCanEnter('adminpanelNews');
$table = 'chat_lang';
$webmasterid = 1;
$webmaster = Webmaster::get($webmasterid);
$translation = DB::getOne('chat_lang', "where webmasterid = $webmasterid",false);
if (!$translation) {
    if (!$webmaster->free && !$webmaster->expired) {
        $translationid = DB::insert($table,array('webmasterid'=>$webmasterid));
    }
} else {
    $translationid = $translation->id;
}
include 'xcrud/xcrud.php';
include 'lng/language.php';
!empty($_GET['lang']) ? $lng = new Language($_GET['lang']) : $lng = new Language();
$lng = $lng->getData();
$lngPage = $lng['news'];

$xcrud = Xcrud::get_instance();

$xcrud->table($table);
$xcrud->pass_var('webmasterid', 54);
$xcrud->pass_var('webmasterid', $webmasterid);

//view, edit, remove, duplicate, add, csv, print, save_new, save_edit, save_return, return.
//$xcrud->hide_button('save_return');
//$xcrud->hide_button('return');
//$xcrud->unset_save();
$xcrud->unset_add();
$xcrud->unset_list();
$xcrud->unset_title();
$xcrud->change_type('webmasterid','hidden');
$xcrud->before_edit('before_details_callback');

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
    <title>Translate</title>
    <style>
        a[data-task="save"] {
            width:200px;
            text-align: center;
        }
        #langageSelect {
            font-size: 1.5em;
            position: relative;
            top: 3px;
        }
    </style>
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
            <?php include'freeAccount.php';?>
        </div>

        <?php if (!$webmaster->free && !$webmaster->expired):?>
        <div class="admin-table rooms-table">
            <div class="pull-right" style="margin: 0px;" >
                <label for="langage">Reset with default langage</label>
                <select name="langageSelect" id="langageSelect" >
                    <option value="en">English</option>
                    <option value="fr">French</option>
                    <option value="es">Spanish</option>
                    <option value="de">German</option>
                    <option value="it">Italian</option>
                    <option value="br">Portuguese</option>
                    <option value="nl">Dutch</option>
                    <option value="se">Swedish</option>
                    <option value="ru">Russian</option>
                    <option value="irn">Iranian</option>
                    <option value="al">Albanian</option>
                </select>
                <button id="changeLangageBtn" class="btn">Change default langage</button>
            </div>


           <div style="clear: both">
               <p>
                   Make sure you don't modify the <b>%s</b>: it allows dynamic variable replacement in your translations.
               </p>
               <?php echo $xcrud->render('edit', $translationid); ?>
           </div>
        </div>
        <?php else: ?>
            <div class="alert alert-danger" style="margin-top: 100px">
                <h4>
                    This feature is only available for <a href="/register" target="_blank">registered users.</a>
                </h4>
            </div>
        <?php endif?>

    </div>
</div>
<?php include 'footer.php';?>
<script>
$('#changeLangageBtn').click(function() {
    var lang = $('#langageSelect').val();
    bootbox.confirm("Are you sure you want to reset the translations ? <br>All your modifications will be replaced", function(res) {
        $.post('ajax.php', {a: 'setTranslation', lang:lang}, function (res) {
            //location.reload();
        });
    })
})
</script>
</body>
</html>
