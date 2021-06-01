<?php

class ForbiddenWords
{
    private static $table = 'chat_forbiddenwords';


    public static function get($id, $webmasterid)
    {
        return DB::get($id, ForbiddenWords::$table, "and webmasterid=$webmasterid");
    }

    public static function update($id, $values, $webmasterid)
    {
        return DB::update(ForbiddenWords::$table, $id, $values, "and webmasterid=$webmasterid");
    }

    public static function insert($values, $debug = false)
    {
        $id = DB::insert(ForbiddenWords::$table, $values, $debug);
        return DB::getOneById($id, self::$table);
    }

    public static function delete($id, $webmasterid)
    {
        return DB::delete(ForbiddenWords::$table, $id, "and webmasterid=$webmasterid)");
    }

    public static function getAll($webmasterid)
    {
        return DB::select(ForbiddenWords::$table, array('`word`'), "WHERE webmasterid=$webmasterid");
    }
}

