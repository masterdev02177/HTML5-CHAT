<?php
@require_once('DB.php');
//ini_set('display_errors', 1);
//error_reporting(E_ALL);




class Banner
{

    public static function getAll()
    {
        $dir = __DIR__ . '/../bannerImg/*.jpg';


        if (empty(glob($dir))) {
            return;
        } else {

            foreach (glob($dir) as $image) {

                $image = basename($image);
                $res[] = array('image' => "$image", 'thumb' => "bannerImg/thumbs/$image");
            }
            return $res;
        }
    }
}
