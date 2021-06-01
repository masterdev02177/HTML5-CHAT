<?php
$a = $_REQUEST['a'];
switch($a) {
    case  'getJson':
        echo Image::getJson($_REQUEST['keyword']);
        break;

    case  'getJsonForCKE':
        echo Image::getJsonForCKE($_REQUEST['keyword']);
        break;

    case 'upload':
        $src = $_REQUEST['src'];
        echo Image::upload($src);
        break;
}

class Image {

    public static function getJson($keyword, $per_page = 20)  {
        $api = 'c7868f1418d483d0e3a7146e41fc590d';
        $search = urlencode($keyword);
        $url = "http://flickr.com/services/rest/?method=flickr.photos.search&api_key=$api&text=$search&per_page=$per_page&format=php_serial";
        $file_contents = file_get_contents($url);
        $rsp_obj = unserialize($file_contents);
        $results = $rsp_obj['photos']['photo'];
        $photos = array();


        foreach ($results as $result) {
            $thumb = 'https://farm' . $result['farm'] . '.static.flickr.com/' . $result['server'] . '/' . $result['id'] . '_' . $result['secret'] . '_t.jpg';
            $image = 'https://farm' . $result['farm'] . '.static.flickr.com/' . $result['server'] . '/' . $result['id'] . '_' . $result['secret'] . '.jpg';
            $photo = Array('thumb'=>$thumb, 'image'=>$image, 'url'=>$thumb);
            $photos[] = $photo;
        }

        return json_encode($photos);
    }

    public static function getJsonForCKE($keyword, $per_page = 20)  {
        $api = 'c7868f1418d483d0e3a7146e41fc590d';
        $search = urlencode($keyword);
        $url = "http://flickr.com/services/rest/?method=flickr.photos.search&api_key=$api&text=$search&per_page=$per_page&format=php_serial";
        $file_contents = file_get_contents($url);
        $rsp_obj = unserialize($file_contents);
        $results = $rsp_obj['photos']['photo'];
        $photos = array();

        foreach ($results as $result) {
            $thumb = 'https://farm' . $result['farm'] . '.static.flickr.com/' . $result['server'] . '/' . $result['id'] . '_' . $result['secret'] . '_t.jpg';
            $photo = Array('url'=>$thumb);
            $photos[] = $photo;
        }

        return json_encode($photos);
    }

    public static function upload($src, $filename, $path='') {
        if (!$path) {
            $path = __DIR__.'/../upload/';
        }
        if (!$filename) {
            $filename = uniqid('image');
        }
        $fullPathDestination = $path.$filename;
        file_put_contents($fullPathDestination, file_get_contents($src));
        return $filename;
    }

}
//echo Image::getJson('Jacques Chirac', 20);
//echo Image::getJsonForCKE('Jacques Chirac');