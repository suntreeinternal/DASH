<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 1/11/2019
 * Time: 12:00 PM
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
    //TODO add to Change log
    if ($_GET['dest'] == ''){
        $query = 'INSERT INTO PatientPhoneMessages(PatientID, User_ID, TimeStamp, Message, UserGroup) values (\'' . $_SESSION['currentPatient'] . '\', \'' . $_SESSION['name'] . '\', \'' . date("Y-m-d h:i:sa") . '\', \'' . $_GET['message'] . '\',\'' . $_SESSION['group'] . '\')';

    } else {
        $query = 'INSERT INTO PatientPhoneMessages(PatientID, User_ID, TimeStamp, Message, UserGroup, AlertToGroup) values (\'' . $_SESSION['currentPatient'] . '\', \'' . $_SESSION['name'] . '\', \'' . date("Y-m-d h:i:sa") . '\', \'' . $_GET['message'] . '\',\'' . $_SESSION['group'] . '\', \'' . $_GET['dest'] . '\')';
    }
    $result = $conReferrals->query($query);
    header($_SESSION['previous']);
    echo $query;
}