<?php

require 'vendor/autoload.php';
use \Firebase\JWT\JWT;
if (count($args)!=1) {
    echo "<pre>";
    print_r($args);
    echo "</pre>";
    exit("Bad count parameter:".count($args));
}
//print_r($args);
try{
    $jsonString = base64_decode($args[0]);
    $json = json_decode($jsonString);
    $password = $json->password;
    $json->password = md5($password);
    //$jsonString = json_encode($json);
    //$jwt = JWT::encode($jsonString, $json->password);

    $jwt = JWT::encode($json, $password);
    exit($jwt);

    // tester le JWT ici

    $decoded = json_decode(JWT::decode($jwt, $password, array('HS256')));
    $myuser = (array)$decoded;

    $myuser['isAdmin'] = ($myuser['role']=='admin');
    $myuser['jwt'] = $jwt;
    print_r($myuser);

}
catch (Exception $e) {
    exit('Bad JSON');
}

