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
        //TODO add to Change log

    $query = 'UPDATE PatientData SET Message_alert_to_group="' . 0 .'" WHERE ID="' . $_SESSION['currentPatient'] . '"';
        $result = $conReferrals->query($query);
        header($_SESSION['previous']);
        die();
}

if ($_GET['message'] == ''){
    header($_SESSION['previous']);
    die;
} else {
    //TODO add to Change log

//    $query = 'UPDATE PatientData SET Message_alert_to_group="' . $groupValue . '" WHERE ID="' . $_SESSION['currentPatient'] . '"';
//    $result = $conReferrals->query($query);
    $query = 'INSERT INTO MessageAboutPatient(PatientID, UserID, Message, UserGroup) values (\'' . $_SESSION['currentPatient'] . '\', \'' . $_SESSION['name'] . '\', \'' . $_GET['message'] . '\',\'' . $_SESSION['group'] . '\')';
//    $result = $conReferrals->query($query);
    if (!$result = $conReferrals->query($query)) {
        // Oh no! The query failed.
        echo "Sorry, the website is experiencing problems.";

        // Again, do not do this on a public site, but we'll show you how
        // to get the error information
        echo "Error: Our query failed to execute and here is why: </br>";
        echo "Query: " . $query . "</br>";
        echo "Errno: " . $conReferrals->errno . "</br>";
        echo "Error: " . $conReferrals->error . "<br/>";
    } else {
        header($_SESSION['previous']);
    }
}