<?php
@session_start();

error_reporting(E_ALL);
ini_set('display_errors', 'On');

if( isset($_GET["id"]) && !empty($_GET["id"]) && is_numeric($_GET["id"]) && isset($_GET["token"]) && !empty($_GET["token"]) )
{
	include 'Config.php';
	include_once 'classes/DB.php';
	include_once 'classes/User.php';
	include_once 'classes/Services.php';

	$webmasterid=1;
	$config = DB::getOne('chat_config', "WHERE webmasterid=$webmasterid");
	$traductions = DB::getOne('chat_lang', "WHERE webmasterid=$webmasterid", false);
	if (!$traductions) {
		$filename = 'lang/' . $config->langue . '.json';
		$fileJson = file_get_contents($filename);
		$traductions = json_decode($fileJson, true);
	} else {
		$traductions = (array)$traductions;
		
		$filename = 'lang/' . $config->langue . '.json';
		$fileJson = file_get_contents($filename);
		$traductionsBase = json_decode($fileJson, true);
		
		$new_array=array();
		foreach($traductionsBase as $k=>$v){ $new_array[$k]=$v; }
		foreach($traductions as $k=>$v){ $new_array[$k]=$v; }
		$traductions=$new_array;     
	}	

	$mess="";
	
	$uid = DB::real_escape_string($_GET['id']);
	$tk = DB::real_escape_string($_GET['token']);
	$row = DB::getOne('chat_users', "WHERE id='$uid' AND token='$tk'");
	if($row){				
		$cf=$row->confirmed;
		if($cf==0){
			$sql = "update chat_users set confirmed='1' WHERE id='$uid' AND token='$tk'";
			DB::execSQL($sql);
			$mess=$traductions['confirmUserOK'];
		}
		if($cf==1){
			$mess=$traductions['confirmUserOKYET'];
		}
	}else{
		$mess=$traductions['confirmUserNOTFOUND'];
	}
	if($mess!=""){
		echo "<script>alert('".addslashes($mess)."'); window.location = '".HOME_HTTP."';</script>";		
	}
}else{ header('Location:/'); exit; }
?>