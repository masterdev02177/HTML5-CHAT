<?php
session_start();
include '../Config.php';
include_once('protect.php');
checkCanEnter('adminpanelRoles');

$webmasterid = 1;
$webmaster = Webmaster::get($webmasterid);

$config = DB::getOne('chat_config', "where webmasterid=$webmasterid");
$webmaster = Webmaster::get($webmasterid);
$chatType = $config->chatType;

include 'xcrud/xcrud.php';
include 'lng/language.php';
!empty($_GET['lang']) ? $lng = new Language($_GET['lang']) : $lng = new Language();
$lng = $lng->getData();
$lngPage = $lng['rooms'];

$xcrud = Xcrud::get_instance();
$table = 'chat_roles';
$xcrud->table($table);
$xcrud->pass_var('webmasterid', $webmasterid);
$xcrud->where('webmasterid =', $webmasterid);

$xcrud->columns('role, image');
$xcrud->fields('role, mappedRole, canPushToTalk, canKick, canBan, canMute, canMutePrison, canMuteWebcam, isVisible, canBroadcast, autoBroadcast, canStream, canWatch, canSend, canSendPrivate, canPostYouTube, canOpenAnyWebcam, canWhisper,
canQuickMessage, canAskPrivate, canGetIP, color, colorText, colorPicker, image, webcamAutoStart, showOnTopofUserList, explanationIfDisabled, webcamMax,canSpyWhisperMessages,
 canCreateDynamicRoomNumber, canUpload, canSnapshot, canRecordVoice, canSeeInvisibleUsers, canCall1To1, canBeCalled1To1, canRequestWebcam, canPaste', false, 'Global');

$xcrud->fields('canBeKicked, canBeBanned, canBeMuted, canBeMutedPrison, canAskFriend, canBeAskedAsFriend, canAccessPasswordProtecedRooms, canPromote, canBePromoted,canDeleteUserMessages,
canUserMessagesDeleted, canEnterChatAdmin, canRequestWebcamWhenPrivate, canAddRoomToFavori, invisibleMode, maxVideoMinutesPerDay, canVoteContest, canBeVotedContest, adContent, adTimer, soundWhenFirstUserEnters', false, 'Advanced');

if ($config->chatType=='conference') {
    $xcrud->fields(' conference_usersCanAskForPrivateConference, conference_canUsersShowWebcamInPublic, conference_canUsersShowWebcamInPrivate', false, 'Conference');
}

$xcrud->change_type('image', 'image', false, array(
    'width' => 32,
    'height'=>32,
    'path' => UPLOAD_ROLES
));
$xcrud->change_type('color','text','#000000', array('id'=>'colorpicker'));
$xcrud->change_type('colorText','text','#000000', array('id'=>'colorpicker2'));
$xcrud->change_type('adContent','texteditor','', array('id'=>'adContent'));
$xcrud->change_type('adTimer','int','0', array('id'=>'adTimer'));

$xcrud->label(array('mappedRole'=>'Role used on your existing site (leave blank if none)', 'canKick' => 'Can kick', 'canBan'=>'Can ban',
    'canPushToTalk'=>'Can push to talk (talkie walkie)',
    'canMutePrison'=>'Can mute to prison',
    'canMuteWebcam'=>'Can mute a webcam',
    'autoBroadcast'=>'Can Broadcast',
    'autoBroadcast'=>'Will broadcast his webcam to all users.',
    'canStream'=>'Can Stream', 'canAskPrivate'=>'Can ask for private chat','canOpenAnyWebcam'=>'Can open any webcam',
    'canWatch'=>'Can watch', 'canSend'=>'Can Send Text', 'canSendPrivate'=>'Can send private text', 'canPostYouTube'=>'Can post youtube', 'isVisible'=>'Is user visible ?',
    'webcamAutoStart'=>'Webcam auto start', 'showOnTopofUserList'=>'Show user on top of userlist', 'canGetIP'=>'Can get user IP address',
    'canWhisper'=>'Can whisper', 'canQuickMessage'=>'Can mention (quick message)', 'explanationIfDisabled'=>'Alert explanation when action not allowed (leave blank to not dispaly)',
    'canSpyWhisperMessages'=>'Can Spy on whisper messages',
    'canCreateDynamicRoomNumber'=>'How many dynamic rooms can user create',
    'canBeKicked'=>'Can user be kicked from chat',
    'canBeBanned'=>'Can user be banned from chat',
    'canBeMuted'=>'Can user be muted on chat',
    'canBeMutedPrison'=>'Can user be put in prison on chat',
    'canAskFriend'=>'Can ask for friendship ?',
    'canBeAskedAsFriend'=>'Can user be asked as friend ?',
    'canAccessPasswordProtecedRooms'=>'Can user access password proteced rooms',
    'webcamMax'=>'Maximum opened webcams',
    'canMute'=>'Can mute/Ignore',
    'colorText'=>'Color of the text',
    'canPromote'=>'Can this role promote an user as moderator ?',
    'canBePromoted'=>'Can this role be promoted as moderator ?',
    'canDeleteUserMessages'=>'Can this role delete other users messages ?',
    'canUserMessagesDeleted'=>'Can this role have his messages deleted by other users ?',
    'canEnterChatAdmin'=>'Can this role enter this chatadmin panel ? (Be careful, you can ban yourself !)',
    'conference_usersCanAskForPrivateConference'=>'Can users ask for exclusive private conference ?',
    'conference_redirectOtherUsersUrlWhenPrivateStarts'=>'Url: where to redirect other users if private conference starts ?',
    'conference_canUsersShowWebcamInPublic'=>'Can other users show their webcam during public conference ?',
    'conference_canUsersShowWebcamInPrivate'=>'Can other users show their webcam during private conference ?',
    'canRequestWebcamWhenPrivate'=>'Can request webcam when webcams are private',
    'canAddRoomToFavori'=>'Can add a room as favourit',
    'invisibleMode'=>'Invisible mode',
    'maxVideoMinutesPerDay'=>'Max minutes video per day (set 0 for unlimited)',
    'canVoteContest'=>'Can vote for other users',
    'canBeVotedContest'=>'Can users vote for this role ?',
    'adContent'=>'Content of an ad message',
    'adTimer'=>'Timer in seconds to display adConent (leave 0 if none)',
    'canUpload'=>'Can upload images',
    'canSnapshot'=>'Can take webcam images snapshots',
    'canRecordVoice'=>'Can record voice',
    'canSeeInvisibleUsers'=>'Can see invisible users',
    'canCall1To1'=>'Can make audio/video 1to1 calls',
    'canBeCalled1To1'=>'Can be called by 1to1 call ?',
    'canRequestWebcam'=>'Can request webcam ?',
    'canPaste'=>'Can paste text in chat ?',
    'soundWhenFirstUserEnters'=>'Mp3 sound to play when an users joins the room. Leave blank for none',

));

$xcrud->set_attr('soundWhenFirstUserEnters',array('placeholder'=>'https://mysite.com/sound.mp3'));


$xcrud->field_tooltip('mappedRole', 'This field allows to map a role that already exist on your website to a role on HTML5 chat. This is useful if you use different roles label on your website (leave blank if you don\'t need it');
$xcrud->field_tooltip('webcamAutoStart', 'Should webcam auto start  ?');
$xcrud->field_tooltip('showOnTopofUserList', 'Should user appear on top of user list (ex: admin may appear on top)');
$xcrud->field_tooltip('canGetIP', 'Is Able to get User IP address (better if only for admin)');
$xcrud->field_tooltip('canCreateDynamicRoomNumber', 'How many dynamic rooms can user create ? (0 if none). A dynamic room is deleted when its creator leaves the chat. Make sure you have the "multiRoomEnter" set to true to use that feature.');
$xcrud->field_tooltip('canBeKicked', 'Can user be kicked from chat : ex: admin should not be kicked');
$xcrud->field_tooltip('canBeBanned', 'Can user be banned from chat : ex: admin should not be banned');
$xcrud->field_tooltip('canBeMutedPrison', 'Can user be put in prison : ex: admin should not be muted');
$xcrud->field_tooltip('canBeMuted', 'Can user be muted on chat : ex: admin should not be muted');
$xcrud->field_tooltip('canAskFriend', 'Can this role ask other people to be a friend ?');
$xcrud->field_tooltip('canBeAskedAsFriend', 'Can this role be asked to be a friend ?');
$xcrud->field_tooltip('canAccessPasswordProtecedRooms', 'Can this role be able to access password protected rooms (only admin should be anle to do that)');
$xcrud->field_tooltip('canMutePrison', 'Can mute an user (put in a jail for N minutes). Muted users can enter the chat but as watcher only');
$xcrud->field_tooltip('canMuteWebcam', 'Can this role mute webcam ?(switch other user\'s webcam off');
$xcrud->field_tooltip('webcamMax', 'Maximum number of cams this role can open');
$xcrud->field_tooltip('canPromote', 'Can this role promote another  user to moderator ?');
$xcrud->field_tooltip('canBePromoted', 'Can this role be promoted as moderator ?');
$xcrud->field_tooltip('canDeleteUserMessages', 'Can this role delete some one else messages ?');
$xcrud->field_tooltip('canUserMessagesDeleted', 'Can this role have his messages deleted by other users ?');
$xcrud->field_tooltip('canEnterChatAdmin', 'Can this role enter this /chatadmin panel and edit the chat config ?');
$xcrud->field_tooltip('canRequestWebcamWhenPrivate', 'Can this role request to watch a webcam when webcams are private ?');
$xcrud->field_tooltip('color', 'This color will overwrite the color chosen in genders. Ex: if an user has blue color as male gender and red color beacuse of admin role, then red will overwrite the blue color');
$xcrud->field_tooltip('colorText', 'Color of the text when user sends a message. If user has colorPicker, then he can change that color');
$xcrud->field_tooltip('colorPicker', 'Role can use the colorPicker to change text color');
$xcrud->field_tooltip('canAddRoomToFavori', 'Can add a room as favourit');
$xcrud->field_tooltip('invisibleMode', 'Can be invisible to other users');
$xcrud->field_tooltip('maxVideoMinutesPerDay', 'How many minutes per day can this role watch other users webcam ?');
$xcrud->field_tooltip('canVoteContest', 'Allows user to vote for other users. (not allowed for guests)');
$xcrud->field_tooltip('canBeVotedContest', 'Allows users to vote for this role - not allowed for guests. (ex: admin should not contest)');
$xcrud->field_tooltip('adContent', 'You can display an HTML text to this role every N seconds (ex: for guest users, a banner to incite to subscribe)');
$xcrud->field_tooltip('adTimer', "Time in seconds to display the adContent. Put it to 0 if you don't want want it to be displayed");
$xcrud->field_tooltip('canUpload', "Role can upload images");
$xcrud->field_tooltip('canSnapshot', "Role can take snpahsots with his webcam can upload them");
$xcrud->field_tooltip('canRecordVoice', "Role can record audio messages");
$xcrud->field_tooltip('canSeeInvisibleUsers', "Role can see invisible users (should be checked for admin)");
$xcrud->field_tooltip('canCall1To1', "Role can make exclusive 1to1 audio/video call to another user");
$xcrud->field_tooltip('canBeCalled1To1', "Role can be called by a call 1to1 ?");
$xcrud->field_tooltip('canRequestWebcam', "Role can request a webcam when webcam is private ? (ex: guests should not request webcams)");
$xcrud->field_tooltip('canMute', "Other user can mute/ignore that user.");
$xcrud->field_tooltip('webcamAutoStart', "Should this role broadcast his webcam to all users automatically when he starts his webcam ?");
$xcrud->field_tooltip('soundWhenFirstUserEnters', "Play an http MP3 sound when an user enters the chat");

//view, edit, remove, duplicate, add, csv, print, save_new, save_edit, save_return, return.
$xcrud->hide_button('save_new');
//$xcrud->hide_button('save_return');
$xcrud->hide_button('return');
$xcrud->unset_add();
$xcrud->unset_search();
$xcrud->unset_remove();

//$xcrud->unset_list();
$xcrud->unset_title();
$xcrud->unset_view();
$xcrud->unset_csv();
$xcrud->unset_limitlist();
$xcrud->unset_numbers();
$xcrud->unset_print();
$xcrud->unset_sortable();
$xcrud->readonly('role');
//	$xcrud->emails_label(' email');
//$xcrud->show_primary_ai_column(true);

$xcrud->condition('role','=','admin','disabled','canEnterChatAdmin');
$xcrud->condition('role','=','guest','disabled','canVoteContest');
$xcrud->condition('role','=','guest','disabled','canBeVotedContest');

$xcrud->before_edit('before_details_callback');

//canVoteContest, canBeVotedContest

?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name=viewport content="width=device-width, initial-scale=1">
    <?php include 'css.php';?>
    <?php include 'js.php';?>

    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" href="../css/colorPicker.css">
    <style>
        tr td a.xcrud-button[data-task="remove"] {
            display: none!important;
            font-size: 2em!important;
        }
    </style>
    <title>Roles</title>
</head>

<body>
<div class="panel panel-default admin-panel">
    <div class="panel-heading">
        <Button class="btn a-header-btn" id="logout"><i class="fa fa-sign-out"></i> <?php echo $lng['menu']['logout']; ?></Button>
        <ul class="breadcrumb">
            <li><a href="loggedon.php"><?php echo $lng['menu']['loggedon']; ?></a></li>
            <li class="active">Roles</li>
        </ul>
    </div>
    <div class="panel-body">
        <div class="flex-property adition-box margin-btm">

        </div>
        <div class="admin-table rooms-table">
            <?php echo $xcrud->render($webmasterid); ?>
        </div>
    </div>
</div>

<?php include 'footer.php';?>

</body>
<script src="../js/jquery.colorPicker.min.js"></script>
<script>

    jQuery(document).on("ready xcrudafterrequest", function(){
        $('#adTimer').on('change keypress', function() {
            if ($(this).val()==0) {
                $('#cke_adContent').hide();
            } else {
                $('#cke_adContent').show();
            }
        })

        jQuery('#colorpicker').colorPicker();
        jQuery('#colorpicker2').colorPicker();
        //jQuery("table.xcrud-details-table td:eq(35)").append("<button class='btn btn-xs' id='resetBtn'>No color</button>");
        $("#colorpicker").parent().append("<button class='btn btn-xs' id='resetBtn'>No color</button>");

        jQuery(document).on('click','#resetBtn', function(e) {
            jQuery('#colorpicker').val('');
            jQuery('#colorpicker2').val('');
            jQuery('div.colorPicker-picker').css('background-color', '#FFF');
        });

    });
    jQuery('div.xcrud').on('click', 'a', function(e) {
        //alert('edit');
        //e.preventDefault();
        //e.stopImmediatePropagation();
    })

</script>
</html>
