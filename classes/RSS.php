<?php
require_once __DIR__.'/DB.php';
//ini_set('display_errors', 1);error_reporting(E_ALL);

class RSS
{

    public static $table = 'chat_rss';
    public static $cachedMinutes = 60;

    public static function readRSS($url) {
        try {
            $res = array();
            if($flux = simplexml_load_file($url, "SimpleXMLElement", LIBXML_NOCDATA))
            {

                $donnee = $flux->channel;
                //Lecture des données
                foreach($donnee->item as $valeur) {
                    //print_r($valeur);exit;

                    //Affichages des données
                    $res[] = array(
                        'title'=>(string)$valeur->title,
                        'description'=>(string)$valeur->description,
                        'link'=>(string)$valeur->link,
                        'pubDate'=>(string)$valeur->pubDate,
                        //'enclosure'=>(string)$valeur->enclosure['url']
                    );

                }
            }
            return $res;
        }catch(Exception $e) {

        }
    }

    public static function getRSS($rss, $cacheMinutes = 60) {
        try {
            $now = date('Y-m-d H:i:s');

            if ($now > $rss->cacheDate) {
                $content =  self::readRSS($rss->url);
                $serialized = serialize($content);
                $newCacheDate = date('Y-m-d H:i:s', strtotime("+$cacheMinutes minute"));
                DB::update(self::$table, $rss->id, array('cached'=>$serialized, 'cacheDate'=>$newCacheDate));
                return $content;
            } else {
                $row = DB::getOne(self::$table,"WHERE id={$rss->id}");
                return unserialize($row->cached);
            }
        }catch (Exception $e) {

        }

    }
    private static function getHtmlContent($items, $dateFormat = 'd/m/Y à H:i') {
        $html = '';
        foreach($items as $item) {
            $title = $item['title'];
            $link = $item['link'];
            $description = $item['description'];
            $pubDate = strtotime($item['pubDate']);
            $pubDate = date($dateFormat, $pubDate);
            $html.="$pubDate <a href='$link' target='_blank'><strong>$title</strong></a> $description";
        }
        return $html;
    }

    public static function getRoomRSS($webmasterid, $roomid=0) {
        $roomidFilter = ($roomid)?" AND roomid=$roomid":'';
        $rsss = DB::getAll(self::$table, "WHERE webmasterid = $webmasterid AND enabled=1 $roomidFilter");
        $roomsRSS = array();
        foreach($rsss as $rss) {
            $flux = self::getRSS($rss, self::$cachedMinutes);
            if (!isset($roomsRSS[$rss->roomid])) {
                $roomsRSS[$rss->roomid]= self::getHtmlContent($flux, $rss->dateFormat);
            } else {
                $roomsRSS[$rss->roomid].= self::getHtmlContent($flux, $rss->dateFormat);
            }
        }
        return $roomsRSS;
    }
}