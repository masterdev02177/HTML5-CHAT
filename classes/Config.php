<?php
class Config {
	private static $table = 'chat_config';
	
	public function __construct() {		
		@require_once('DB.php');
	}

    public static function getCss($webmasterid) {
        return DB::getOne(self::$table,"WHERE webmasterid=$webmasterid");
    }

	public static function checkJSON($url) {
			$json = file_get_contents($url);
			json_decode($json);
			return (json_last_error() == JSON_ERROR_NONE);

	}

	public static function updateCss($css, $id, $webmasterid) 	{
		DB::update(self::$table, $id, array('css'=>$css),"AND webmasterid=$webmasterid");
	}


	public static function createDefaultConfig($webmasterid, $email, $langue = 'en') {
		$id = DB::insert(Config::$table, array('webmasterid'=>$webmasterid, 'fromEmail'=>$email, 'langue'=>$langue));
		return DB::getOneById($id, Config::$table);
	}
	
	public static function get($id, $webmasterid) {
		return DB::get($id, Config::$table, "and webmasterid=$webmasterid");
	}

	public static function getByWebmasterid($webmasterid) {
		return DB::getOne(Config::$table, "WHERE webmasterid=$webmasterid");
	}
	
	public static function update($id, $values, $webmasterid) {
		return DB::update(Config::$table, $id, "and webmasterid=$webmasterid", $values);
	}
	public static function insert($values, $debug=false) {
		$id = DB::insert(Config::$table, $values, $debug);
		return DB::getOneById($id);
	}
	
	public static function delete($id, $webmasterid) {
		return DB::delete(Config::$table, $id, "and webmasterid=$webmasterid)");
	}

	public static function getAll($webmasterid) {
		return DB::select(Config::$table, array('id', 'name', 'description', 'users', 'welcome', 'image', '`password`<>"" as isPasswordProtected'), "WHERE webmasterid=$webmasterid");
	}	
}
