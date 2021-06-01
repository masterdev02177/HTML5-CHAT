<?php
@require_once('DB.php');
//ini_set('display_errors', 1);
//error_reporting(E_ALL);




class Background {

    public static function getAll() {
        $dir = __DIR__.'/../backgrounds/*.jpg';



        foreach (glob($dir) as $image) {

            $image = basename($image);
            $res[] = array('image'=>"backgrounds/$image", 'thumb'=>"backgrounds/thumbs/$image");
        }
        return $res;
    }



}
