<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 11/7/2018
 * Time: 9:44 AM
 */

session_start();
$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');

$groupValue = 0;

switch ($_GET['button']){
    case 'Reception':
        $groupValue = 2;
        break;

    case 'MA':
        $groupValue = 5;
        break;

    case 'Referrals':
        $groupValue = 4;
        break;

    case 'Provider':
        $groupValue = 3;
        break;

    default:
        $query = 'UPDATE PatientData SET Message_alert_to_group="' . 0 .'" WHERE ID="' . $_SESSION['currentPatient'] . '"';
        $result = $conReferrals->query($query);
        header($_SESSION['previous']);
        die();
}

if ($_GET['message'] == ''){
    header($_SESSION['previous']);
    die;
} else {
    $query = 'UPDATE PatientData SET Message_alert_to_group="' . $groupValue . '" WHERE ID="' . $_SESSION['currentPatient'] . '"';
    $result = $conReferrals->query($query);
    $query = 'INSERT INTO MessageAboutPatient(PatientID, UserID, TimeStamp, Message, UserGroup) values (\'' . $_SESSION['currentPatient'] . '\', \'' . $_SESSION['name'] . '\', \'' . date("Y-m-d h:i:sa") . '\', \'' . $_GET['message'] . '\',\'' . $_SESSION['group'] . '\')';
    $result = $conReferrals->query($query);
    header($_SESSION['previous']);
}