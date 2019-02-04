<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 11/12/2018
 * Time: 3:28 PM
 */
session_start();
$temp = $_GET['button'];

switch ($temp){
    case 'New Referral':
        header("location:/Referral/Referral.php");
        die();

    case 'New Record':
        header("location:/RecordRequest/RecordRequest.php");
        die();

    case 'New Rx':
        header("location:/Rx/newRX.php");
}
echo var_dump($_GET) . "<br/>";
echo var_dump($_SESSION);