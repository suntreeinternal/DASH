<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 11/7/2018
 * Time: 9:44 AM
 */

session_start();
echo var_dump($_GET);
$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');

$groupValue = 0;

echo var_dump($_GET);
if ($_GET['message'] == ''){
    header($_SESSION['previous']);
    die;
} else {
    $query = 'INSERT INTO PatientPhoneMessages(PatientID, User_ID, TimeStamp, Message, UserGroup) values (\'' . $_SESSION['currentPatient'] . '\', \'' . $_SESSION['name'] . '\', \'' . date("Y-m-d h:i:sa") . '\', \'' . $_GET['message'] . '\',\'' . $_SESSION['group'] . '\')';
    $result = $conReferrals->query($query);
    header($_SESSION['previous']);
}