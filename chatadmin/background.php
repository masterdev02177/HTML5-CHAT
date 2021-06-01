<?php
session_start();
include '../Config.php';
include_once 'protect.php';
checkCanEnter('adminpanelUsers');

$webmasterid = 1;
$webmaster = Webmaster::get($webmasterid);

$config = DB::getOne('chat_config', "where webmasterid=$webmasterid");
$configid = $config->id;
$chatType = $config->chatType;


include 'xcrud/xcrud.php';
include 'lng/language.php';
!empty($_GET['lang']) ? $lng = new Language($_GET['lang']) : $lng = new Language();
$lng = $lng->getData();
$lngPage = $lng['users'];

// $xcrud = Xcrud::get_instance();
// $table = 'chat_config';
// $xcrud->table($table);
// $xcrud->pass_var('webmasterid', $webmasterid);
// $xcrud->where('webmasterid =', $webmasterid);

// $xcrud->columns('backgroundImage');

// if ($chatType=='conference') {
//     $xcrud->fields('username, email, password, status, confirmed, role, bannedUntil, IP, genderid, image, conference_bigPhoto');
//     $xcrud->change_type('conference_bigPhoto', 'image', '',
//         array('width' => 640, 'height' => 480, 'crop' => true, 'path' => UPLOAD_THUMBS)
//     );

// } else {
//     $xcrud->fields('username, email, password, status, confirmed, role, bannedUntil, IP, genderid, image');
// }
/*$xcrud->change_type('image', 'image', '',
    array('width' => 64, 'height' => 64, 'crop' => true, 'folder' => '/upload/thumb/')
);*/



// $xcrud->relation('genderid','chat_gender','id', 'gender', "webmasterid=$webmasterid");

// $xcrud->label(array('genderid' => 'Gender', 'bannedUntil'=>'Banned until', 'image'=>'Avatar image', 'conference_bigPhoto'=>'Big photo for conference'));

// $xcrud->validation_pattern('email', 'email');
// $xcrud->validation_required('username');

// $xcrud->change_type('password', 'password');
// $xcrud->change_type('welcome', 'textarea');

// $xcrud->field_tooltip('username', $lngPage['username']);
// $xcrud->field_tooltip('image', $lngPage['image']);
// $xcrud->field_tooltip('bannedUntil', $lngPage['bannedUntil']);
// $xcrud->field_tooltip('password', $lngPage['password']);
// $xcrud->field_tooltip('IP', $lngPage['IP']);
// $xcrud->field_tooltip('role', $lngPage['role']);
// $xcrud->field_tooltip('invisible', $lngPage['invisible']);
// $xcrud->field_tooltip('canOpenAnyWebcam', $lngPage['canOpenAnyWebcam']);
// $xcrud->field_tooltip('conference_bigPhoto', 'Big avatar for conferencer. will be displayed when his webcam if off and also in the listing of online conferences');
// $xcrud->field_tooltip('confirmed', 'User confirmed this account by email');

// $xcrud->disabled_on_create(array('bannedUntil', 'IP','status'));

//$xcrud->label(array('quitUrl' => 'Url where quit', 'showSmileys'=>'Show smileys'));

//view, edit, remove, duplicate, add, csv, print, save_new, save_edit, save_return, return.
// $xcrud->hide_button('save_new');
//$xcrud->hide_button('save_return');
// $xcrud->hide_button('return');
//$xcrud->unset_add();
//$xcrud->unset_list();
//$xcrud->unset_title();
// $xcrud->unset_view();
// $xcrud->unset_csv();
// $xcrud->unset_limitlist();
// $xcrud->unset_numbers();
// $xcrud->unset_print();
// $xcrud->unset_sortable();
// $xcrud->table_name('Users (available ONLY if <a href="config.php">guest</a> users are disabled)', 'Users (available ONLY if guest users are disabled)');
// if(isset($_POST["uploadImageFile"])) {
// print_r($_POST['uploadImageFile']);exit;
// }
//	$xcrud->emails_label(' email');
//$xcrud->show_primary_ai_column(true);


?>
<!DOCTYPE HTML>
<html>

<head>
    <meta name=viewport content="width=device-width, initial-scale=1">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,600,700,800" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Kanit:200,300,400,500,600" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/common.css">

    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/sprintf.min.js" type="text/javascript"></script>
    <script src="../js/bootbox.min.js" type="text/javascript"></script>
    <title><?php echo "Backgrounds"; ?></title>
    <style type="text/css">
        /*
 * contextMenu.js v 1.4.0
 * Author: Sudhanshu Yadav
 * s-yadav.github.com
 * Copyright (c) 2013 Sudhanshu Yadav.
 * Dual licensed under the MIT and GPL licenses
**/

        .iw-contextMenu {
            box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.10) !important;
            border: 1px solid #c8c7cc !important;
            border-radius: 11px !important;
            display: none;
            z-index: 1000000132;
            max-width: 300px !important;
            width: auto !important;
        }

        .dark-mode .iw-contextMenu,
        .TnITTtw-dark-mode.iw-contextMenu,
        .TnITTtw-dark-mode .iw-contextMenu {
            border-color: #747473 !important;
        }

        .iw-cm-menu {
            background: #fff !important;
            color: #000 !important;
            margin: 0px !important;
            padding: 0px !important;
            overflow: visible !important;
        }

        .dark-mode .iw-cm-menu,
        .TnITTtw-dark-mode.iw-cm-menu,
        .TnITTtw-dark-mode .iw-cm-menu {
            background: #525251 !important;
            color: #FFF !important;
        }


        .iw-cm-menu li {
            font-family: -apple-system, BlinkMacSystemFont, "Helvetica Neue", Helvetica, Arial, Ubuntu, sans-serif !important;
            list-style: none !important;
            padding: 10px !important;
            padding-right: 20px !important;
            border-bottom: 1px solid #c8c7cc !important;
            font-weight: 400 !important;
            cursor: pointer !important;
            position: relative !important;
            font-size: 14px !important;
            margin: 0 !important;
            line-height: inherit !important;
            border-radius: 0 !important;
            display: block !important;
        }

        .dark-mode .iw-cm-menu li,
        .TnITTtw-dark-mode .iw-cm-menu li {
            border-bottom-color: #747473 !important;
        }

        .iw-cm-menu li:first-child {
            border-top-left-radius: 11px !important;
            border-top-right-radius: 11px !important;
        }

        .iw-cm-menu li:last-child {
            border-bottom-left-radius: 11px !important;
            border-bottom-right-radius: 11px !important;
            border-bottom: none !important;
        }

        .iw-mOverlay {
            position: absolute !important;
            width: 100% !important;
            height: 100% !important;
            top: 0px !important;
            left: 0px !important;
            background: #FFF !important;
            opacity: .5 !important;
        }

        .iw-contextMenu li.iw-mDisable {
            opacity: 0.3 !important;
            cursor: default !important;
        }

        .iw-mSelected {
            background-color: #F6F6F6 !important;
        }

        .dark-mode .iw-mSelected,
        .TnITTtw-dark-mode .iw-mSelected {
            background-color: #676766 !important;
        }

        .iw-cm-arrow-right {
            width: 0 !important;
            height: 0 !important;
            border-top: 5px solid transparent !important;
            border-bottom: 5px solid transparent !important;
            border-left: 5px solid #000 !important;
            position: absolute !important;
            right: 5px !important;
            top: 50% !important;
            margin-top: -5px !important;
        }

        .dark-mode .iw-cm-arrow-right,
        .TnITTtw-dark-mode .iw-cm-arrow-right {
            border-left-color: #FFF !important;
        }


        #fileupload {
            color: #fff;
            background-color: #5cb85c;
            border-color: #4cae4c;

            display: inline-block;
            padding: 6px 12px;
            margin-bottom: 0;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.42857143;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            -ms-touch-action: manipulation;
            touch-action: manipulation;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-image: none;
            border: 1px solid transparent;
            border-radius: 4px;

        }


        /*context menu css end */
    </style>
    <style type="text/css">
        @-webkit-keyframes load4 {

            0%,
            100% {
                box-shadow: 0 -3em 0 0.2em, 2em -2em 0 0em, 3em 0 0 -1em, 2em 2em 0 -1em, 0 3em 0 -1em, -2em 2em 0 -1em, -3em 0 0 -1em, -2em -2em 0 0;
            }

            12.5% {
                box-shadow: 0 -3em 0 0, 2em -2em 0 0.2em, 3em 0 0 0, 2em 2em 0 -1em, 0 3em 0 -1em, -2em 2em 0 -1em, -3em 0 0 -1em, -2em -2em 0 -1em;
            }

            25% {
                box-shadow: 0 -3em 0 -0.5em, 2em -2em 0 0, 3em 0 0 0.2em, 2em 2em 0 0, 0 3em 0 -1em, -2em 2em 0 -1em, -3em 0 0 -1em, -2em -2em 0 -1em;
            }

            37.5% {
                box-shadow: 0 -3em 0 -1em, 2em -2em 0 -1em, 3em 0em 0 0, 2em 2em 0 0.2em, 0 3em 0 0em, -2em 2em 0 -1em, -3em 0em 0 -1em, -2em -2em 0 -1em;
            }

            50% {
                box-shadow: 0 -3em 0 -1em, 2em -2em 0 -1em, 3em 0 0 -1em, 2em 2em 0 0em, 0 3em 0 0.2em, -2em 2em 0 0, -3em 0em 0 -1em, -2em -2em 0 -1em;
            }

            62.5% {
                box-shadow: 0 -3em 0 -1em, 2em -2em 0 -1em, 3em 0 0 -1em, 2em 2em 0 -1em, 0 3em 0 0, -2em 2em 0 0.2em, -3em 0 0 0, -2em -2em 0 -1em;
            }

            75% {
                box-shadow: 0em -3em 0 -1em, 2em -2em 0 -1em, 3em 0em 0 -1em, 2em 2em 0 -1em, 0 3em 0 -1em, -2em 2em 0 0, -3em 0em 0 0.2em, -2em -2em 0 0;
            }

            87.5% {
                box-shadow: 0em -3em 0 0, 2em -2em 0 -1em, 3em 0 0 -1em, 2em 2em 0 -1em, 0 3em 0 -1em, -2em 2em 0 0, -3em 0em 0 0, -2em -2em 0 0.2em;
            }
        }

        @keyframes load4 {

            0%,
            100% {
                box-shadow: 0 -3em 0 0.2em, 2em -2em 0 0em, 3em 0 0 -1em, 2em 2em 0 -1em, 0 3em 0 -1em, -2em 2em 0 -1em, -3em 0 0 -1em, -2em -2em 0 0;
            }

            12.5% {
                box-shadow: 0 -3em 0 0, 2em -2em 0 0.2em, 3em 0 0 0, 2em 2em 0 -1em, 0 3em 0 -1em, -2em 2em 0 -1em, -3em 0 0 -1em, -2em -2em 0 -1em;
            }

            25% {
                box-shadow: 0 -3em 0 -0.5em, 2em -2em 0 0, 3em 0 0 0.2em, 2em 2em 0 0, 0 3em 0 -1em, -2em 2em 0 -1em, -3em 0 0 -1em, -2em -2em 0 -1em;
            }

            37.5% {
                box-shadow: 0 -3em 0 -1em, 2em -2em 0 -1em, 3em 0em 0 0, 2em 2em 0 0.2em, 0 3em 0 0em, -2em 2em 0 -1em, -3em 0em 0 -1em, -2em -2em 0 -1em;
            }

            50% {
                box-shadow: 0 -3em 0 -1em, 2em -2em 0 -1em, 3em 0 0 -1em, 2em 2em 0 0em, 0 3em 0 0.2em, -2em 2em 0 0, -3em 0em 0 -1em, -2em -2em 0 -1em;
            }

            62.5% {
                box-shadow: 0 -3em 0 -1em, 2em -2em 0 -1em, 3em 0 0 -1em, 2em 2em 0 -1em, 0 3em 0 0, -2em 2em 0 0.2em, -3em 0 0 0, -2em -2em 0 -1em;
            }

            75% {
                box-shadow: 0em -3em 0 -1em, 2em -2em 0 -1em, 3em 0em 0 -1em, 2em 2em 0 -1em, 0 3em 0 -1em, -2em 2em 0 0, -3em 0em 0 0.2em, -2em -2em 0 0;
            }

            87.5% {
                box-shadow: 0em -3em 0 0, 2em -2em 0 -1em, 3em 0 0 -1em, 2em 2em 0 -1em, 0 3em 0 -1em, -2em 2em 0 0, -3em 0em 0 0, -2em -2em 0 0.2em;
            }
        }
    </style>
</head>

<body>
    <div class="panel panel-default admin-panel">
        <div class="panel-heading">
            <Button class="btn a-header-btn" id="logout"><i class="fa fa-sign-out"></i> <?php echo $lng['menu']['logout']; ?></Button>
            <ul class="breadcrumb">
                <li><a href="loggedon.php"><?php echo $lng['menu']['loggedon']; ?></a></li>
                <li class="active"><?php echo "BACKGROUNDS" ?></li>
            </ul>
        </div>
        <div class="panel-body">
            <div class="flex-property adition-box margin-btm">
                <?php include('freeAccount.php'); ?>
            </div>
            <div class="admin-table rooms-table">
                <link href="https://html5-chat.com/chatadmin/xcrud/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
                <link href="https://html5-chat.com/chatadmin/xcrud/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css">
                <link href="https://html5-chat.com/chatadmin/xcrud/plugins/jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css">
                <link href="https://html5-chat.com/chatadmin/xcrud/plugins/timepicker/jquery-ui-timepicker-addon.css" rel="stylesheet" type="text/css">
                <link href="https://html5-chat.com/chatadmin/xcrud/themes/bootstrap/xcrud.css" rel="stylesheet" type="text/css">
                <div class="xcrud">
                    <div class="xcrud-container">
                        <div class="xcrud-ajax">
                            <input type="hidden" class="xcrud-data" name="key" value="925d4986fcfc7173ba57d4c8f1719d449d1038f5"><input type="hidden" class="xcrud-data" name="orderby" value=""><input type="hidden" class="xcrud-data" name="order" value="asc"><input type="hidden" class="xcrud-data" name="start" value="0"><input type="hidden" class="xcrud-data" name="limit" value="10"><input type="hidden" class="xcrud-data" name="instance" value="3757fb5f335c2c0e103df1db28c392f215a1eaff"><input type="hidden" class="xcrud-data" name="task" value="list">
                            <div class="xcrud-top-actions">
                                <div class="btn-group pull-right">
                                </div>
                                <form action="upload.php" method="post" id="image-form" enctype="multipart/form-data">
                                    <label id='fileupload' for="uploadImageFile"><i class="glyphicon glyphicon-plus-sign"></i> Add</label>
                                    <input type="file" name="uploadImageFile" id="uploadImageFile" style='display:none;'>
                                </form>
                                <div class="clearfix"></div>
                            </div>
                            <div class="xcrud-list-container">
                                <table class="xcrud-list table table-striped table-hover table-bordered">
                                    <thead>
                                        <tr class="xcrud-th">
                                            <th class="xcrud-column">Image</th>
                                            <th class="xcrud-actions">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $backgroundImages = Background::getAll();
                                        foreach ($backgroundImages as $backgroundImage) : ?>
                                            <tr class="xcrud-row xcrud-row-0">
                                                <td>
                                                    <img alt="" src="<?= '../' . $backgroundImage['thumb'] ?>" style="max-height: 55px;">
                                                </td>
                                                <td class="xcrud-actions xcrud-fix">
                                                    <span class="btn-group">
                                                        <button class="btn btn-danger btn-sm removeimage" title="Remove" data-src='<?= '../' . $backgroundImage['image'] ?>' data-thumb-src='<?= '../' . $backgroundImage['thumb'] ?>'>
                                                            <i class="glyphicon glyphicon-remove"></i>
                                                        </button>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                    <tfoot>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="xcrud-overlay" style="display: none;"></div>
                    </div>
                </div>
                <script src="https://html5-chat.com/chatadmin/xcrud/plugins/jquery.min.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/xcrud/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/xcrud/plugins/jcrop/jquery.Jcrop.min.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/xcrud/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/xcrud/plugins/timepicker/jquery-ui-timepicker-addon.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/ckeditor.js" type="text/javascript"></script>
                <script type="text/javascript" src="https://html5-chat.com/chatadmin/editors/ckeditor/core/loader.js"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/event.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/editor_basic.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/env.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/ckeditor_basic.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/log.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/dom.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/tools.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/dtd.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/dom/event.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/dom/domobject.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/dom/node.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/dom/window.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/dom/document.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/dom/nodelist.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/dom/element.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/dom/documentfragment.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/dom/walker.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/dom/range.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/dom/iterator.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/command.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/ckeditor_base.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/config.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/filter.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/focusmanager.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/keystrokehandler.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/lang.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/scriptloader.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/resourcemanager.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/plugins.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/ui.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/editor.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/htmlparser.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/htmlparser/basicwriter.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/htmlparser/node.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/htmlparser/comment.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/htmlparser/text.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/htmlparser/cdata.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/htmlparser/fragment.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/htmlparser/filter.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/htmldataprocessor.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/htmlparser/element.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/template.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/ckeditor.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/creators/inline.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/creators/themedui.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/editable.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/selection.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/style.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/dom/comment.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/dom/elementpath.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/dom/text.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/dom/rangelist.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/skin.js" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/editors/ckeditor/core/_bootstrap.js" type="text/javascript"></script>
                <script src="//maps.google.com/maps/api/js?sensor=false&amp;language=en" type="text/javascript"></script>
                <script src="https://html5-chat.com/chatadmin/xcrud/plugins/xcrud.js" type="text/javascript"></script>
                <script type="text/javascript">
                    var xcrud_config = {
                        "url": "https:\/\/html5-chat.com\/chatadmin\/xcrud\/xcrud_ajax.php",
                        "editor_url": "https:\/\/html5-chat.com\/chatadmin\/editors\/ckeditor\/ckeditor.js",
                        "editor_init_url": false,
                        "force_editor": false,
                        "date_first_day": 1,
                        "date_format": "dd.mm.yy",
                        "time_format": "HH:mm:ss",
                        "lang": {
                            "add": "Add",
                            "edit": "Edit",
                            "view": "View",
                            "remove": "Remove",
                            "duplicate": "Duplicate",
                            "print": "Print",
                            "export_csv": "Export into CSV",
                            "search": "Search",
                            "go": "Go",
                            "reset": "Reset",
                            "save": "Save",
                            "save_return": "Save & Go back",
                            "save_new": "Save & Add New",
                            "save_edit": "Save",
                            "return": "Return",
                            "modal_dismiss": "Close",
                            "add_image": "Add image",
                            "add_file": "Add file",
                            "exec_time": "Execution time:",
                            "memory_usage": "Memory usage:",
                            "bool_on": "Yes",
                            "bool_off": "No",
                            "no_file": "no file",
                            "no_image": "no image",
                            "null_option": "- none -",
                            "total_entries": "Total entries:",
                            "table_empty": "Entries not found.",
                            "all": "All",
                            "deleting_confirm": "Do you really want remove this entry?",
                            "undefined_error": "It looks like something went wrong...",
                            "validation_error": "Some fields are likely to contain errors. Fix errors and try again.",
                            "image_type_error": "This image type is not supported.",
                            "unique_error": "Some fields are not unique.",
                            "your_position": "Your position",
                            "search_here": "Search here...",
                            "all_fields": "All fields",
                            "choose_range": "- choose range -",
                            "next_year": "Next year",
                            "next_month": "Next month",
                            "today": "Today",
                            "this_week_today": "This week up to today",
                            "this_week_full": "This full week",
                            "last_week": "Last week",
                            "last_2weeks": "Last two weeks",
                            "this_month": "This month",
                            "last_month": "Last month",
                            "last_3months": "Last 3 months",
                            "last_6months": "Last 6 months",
                            "this_year": "This year",
                            "last_year": "Last year"
                        },
                        "rtl": 0
                    };



                    $('#uploadImageFile').change(function(_e) {
                        if ($(this).val() != '') {
                            console.log($('#image-form').serializeArray());
                            // $.post('background.php', $('#image-form').serializeArray()).done(function(data){
                            // });
                            $('#image-form').submit();
                        }
                    })
                    $('.removeimage').on('click', function(event) {
                        var r = confirm("Are you sure you want to delete this Image?")
                        if (r == true) {
                            $.post(
                                    './delete.php', {
                                        'file': $(this).attr('data-src'),
                                        'file1': $(this).attr('data-thumb-src')
                                    })
                                .done(
                                    function(response) {
                                        alert("success");
                                        $(event.target).parents('tr').remove();
                                    })
                                .error(function(err) {
                                    alert(err);
                                })
                                .always(function(){
                                    alert("done");
                                });
                        }
                    })
                </script>
            </div>

        </div>
    </div>

    <?php include("footer.php"); ?>

</body>

</html>