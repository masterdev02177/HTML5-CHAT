<?php

include_once(__DIR__.'/../classes/DB.php');
include_once(__DIR__.'/../classes/Mailing.php');
$mailing = Mailing::pickRandom();
if ($mailing) {
    Mailing::send($mailing);
    echo ("Sent to $mailing->email\r\n");
} else {
    exit("No emails in queue\r\n");
}
