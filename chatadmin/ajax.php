<?php
ini_set('display_errors', 1);error_reporting(E_ALL);
session_start();
include_once '../classes/DB.php';
if (!isset($_REQUEST['a'])) {
    exit("error");
}
switch($_REQUEST['a']) {
    case 'setTemplate':
        $webmasterid = DB::real_escape_string($_SESSION['admin']);
        if (!$webmasterid) {
            exit("Missing webmasterid");
        }

        $templateid = $_POST['templateid'];
        $model = DB::getOne('chat_template', "WHERE id=$templateid");

        $sql = "SELECT * from chat_config WHERE webmasterid = {$model->webmasterid}";
        $configModel = DB::selectOneBySQLAssociativeArray($sql);
        $configModel['webmasterid'] = $webmasterid;
        unset ($configModel['id']);


        DB::updateWhere('chat_config', "WHERE webmasterid=$webmasterid", $configModel, false);

        //print_r($configModel);exit();

        // clear roles !
        $sql = "SELECT * FROM chat_roles WHERE webmasterid = {$model->webmasterid}";
        $roles = DB::selectAllBySQLAssociativeArray2($sql);
        foreach ($roles as $role):
            $role['webmasterid'] = $webmasterid;
            unset ($role['id']);
            $roleLabel = $role['role'];
            DB::updateWhere('chat_roles', "WHERE role='$roleLabel' AND webmasterid=$webmasterid", $role, false);
        endforeach;
        break;

    case 'resetConfig':
        $webmasterid = DB::real_escape_string($_SESSION['admin']);
        if (!$webmasterid) {
            exit("Error, Missing webmasterid.");
        }
        $model = DB::getOne('chat_template', "WHERE type='tabAndWindow'");
        $modelid = $model->id;
        $sql = "SELECT * from chat_config WHERE id=$modelid";
        $configModel = DB::selectOneBySQLAssociativeArray($sql);
        $configModel['webmasterid'] = $webmasterid;
        unset ($configModel['id']);
        $myConfig = DB::getOne('chat_config', "WHERE webmasterid=$webmasterid");
        if (!$myConfig) {
            exit("error2");
        }
        //print_r($configModel);

        DB::update('chat_config', $myConfig->id, $configModel, '', true);
        break;

    case 'resetCss':
        $webmasterid = $_SESSION['admin'];
        DB::update2('chat_config', array('css'=>''),"WHERE webmasterid=$webmasterid");
        break;

    case 'css':
        $css = $_POST['css'];
        $webmasterid = $_SESSION['admin'];
        $config = DB::getOne('chat_config', "where webmasterid=$webmasterid");
        $oldCss = $config->css;
        $css = $oldCss.$css;
        DB::update2('chat_config', array('css'=>$css),"WHERE webmasterid=$webmasterid",true);
        break;
    case 'setTranslation':
        $sql = "
            SELECT `COLUMN_NAME`
            FROM `INFORMATION_SCHEMA`.`COLUMNS`
            WHERE `TABLE_SCHEMA`='chat'
            AND `TABLE_NAME`='chat_lang';
    ";
        $res = DB::selectAllBySQL($sql);
        $fields = [];
        $arr = [];
        foreach ($res as $row) {
            $fields[] = $row->COLUMN_NAME;
        }
        //print_r($fields);

        $lang = $_REQUEST['lang'];
        $jsons = file_get_contents("../lang/$lang.json");
        $jsons = json_decode($jsons, true);
        $webmasterid = $_SESSION['admin'];
        //print_r($jsons);
        //exit($jsons["camOn"]);
        foreach ($fields as $index => $value) {
            if (array_key_exists($value, $jsons)) {
                //echo $fields[$index] .' = '. $jsons[$value]."\r\n";
                $arr[$fields[$index]] = $jsons[$value];
            }
        }

        DB::update2('chat_lang', $arr, "WHERE webmasterid=$webmasterid", true);
        break;
}