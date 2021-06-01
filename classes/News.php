<?php
@require_once 'DB.php';
class News {
	private static $table = 'chat_news';

		public static function getAll($webmasterid, $debug=false) {
			$news =  DB::select(self::$table, array('id', 'news', 'startHour', 'endHour', 'isPopup', 'display_news_minutes'), "WHERE webmasterid=$webmasterid", $debug);
            return $news;
		}
	public static function getAllActive($webmasterid) {
		$news =  DB::select(self::$table, array('id', 'news', 'startHour', 'endHour', 'isPopup', 'display_news_minutes'), "WHERE webmasterid=$webmasterid and active=1", false);
		return $news;
	}
}