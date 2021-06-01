<?php
require_once __DIR__.'/../Config.php';
$a = @$_REQUEST['a'];
switch ($a) {

    case 'upload':
        $userid = $_REQUEST['userid'];
        $audioOnly = $_REQUEST['audioOnly'];
        Video::upload($userid, $_FILES['file'], $audioOnly);
        break;
}

class Video {
    public static function secondsToHHMMSS($seconds) {
        return gmdate('H:i:s', (int) $seconds);
    }

    public static function convertToMP4($webmasterid, $fileNameNoExtension)  {
        $config = DB::getOne('chat_config', "WHERE webmasterid=$webmasterid");
        $webrtcServerUrl = $config->webrtcServerUrl;
    }


    public static function upload($userid, $file, $audioOnly) {
        $valid_mime_types = array( 'webm', 'video/webm', 'video', 'video/avi', 'video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/x-ms-wmv');
        if (!in_array($file['type'], $valid_mime_types)) {
            die($file['type'].": invalid format :".$file['type']);
            unlink($file['tmp_name']);
        }
        $FINALPATH = UPLOAD_VIDEOS;
        $finalFileName = uniqid();
        $movedVideo = $FINALPATH.$finalFileName;
        //echo "movedVideo:$movedVideo\r\n";
        move_uploaded_file($file['tmp_name'], $movedVideo);

        // FFMPEG stuff
        $exec_string = "ffmpeg -i $movedVideo -y -acodec aac -ac 2 -ab 160k -vcodec libx264 -s 640x480 -f mp4 -qscale 0 $movedVideo.mp4";
        //echo ("$exec_string\r\n");
        exec($exec_string);

        $duration = self::getVideoDuration($movedVideo);
        //echo "duration:$duration\r\n";

        if ($audioOnly=='true') {
            $finalVideoImage = $movedVideo.'.svg';
            $mp4 = "$FINALPATH/$finalFileName.mp4";
            copy(__DIR__.'/../img/sound-waves.svg', UPLOAD_VIDEOS.$finalFileName.'.svg');
            $res = array('thumb'=>"/upload/videos/$finalFileName.svg", 'mp4'=>"/upload/videos/$finalFileName.mp4");

        } else {
            $finalVideoImage = $movedVideo.'.jpg';
            self::createImage($movedVideo, $finalVideoImage);

            $thumb = "$FINALPATH/$finalFileName.jpg";
            $mp4 = "$FINALPATH/$finalFileName.mp4";

            $res = array('thumb'=>"/upload/videos/$finalFileName.jpg", 'mp4'=>"/upload/videos/$finalFileName.mp4");
        }
        unlink($movedVideo);
        echo json_encode($res);
    }



    public static function createImage($finalVideo, $finalVideoImage) {
        $duration = self::getVideoDuration($finalVideo);
        $halfDuration = round($duration / 2);
        $durationInHHMMSS = self::secondsToHHMMSS($halfDuration);
        // extract image

        $exec_string = "ffmpeg -i $finalVideo -y -ss $durationInHHMMSS -s 320x240 -vframes 1 $finalVideoImage";
        //echo ("exctractImageFromFlv: $exec_string");
        exec($exec_string);
        return $finalVideoImage;
    }

    public static function getVideoDuration($file) {
        $cmd = "ffprobe $file -show_format -v quiet | sed -n 's/duration=//p'";
        //echo ('getVideoDuration:'.$cmd);
        ob_start();
        passthru($cmd);
        $duration = ob_get_contents();
        ob_end_clean();
        $duration = preg_replace('/[\r\n]+/','', $duration);
        $duration = intval(trim($duration));
        return $duration;
    }



}