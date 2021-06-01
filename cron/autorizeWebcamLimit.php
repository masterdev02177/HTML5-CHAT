<?php
include_once(__DIR__.'/../classes/DB.php');

$sql = "TRUNCATE chat_users_videoTimeSpent";
DB::execSQL($sql);