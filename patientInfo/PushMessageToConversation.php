<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 3/21/2019
 * Time: 3:42 PM
 */
session_start();
include "../AuditLog.php";
$audit = new AuditLog();

//echo var_dump($_SESSION);
var_dump($_GET);

$con = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
if($con->connect_error){
    header('location:/index.html');
}

$toSend = str_replace("'", "\'",$_GET['message']);
$toSend = str_replace('"', '\"', $toSend);
if ($_GET['dest'] == 3){
    if ($_GET['message'] == '') {
        $query = "INSERT INTO Referrals.PatientPhoneMessages(PatientID, User_ID, Message, UserGroup, AlertToGroup, ParrentMessage) VALUES ('" . $_SESSION['currentPatient'] . "', '" . $_SESSION['name'] .
            "', 'Left Voice Mail', '" . $_SESSION['group'] . "', '" . $_GET['dest'] . "', '" . $_GET['parent'] . "')";
    } else {
        $query = "INSERT INTO Referrals.PatientPhoneMessages(PatientID, User_ID, Message, UserGroup, AlertToGroup, ParrentMessage) VALUES ('" . $_SESSION['currentPatient'] . "', '" . $_SESSION['name'] .
            "', '" . $toSend . "', '" . $_SESSION['group'] . "', '" . $_GET['dest'] . "', '" . $_GET['parent'] . "')";
    }
} elseif ($_GET['dest'] == ''){
    echo 'adsf';
    $query = "INSERT INTO Referrals.PatientPhoneMessages(PatientID, User_ID, Message, UserGroup, AlertToGroup, ParrentMessage) VALUES ('" . $_SESSION['currentPatient'] . "', '" . $_SESSION['name'] .
        "', '" . $toSend . "', '" . $_SESSION['group'] . "', NULL, '" . $_GET['parent'] . "')";
} else {
    $query = "INSERT INTO Referrals.PatientPhoneMessages(PatientID, User_ID, Message, UserGroup, AlertToGroup, ParrentMessage) VALUES ('" . $_SESSION['currentPatient'] . "', '" . $_SESSION['name'] .
        "', '" . $toSend . "', '" . $_SESSION['group'] . "', '" . $_GET['dest'] ."', '" . $_GET['parent'] . "')";
}
$result = $con->query($query);
echo $con->error;
$con->close();

header($_SESSION['previous']);
