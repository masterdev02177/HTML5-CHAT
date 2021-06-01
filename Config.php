<?php
spl_autoload_register(function($class_name) {
    include __DIR__.'/classes/'.$class_name . '.php';
});
define('DEFAULT_VIEW', 'home');
define('DEBUG', false);
define('LANGUE','en');
define('ADMINURL','/adminchat');

// DB
define('hostname_con1', 'localhost');
define('database_con1', 'zadmin_chat8');
define('username_con1', 'root');
define('password_con1', '');

define('HOME_HTTP', 'https://chat8.hostingchat.nl/');
define('HTTP_NODE_MULTI', 'https://socket8.hostingchat.nl:8001'); //chat
define('HTTP_NODE_MULTI2', 'https://socket8.hostingchat.nl:8002'); // chat
define('WEBMASTER_EMAIL', 'info@hostingmilano.nl');
define('WEBMASTERID', 1);



//UPLOAD

define('UPLOAD_GENDERS', __DIR__.'/upload/genders/');
define('UPLOAD_THUMBS', __DIR__.'/upload/thumbs/');
define('UPLOAD_ROOMS', __DIR__.'/upload/rooms/');
define('UPLOAD_LOGO', __DIR__.'/upload/logo/');
define('UPLOAD_ROLES', __DIR__.'/upload/roles/');
define('UPLOAD_IMAGES_MESSAGES', __DIR__.'/upload/messages/');
define('UPLOAD_IMAGES_MESSAGES_URL', '/upload/messages/');
define('UPLOAD_IMAGES_MESSAGES_WIDTH', 200);
define('UPLOAD_IMAGES_MESSAGES_HEIGHT', 200);
define('UPLOAD_VIDEOS', __DIR__.'/upload/videos/');
define('UPLOAD_AUDIO', __DIR__.'/upload/audio/');


// SMTP
define('USESMTP', false);
define('SMTPHost', 'SSL0.OVH.NET');
define('SMTPDebug',1);
define('SMTPAuth', true);
define('SMTPPort', 465);
define('SMTPUsername', 'noreply@server2.buychatroom.com');
define('SMTPUsernameWP', 'wp@server2.buychatroom.com');
define('SMTPPassword', '******');
define('EMAILFROM', 'noreply@server2.buychatroom.com');

// camera

define('micRate', 11);
define('cameraWidth', 320);
define('cameraHeight', 240);
define('cameraBandWidth', 0); // 0 = all possible
define('cameraQuality', 75); //  0-90
define('cameraFPS', 20); //  0-90
define('cameraKeyFrameInterval', 8); // keyframe
