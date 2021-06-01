<?php
//ini_set('display_errors', 1);error_reporting(E_ALL);
class Services {
    public function __construct() {
        //require_once(__DIR__.'/../Config.php');
    }

    public static function fuleExists($file) {
        $file_headers = @get_headers($file);
        if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
            return false;
        }
        else {
            return true;
        }
    }

    public static function post($url, $data) {
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        echo $result;
    }

    public function getFlvDuration($file) {
        ob_start();
        passthru("ffprobe $file -show_format -v quiet | sed -n 's/duration=//p'");
        $duration = ob_get_contents();
        ob_end_clean();
        return $duration;
    }
    public static function  ReverseIPOctets($inputip){
        $ipoc = explode(".",$inputip);
        return $ipoc[3].".".$ipoc[2].".".$ipoc[1].".".$ipoc[0];
    }


    public static function IsTorExitPoint(){
        if (gethostbyname(self::ReverseIPOctets($_SERVER['REMOTE_ADDR']).".".$_SERVER['SERVER_PORT'].".". self::ReverseIPOctets($_SERVER['SERVER_ADDR']).".ip-port.exitlist.torproject.org")=="127.0.0.2") {
            return true;
        } else {
            return false;
        }
    }

    public static function checkProxy($ip){
        $contactEmail="someValidEmailAddress"; //you must change this to your own email address
        $timeout=5; //by default, wait no longer than 5 secs for a response
        $banOnProbability=0.99; //if getIPIntel returns a value higher than this, function returns true, set to 0.99 by default

        //init and set cURL options
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        //if you're using custom flags (like flags=m), change the URL below
        curl_setopt($ch, CURLOPT_URL, "http://check.getipintel.net/check.php?ip=$ip&contact=$contactEmail");
        $response=curl_exec($ch);
        curl_close($ch);
        if ($response > $banOnProbability) {
            return true;
        } else {
            if ($response < 0 || strcmp($response, "") == 0 ) {
                //The server returned an error, you might want to do something
                //like write to a log file or email yourself
                //This could be true due to an invalid input or you've exceeded
                //the number of allowed queries. Figure out why this is happening
                //because you aren't protected by the system anymore
                //Leaving this section blank is dangerous because you assume
                //that you're still protected, which is incorrect
                //and you might think GetIPIntel isn't accurate anymore
                //which is also incorrect.
                //failure to implement error handling is bad for the both of us

            }
            return false;
        }
    }


    public static function isJunkMail($mail) {
        $domains = array('pjjkp.com', 'ephemail.com', 'ephemail.org', 'ephemail.net', 'jetable.org', 'jetable.net', 'jetable.com', 'yopmail.com', 'haltospam.com', 'tempinbox.com', 'brefemail.com', '0-mail.com', 'link2mail.net', 'mailexpire.com', 'kasmail.com', 'spambox.info', 'mytrashmail.com', 'mailinator.com', 'dontreg.com', 'maileater.com', 'brefemail.com', 'yopmail.com', '0-mail.com', 'brefemail.com', 'ephemail.net', 'guerrillamail.com', 'guerrillamail.info', 'haltospam.com', 'iximail.com', 'jetable.net', 'jetable.org', 'kasmail.com', 'klassmaster.com', 'kleemail.com', 'link2mail.net', 'mailin8r.com', 'mailinator.com', 'mailinator.net', 'mailinator2.com', 'myamail.com', 'mytrashmail.com', 'nyms.net', 'shortmail.net', 'sogetthis.com', 'spambox.us', 'spamday.com', 'Spamfr.com', 'spamgourmet.com', 'spammotel.com', 'tempinbox.com', 'yopmail.com', 'yopmail.fr', 'guerrillamail.org', 'temporaryinbox.com', 'spamcorptastic.com', 'filzmail.com', 'lifebyfood.com', 'tempemail.net', 'spamfree24.org', 'spamfree24.com', 'spamfree24.net', 'spamfree24.de', 'spamfree24.eu', 'spamfree24.info', 'spamherelots.com', 'thisisnotmyrealemail.com', 'slopsbox.com', 'trashmail.net', 'myamail.com', 'tyldd.com', 'safetymail.info', 'brefmail.com', 'bofthew.com', 'trash-mail.com');

        list($user,$domain) = explode('@',$mail);
        return in_array($domain,$domains);

    }

    public static function getMyIp() {

        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public static function logjs($data) {
        $html = '';
        if(is_array($data) || is_object($data)) {
            $html = "<script>console.log('PHP: ".json_encode($data)."');</script>";
        } else {
            $html = "<script>console.log('PHP: ".$data."');</script>";
        }
        echo($html);
    }

    public static function log($log) {
        DB::insert('log', array('log'=>$log));
    }

    public static function createThumbnail($source, $target, $width = 128) {
        Images::createThumbnail($source,$target,$width);
        return;

        /*$finalWidth=$width."x".$width;
        $cmd = "convert  $source  -thumbnail -background black '$finalWidth>' $target";
        exec($cmd);
        return "$cmd";
        */
    }

    public static function createCroppedThumbnail($source, $target, $width, $height) {
        Images::createThumbnail($source,$target,$width, $height);
        return;

        /*$finalWidth=$width."x".$width;
        $cmd = "convert $source -background black -thumbnail $finalWidth^  -gravity center -extent $finalWidth  $target";
        exec($cmd);
        //echo $cmd;
        return "$cmd";
        */
    }


    public static function sendEmailWithTemplate($to, $subject, $from, $template="template.html")  {
        ob_start();
        include(__DIR__."/../email/$template");
        $body = ob_get_clean();
        self::sendEmailSMTP($to, $subject, $body, $from);

    }

    public static function getScreenshotFromUrl($base64) {
        //die($base64);
        $row = DB::getOne('chat_url', "WHERE base64='$base64'",false);
        if ($row) {
            if ($row->ready) {
                if (!$row->error) {
                    return HOME_HTTP.'upload/url/'.$row->base64.'.jpg';
                } else {
                    return HOME_HTTP.'img/error.svg';
                }

            } else {
                return false;
            }
        } else {
            $siteURL = base64_decode($base64);
            $filename = __DIR__."/../upload/url/$base64.jpg";
            //create a snapshot !
            //call Google PageSpeed Insights API
            $googlePagespeedData = file_get_contents("https://www.googleapis.com/pagespeedonline/v2/runPagespeed?url=$siteURL&screenshot=true");

            //decode json data
            $googlePagespeedData = json_decode($googlePagespeedData, true);

            //screenshot data
            $screenshot = ($googlePagespeedData['screenshot']['data']);
            if (!$screenshot) {
                DB::insert('chat_url',array('base64'=>"$base64", 'ready'=>1, 'error'=>1));
                return 'error';
            }
            $screenshot = str_replace(array('_','-'),array('/','+'),$screenshot);
            $screenshot = base64_decode($screenshot);
            file_put_contents($filename, $screenshot);
            DB::insert('chat_url',array('base64'=>"$base64", 'ready'=>1));
            return HOME_HTTP.'upload/url/'.$base64.'.jpg';
        }



        //$screenshot = str_replace(array('_','-'),array('/','+'),$screenshot);

        //display screenshot image
        //return "<img src=\"data:image/jpeg;base64,".$screenshot."\" />";

    }

    public static function sendEmailSMTP($to, $subject, $message, $from, $debug=false) {

        require_once(__DIR__.'/phpmailer/class.phpmailer.php');
        $mail = new PHPMailer(true);

        $mail->IsSMTP();
        $mail->SMTPDebug  = $debug;
        $mail->Host       = SMTPHost;
        $mail->SMTPDebug  = SMTPDebug;
        $mail->SMTPAuth   = SMTPAuth;
        $mail->Port       = SMTPPort;
        $mail->Username   = SMTPUsername;
        $mail->Password   = SMTPPassword;
        $mail->SMTPSecure = "none";

        $mail->IsHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->AddAddress($to, $to);
        $mail->SetFrom($from, $from);
        $mail->AddReplyTo($from, $from);

        $mail->Subject = $subject;
        $mail->MsgHTML($message);
        //$mail->Body = $message;
        //die($message);

        $res = $mail->Send();
        // die("SENT to:$to, $subject, $from $message");
        return "ok";
    }


    public static function sendEmail($to, $name, $subject, $body, $from, $debug=false) {

        $headers = "From: $from\r\n";
        $headers.= "MIME-Version: 1.0\r\n";
        $headers.= "Content-Type: text/html; charset=utf-8\r\n";
        $headers.= "X-Priority: 1\r\n";

        if (USESMTP) {
            Services::sendEmailSMTP($to, $subject, $body, $from, $debug);
        } else {
            Services::sendEmailSMTP($to, $subject, $body, $from, $debug);
            //mail($to, $subject, $body, $headers);
        }
    }

}
