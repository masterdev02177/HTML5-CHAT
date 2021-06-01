<?php
class Gender {
	private static $table = 'chat_gender';
	
	
	public static function createDefaultGenders($webmasterid) {
		DB::insert(Gender::$table, array('gender'=>'male', 'image'=>'', 'color'=>'#0096ff', 'webmasterid'=>$webmasterid));
		DB::insert(Gender::$table, array('gender'=>'female', 'image'=>'', 'color'=>'#ff90de', 'webmasterid'=>$webmasterid));
		DB::insert(Gender::$table, array('gender'=>'couple', 'image'=>'', 'color'=>'#666666', 'webmasterid'=>$webmasterid));
	}
	
	public static function get($id, $webmasterid) {
		return DB::get($id, Gender::$table, "and webmasterid=$webmasterid order by id");
	}
	
	
	public static function update($id, $values, $webmasterid) {
		return DB::update(Gender::$table, $id, "and webmasterid=$webmasterid", $values);
	}
	public static function insert($values, $debug=false) {
		$id = DB::insert(Gender::$table, $values, $debug);
		return DB::getOneById($id);
	}
	
	public static function delete($id, $webmasterid) {
		return DB::delete(Gender::$table, $id, "and webmasterid=$webmasterid)");
	}

	public static function getAll($webmasterid) {
		return DB::select(Gender::$table, array('id', 'gender', 'image', 'color', 'canBroadcast', 'webcamAutoStart, showOnTopofUserList, mappedGender'), "WHERE webmasterid=$webmasterid order by id");
	}	
}
