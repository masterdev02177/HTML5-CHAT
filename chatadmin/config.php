<?php
session_start();
include_once  '../Config.php';
include_once 'protect.php';
checkCanEnter('adminpanelChatconfig');

$webmasterid = $_SESSION['admin'];
$config = DB::getOne('chat_config', "where webmasterid=$webmasterid");
$configid = $config->id;
$webmaster = Webmaster::get($webmasterid);
$chatType = $config->chatType;

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
$webrtc = (!$webmaster->free && !$webmaster->expired);

// tabs
$fields = 'siteUrl, webrtc, multiRoomEnter, chatType, saveMessages, quitUrl, actionOnForbiddenWord, timerUserCanSendAgain, privateOnlyOnInvitation,
langue, youtubePlayer, userYoutubeLinkDisplayInChat, allowQuickPrivateMessagesInPublicChat, allowWhisper,
 showQuickPrivateMessagesInPublicChat, showMessageServer, hideMessageServerAfterNseconds, closePrivateChatWhenUserLeaves,
 displayRoomsChoiceWhenEnterChat, enterFirstRoomIfChoiceAuto, displayPastChatHistory, uploadImages, linkifyUrl, maxSizeUpload, privateClosesWhenOneUserClosesPrivate, keepCusrorOnPrivateAfterPrivateSent,
 guestConditionsCheckbox, howBroadcastOpens, friendsManagment, webradio, screenshotUrl';
if (!$webrtc) {
    $xcrud->disabled('webrtc, multiRoomEnter');
}
$xcrud->fields($fields, false, 'Chat');

$xcrud->fields('theme, logo, displayLeaveChatMessage, showCountryFlag, senderMessageFloatRight, showSmileys, widthGenderIcon, heightGenderIcon, displayGifsSearch, showBackgrounds,
showGenderFilters, externalCSSLink, externalJS, displayRoomTab, displayEnterChatMessage, displayRoomButton, displayPrivateButton, helpUrl, displayAge, showSearchRoom, showSearchRoomAdultCheckbox, displayUsersSidebar',
    false, 'Design');

$xcrud->fields('webcam, audioVideo, chatroulette, webcamPublic, webcamMax, webcamWidth, webcamHeight, webcamAutoStart, displayWebcamButton, myWebcamDraggable, displayWebcamPublicWebcamPrivateButtons,
soundMutedAtStart, codec,notifyWhenUserStartsWebcam', false, 'Webcam');

$xcrud->fields('enterChatMode, userCanRegister, forgottenEmailTemplate, fromEmail, userMustConfirmEmail, bannedUrl,
showRandomAvatarsForGuests, showUserInfoDataUrlOrJavascript,  colorAdmin, colorModerator, colorDJ, timeoutOffline, timeoutLogout', false, 'Users management');

if ($config->chatType=='conference') {
    $xcrud->fields('conference_usersCanCommunicateTogether,  conference_redirectOtherUsersUrlWhenPrivateStarts, conferencePrivatePaymentUrl', false, 'Conference');
}
$xcrud->fields('pushToTalk, timeoutBeforeTalkAgain, pushToTalkMax, pushToTalkFreeHand, pushToTalkFreeHandAdminOnly', false, 'Push video to talk');
$xcrud->fields('guestTrialVersionOnly, guestTrialUrlWhenForbidden, guestTrialMessage', false, 'Trial version chat Messages');



$xcrud->label(array(
    'siteUrl'=>'Site URL',
    'webrtc' => 'Enable WEBRTC',
    'chatroulette'=>'Chat roulette mode',
    'multiRoomEnter' => 'Chat multi room',
    'chatType' => $lngPage['chatType'],
    'quitUrl'  => $lngPage['quitUrl' ],
    'showSmileys' => $lngPage['showSmileys'],
    'saveMessages' => $lngPage['saveMessages'],
    'actionOnForbiddenWord' => $lngPage['actionOnForbiddenWord'],
    'timerUserCanSendAgain' => $lngPage['timerUserCanSendAgain'],
    'privateOnlyOnInvitation' => $lngPage['privateOnlyOnInvitation'],
    'webcam' => $lngPage['webcam'],
    'webcamPublic' => $lngPage['webcamPublic'],
    'webcamMax' => $lngPage['webcamMax'],
    'enterChatMode' => 'Enter chat mode',
    'forgottenEmailTemplate' => $lngPage['forgottenEmailTemplate'],
    'fromEmail' => $lngPage['fromEmail'],
    'userMustConfirmEmail' => $lngPage['userMustConfirmEmail'],
    'widthGenderIcon' => $lngPage['widthGenderIcon'],
    'heightGenderIcon' => $lngPage['heightGenderIcon'],
    'bannedUrl' => $lngPage['bannedUrl'],
    'langue' => 'Language <br>(if you need your country language, please <a target="_blank" href="/contact">Contact us</a>)',
    'webcamWidth' => $lngPage['webcamWidth'],
    'webcamHeight' => $lngPage['webcamHeight'],
    'showRandomAvatarsForGuests' => $lngPage['showRandomAvatarsForGuests'],
    'userCanRegister' => $lngPage['userCanRegister'],
    'youtubePlayer' => $lngPage['youtubePlayer'],
    'webcamAutoStart' => $lngPage['webcamAutoStart'],
    'displayRoomButton' => $lngPage['displayRoomButton'],
    'displayWebcamButton' => $lngPage['displayWebcamButton'],
    'displayPrivateButton' => $lngPage['displayPrivateButton'],
    'allowQuickPrivateMessagesInPublicChat' => $lngPage['allowQuickPrivateMessagesInPublicChat'],
    'myWebcamDraggable' => $lngPage['myWebcamDraggable'],
    'displayWebcamPublicWebcamPrivateButtons' => $lngPage['displayWebcamPublicWebcamPrivateButtons'],
    'showQuickPrivateMessagesInPublicChat' => $lngPage['showQuickPrivateMessagesInPublicChat'],
    'pushToTalk' => $lngPage['pushToTalk'],
    'timeoutBeforeTalkAgain' => $lngPage['timeoutBeforeTalkAgain'],
    'pushToTalkMax' => $lngPage['pushToTalkMax'],
    'pushToTalkFreeHand' => $lngPage['pushToTalkFreeHand'],
    'pushToTalkFreeHandAdminOnly' => $lngPage['pushToTalkFreeHandAdminOnly'],
    'display_news_minutes' => $lngPage['display_news_minutes'],
    'urlProtection'  => $lngPage['urlProtection' ],
    'externalCSSLink' => $lngPage['externalCSSLink'],
    'showMessageServer' => $lngPage['showMessageServer'],
    'hideMessageServerAfterNseconds' => $lngPage['hideMessageServerAfterNseconds'],
    'guestTrialVersionOnly' => 'Chat will display trial version for users who have no right to access some features',
    'guestTrialUrlWhenForbidden' => $lngPage['guestTrialUrlWhenForbidden'],
    'showUserInfoDataUrlOrJavascript' => $lngPage['showUserInfoDataUrlOrJavascript'],
    'colorAdmin' => $lngPage['colorAdmin'],
    'colorModerator' => $lngPage['colorModerator'],
    'colorDJ' => $lngPage['colorDJ'],
    'closePrivateChatWhenUserLeaves' => $lngPage['closePrivateChatWhenUserLeaves'],
    'displayRoomsChoiceWhenEnterChat' => $lngPage['displayRoomsChoiceWhenEnterChat'],
    'enterFirstRoomIfChoiceAuto' => "Enter the first available room automatically (when multi room chat is enabled)",
    'displayPastChatHistory' => $lngPage['displayPastChatHistory'],
    'displayGifsSearch' => $lngPage['displayGifsSearch'],
    'displayRoomTab' => 'Display rooms tab',
    'uploadImages' => $lngPage['uploadImages'],
    'soundMutedAtStart' => $lngPage['soundMutedAtStart'],
    'linkifyUrl' => $lngPage['linkifyUrl'],
    'logo' =>'Chat logo',
    'senderMessageFloatRight'=>'Display my Own messages aligned on right',
    'allowWhisper'=>'Allow whisper',
    'showBackgrounds'=>'Show backgrounds',
    'showGenderFilters'=>'Show gender filters',
    'maxSizeUpload'=>'Maximum image upload (in Kbytes)',
    'showCountryFlag'=>'Show country flag',
    'displayEnterChatMessage'=>'Display message when user enters room',
    'displayLeaveChatMessage'=>'Display message when user leaves room',
    'privateClosesWhenOneUserClosesPrivate'=>'Close private chat on both sides when of the users closes private',
    'keepCusrorOnPrivateAfterPrivateSent'=>'Keep cursor with selected member after private message was sent',
    'helpUrl'=>'Url of help file',
    'conference_webcamWidth'=>'Webcam width',
    'conference_webcamHeight'=>'Webcam height',
    'conference_usersCanCommunicateTogether'=>'Users can chat together ?',
    'conferencePrivatePaymentUrl'=>'Url if conference is Paid. A purchase button will appear on user interface.',
    'externalJS'=>'URL for external Javascript',
    'guestTrialMessage'=>'Message to be displayed when feature is disabled for user',
    'guestConditionsCheckbox'=>'Checkbox message for guests (leave blank for none)',
    'displayAge'=>'Display age of user',
    'timeoutOffline'=>'Timeout: Time in seconds after user shows as offline (put 0 if none)',
    'timeoutLogout'=>'Timeout:Time in seconds after user quits the chat (put 0 if none)',
    'showSearchRoom'=>'Show room search',
    'showSearchRoomAdultCheckbox'=>'Show adult room search',
    'howBroadcastOpens'=>'Broadcast behaviour',
    'codec'=>'Video codec for webrtc',
    'audioVideo'=>'Video/Audio',
    'notifyWhenUserStartsWebcam'=>'Notify in chat when an user opens a webcam',
    'friendsManagment'=>'Allows friends management',
    'screenshotUrl'=>'Generate screenshot of URL ',
    'webradio'=>'Url of webradio (leave blank if none) ',
    'displayUsersSidebar'=>'Display users list and sidebar',
    'userYoutubeLinkDisplayInChat'=>'Display youtube inside the chat text'
));
if ($webmaster->free  || $webmaster->expired) {
    $xcrud->change_type('screenshotUrl','bool', '', array('id'=>'screenshot', 'disabled'=>'true'));
}


if (!$webrtc) {
    $xcrud->label(array(
            'webrtc' => 'Enable WEBRTC (<a href="/purchase">only paid version</a>)',
            'multiRoomEnter' => 'Chat multi room (<a href="/purchase">only paid version</a>)',
            'logo' => 'Chat logo (<a href="/purchase">only paid version</a>)'
        )
    );
}

$xcrud->validation_pattern('widthGenderIcon', 'natural');
$xcrud->validation_pattern('heightGenderIcon', 'natural');
$xcrud->validation_pattern('webcamMax', 'natural');
$xcrud->validation_pattern('timerUserCanSendAgain', 'natural');
$xcrud->validation_pattern('display_news_minutes', 'integer');

$xcrud->field_tooltip('webrtc', 'Enable Webrtc or enable Flash for streaming');
$xcrud->field_tooltip('chatType', "tab = all in tabs: many to many chat: private chats and webcams<br><br>
windows = many to many chat:  all in dragabble windows<br><br>
tabAndWindow = many to many chat:  webcams in windows, private chats in<br><br>
BETA conference = 1 performer and many viewers.");
$xcrud->field_tooltip('theme', $lngPage['theme']);
$xcrud->field_tooltip('showSmileys', $lngPage['showSmileysL']);
$xcrud->field_tooltip('quitUrl', $lngPage['quitUrlL']);
$xcrud->field_tooltip('saveMessages', $lngPage['saveMessagesL']);
$xcrud->field_tooltip('actionOnForbiddenWord', $lngPage['actionOnForbiddenWordL']);
$xcrud->field_tooltip('timerUserCanSendAgain', $lngPage['timerUserCanSendAgainL']);
$xcrud->field_tooltip('forgottenEmailTemplate', $lngPage['forgottenEmailTemplateL']);
$xcrud->field_tooltip('fromEmail', $lngPage['fromEmailL']);
$xcrud->field_tooltip('webcam', $lngPage['webcamL']);
$xcrud->field_tooltip('webcamPublic', $lngPage['webcamPublicL']);
$xcrud->field_tooltip('webcamMax', $lngPage['webcamMaxL']);
$xcrud->field_tooltip('privateOnlyOnInvitation', $lngPage['privateOnlyOnInvitationL']);
$xcrud->field_tooltip('enterChatMode', "How users do enter the chat : as guests or as registered users or you can even mix both !. Guests users have 'guest' role. Registered users have 'user' role");
$xcrud->field_tooltip('userMustConfirmEmail', $lngPage['userMustConfirmEmailL']);
$xcrud->field_tooltip('bannedUrl', $lngPage['bannedUrlL']);
$xcrud->field_tooltip('langue', $lngPage['langueL']);
$xcrud->field_tooltip('webcamWidth', $lngPage['webcamWidthL']);
$xcrud->field_tooltip('webcamHeight', $lngPage['webcamHeightL']);
$xcrud->field_tooltip('showRandomAvatarsForGuests', $lngPage['showRandomAvatarsForGuestsL']);
$xcrud->field_tooltip('userCanRegister', $lngPage['userCanRegisterL']);
$xcrud->field_tooltip('youtubePlayer', $lngPage['youtubePlayerL']);
$xcrud->field_tooltip('webcamAutoStart', $lngPage['webcamAutoStartL']);
$xcrud->field_tooltip('displayRoomButton', $lngPage['displayRoomButtonL']);
$xcrud->field_tooltip('displayWebcamButton', $lngPage['displayWebcamButtonL']);
$xcrud->field_tooltip('displayPrivateButton', $lngPage['displayPrivateButtonL']);
$xcrud->field_tooltip('allowQuickPrivateMessagesInPublicChat', $lngPage['allowQuickPrivateMessagesInPublicChatL']);
$xcrud->field_tooltip('myWebcamDraggable', $lngPage['myWebcamDraggableL']);
$xcrud->field_tooltip('displayWebcamPublicWebcamPrivateButtons', $lngPage['displayWebcamPublicWebcamPrivateButtonsL']);
$xcrud->field_tooltip('showQuickPrivateMessagesInPublicChat', $lngPage['showQuickPrivateMessagesInPublicChatL']);
@$xcrud->field_tooltip('pushToTalk', 'Push to talk');
$xcrud->field_tooltip('timeoutBeforeTalkAgain', $lngPage['timeoutBeforeTalkAgainL']);
$xcrud->field_tooltip('pushToTalkMax', $lngPage['pushToTalkMaxL']);
$xcrud->field_tooltip('pushToTalkFreeHand', $lngPage['pushToTalkFreeHandL']);
$xcrud->field_tooltip('pushToTalkFreeHandAdminOnly', $lngPage['pushToTalkFreeHandAdminOnlyL']);
$xcrud->field_tooltip('display_news_minutes', $lngPage['display_news_minutesL']);
$xcrud->field_tooltip('urlProtection', $lngPage['urlProtectionL']);
$xcrud->field_tooltip('externalCSSLink', $lngPage['externalCSSLinkL']);
$xcrud->field_tooltip('showMessageServer', $lngPage['showMessageServerL']);
$xcrud->field_tooltip('hideMessageServerAfterNseconds', $lngPage['hideMessageServerAfterNseconds']);
$xcrud->field_tooltip('guestTrialVersionOnly', "Chat will display trial version for users who have no right to access some features<br>
Ex: guest user who wants to access webcam and does not have right to access webcam will have a popup displayed telling him he has no right to access that feature.
This field is important if you want to incite users to register for instance");
$xcrud->field_tooltip('guestTrialUrlWhenForbidden', $lngPage['guestTrialUrlWhenForbiddenL']);
$xcrud->field_tooltip('showUserInfoDataUrlOrJavascript', $lngPage['showUserInfoDataUrlOrJavascriptL']);


$xcrud->field_tooltip('colorAdmin', $lngPage['colorAdmin']);
$xcrud->field_tooltip('colorModerator', $lngPage['colorModerator']);
$xcrud->field_tooltip('colorDJ', $lngPage['colorDJ']);
$xcrud->field_tooltip('displayRoomsChoiceWhenEnterChat', $lngPage['displayRoomsChoiceWhenEnterChat']);
$xcrud->field_tooltip('enterFirstRoomIfChoiceAuto', "Should user go to first available room when enter chat. This option should be ONLY used with multi room AND when choice of rooms is displayed before enter chat (check it by default)");
$xcrud->field_tooltip('displayPastChatHistory', $lngPage['displayPastChatHistory']);
$xcrud->field_tooltip('displayGifsSearch', $lngPage['displayGifsSearch']);
$xcrud->field_tooltip('displayRoomTab', 'Display rooms tabs on right ?');
$xcrud->field_tooltip('uploadImages', $lngPage['uploadImages']);
$xcrud->field_tooltip('soundMutedAtStart', $lngPage['soundMutedAtStart']);
$xcrud->field_tooltip('linkifyUrl', $lngPage['linkifyUrl']);
$xcrud->field_tooltip('logo', 'Logo of the chat');
$xcrud->field_tooltip('multiRoomEnter', 'Usres can enter many rooms simultaneously or only 1 room');
$xcrud->field_tooltip('senderMessageFloatRight', 'Display my Own chat messages aligned on right (or left if not checked)');
$xcrud->field_tooltip('allowWhisper', 'Allow Whisper (private chat messages)');
$xcrud->field_tooltip('showBackgrounds', 'Show backgrounds)');
$xcrud->field_tooltip('showGenderFilters', 'Show gender filters in chat)');
$xcrud->field_tooltip('maxSizeUpload', 'Maximum size of upload images (in Kbytes)');
$xcrud->field_tooltip('showCountryFlag', 'Show the country flag of the user');
$xcrud->field_tooltip('showCountryFlag', 'Show the country flag of the user');
$xcrud->field_tooltip('displayEnterChatMessage', 'Display message when user enters room');
$xcrud->field_tooltip('displayLeaveChatMessage', 'Display message when user leaves room');
$xcrud->field_tooltip('privateClosesWhenOneUserClosesPrivate', 'When 2 users are in private chat and one of them leaves closes the private chat, the private chat will be close for the second user as well');
$xcrud->field_tooltip('keepCusrorOnPrivateAfterPrivateSent', 'When sending a private message to a member with whisper, keep the member selected for next whisper');
$xcrud->field_tooltip('helpUrl', 'Url of help file (leave blank if node)');
$xcrud->field_tooltip('externalJS', 'Inject external javascript JS.<br>ex: https://code.jquery.com/jquery-3.3.1.min.js <br>Warning: bad JS syntax can make your chat stop running normally');
$xcrud->field_tooltip('guestTrialMessage', "What message should be displayed when an user tries to access a feature ha has no right to access");
$xcrud->field_tooltip('guestConditionsCheckbox', "Guest user will have to check it to enter the chat.<br> ex: I agree to conditions and I am over 13 years old");
$xcrud->field_tooltip('displayAge', "Display age of user. You need to inject the parameter <b>birthyear</b> in JWT to get this data. <br>ex: birthyear:1985");
$xcrud->field_tooltip('timeoutOffline', "Time in seconds after user shows as offline in his status (put 0 if none)");
$xcrud->field_tooltip('timeoutLogout', "Time in seconds after user disconnects and quits the chat (put 0 if none)");
$xcrud->field_tooltip('showSearchRoom', "Allows to quickly search a room by name");
$xcrud->field_tooltip('showSearchRoomAdultCheckbox', "Allows to display adult room checkbox for search");
$xcrud->field_tooltip('howBroadcastOpens', "How should broadcast work ? 3 different modes:<br> opens: new webcam opens automatically<br>chat warn: begin of broadcast is notified in chat. <br>notification warn: begin of broadcast is notified in a notification popup");
$xcrud->field_tooltip('codec', "What codec to be used in the chat ?<br>h264 is Safari compatible, but has lower bigger bandwidth and less quality.<br>vp8 is a modern codec. It has lower bandwidth, better quality and more reliability but has no safari Support");
$xcrud->field_tooltip('chatroulette', "Adds a button chatroulette that allows to pick a random public webcam available");
$xcrud->field_tooltip('notifyWhenUserStartsWebcam', "Write a message in chat that says: USERNAME has opened his webcam.");
$xcrud->field_tooltip('friendsManagment', "Check this to allow friends managements (individual friends must be then defined in roles).");
$xcrud->field_tooltip('screenshotUrl', "Generate an image screenhot when user sends an url in the chat (allows to preview the website content visually). Available only for PAID users");
$xcrud->field_tooltip('webradio', "Enter the url of your webradio ex: http://mysite.com:8040/stream (itunes radio stream). If provided a small webradio will be added to your chat");
$xcrud->field_tooltip('displayUsersSidebar', "Should be checked unless you do not want the userlist to be displayed (only suitable for a simple shoutbox)");
$xcrud->field_tooltip('userYoutubeLinkDisplayInChat', "Should the youtube player be displayed inside the chat text or in the youtube player (in the right siderbar)");


$xcrud->change_type('forgottenEmailTemplate', 'texteditor');
$xcrud->change_type('display_news_minutes','int');
$xcrud->change_type('maxSizeUpload','int');
$xcrud->change_type('colorAdmin','text','#000',array('id'=>'colorAdmin'));
$xcrud->change_type('colorModerator','text','#000',array('id'=>'colorModerator'));
$xcrud->change_type('colorDJ','text','#000',array('id'=>'colorDJ'));
$xcrud->change_type('maxSizeUpload','text','',array('id'=>'maxSizeUpload', 'type'=>'number', 'min'=>1, 'max'=>500));
$xcrud->change_type('externalJS','text');
$xcrud->change_type('guestTrialMessage','texteditor');
$xcrud->change_type('guestConditionsCheckbox','texteditor');

$xcrud->change_type('enterChatMode','select','',
    array('values'=>array(
        0=>'Registered users only: users must register to enter chat by providing his data and password (role: user)',
        1=>'Guests: users do not need to register to enter chat. They just provide username. (role: guest)',
        2=>'Registered and Guests: Guest are allowed (role: guest), registered are allowed (role: user) '
    )));

$xcrud->change_type('logo', 'image', false, array(
    'width' => 80,
    'crop'=>false,
    'path' => UPLOAD_LOGO
));

$xcrud->validation_pattern('maxSizeUpload', '[0-9]{1,3}'); //0-500 http://gamon.webfactional.com/regexnumericrangegenerator/


//view, edit, remove, duplicate, add, csv, print, save_new, save_edit, save_return, return.
//$xcrud->hide_button('save_new');
//$xcrud->hide_button('save_return');
//$xcrud->hide_button('return');
$xcrud->unset_add();
$xcrud->unset_list();
$xcrud->unset_title();
$xcrud->before_edit('before_details_callbackConfig');


if ($webmaster->free || $webmaster->expired) {
    $xcrud->disabled('webcamMax, webcamWidth, webcamHeight');
}

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
    <link rel="stylesheet" type="text/css" href="css/admin.css?cache=<?php echo time();?>">
    <link rel="stylesheet" type="text/css" href="../css/colorPicker.css">
    <link rel="stylesheet" type="text/css" href="../css/common.css">

    <style>



    </style>
    <title><?php echo $lngPage['metaTtitle']; ?></title>
</head>
<body>
<div class="panel panel-default admin-panel">
    <div class="panel-heading">
        <Button class="btn a-header-btn" id="logout"><i class="fa fa-sign-out"></i> <?php echo $lng['menu']['logout']; ?></Button>
        <ul class="breadcrumb">
            <li><a href="loggedon.php"><?php echo $lng['menu']['loggedon']; ?></a></li>
            <li class="active"><?php echo $lng['menu']['config']; ?></li>
        </ul>
    </div>
    <div class="panel-body">
        <div class="flex-property adition-box margin-btm">
            <?php include 'freeAccount.php';?>
        </div>
        <div class="admin-table rooms-table">
            <?php echo $xcrud->render('edit', $configid); ?>
        </div>
    </div>
</div>
<?php include 'footer.php';?>
<script src="../js/jquery.colorPicker.min.js"></script>
<script>
    $(document).on('saved', function() {
        window.location = window.location;
    })
    $(document).ready(function() {
        $('#colorAdmin').colorPicker();
        $('#colorModerator ').colorPicker();
        $('#colorDJ ').colorPicker();
    })
</script>

</body>
</html>
