<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 1/11/2019
 * Time: 12:00 PM
 */

include ('fetchPatientData/patientInfo.php');

session_start();

$patientInfo = new Patient;
$patientInfo->SelectPatient($_SESSION['currentPatient']);

$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');

$groupValue = 0;



if ($_GET['message'] == ''){
    header($_SESSION['previous']);
    die;
} else {
    //TODO add to Change log
    if ($_GET['dest'] == '') {
        $query = 'INSERT INTO PatientPhoneMessages(PatientID, User_ID, Message, UserGroup, ParrentMessage) values (\'' . $_SESSION['currentPatient'] . '\', \'' . $_SESSION['name'] . '\', \'' . $_GET['message'] . '\',\'' . $_SESSION['group'] . '\', \'0\')';

    } else {
        $query = 'INSERT INTO PatientPhoneMessages(PatientID, User_ID, Message, UserGroup, AlertToGroup, ParrentMessage) values (\'' . $_SESSION['currentPatient'] . '\', \'' . $_SESSION['name'] . '\', \'' . $_GET['message'] . '\',\'' . $_SESSION['group'] . '\', \'' . $_GET['dest'] . '\', \'0\')';
    }
}
    echo $query;
    $result = $conReferrals->query($query);
    $_SESSION['previous'] = "location:/patientInfo/Patient.php?last=" . $patientInfo->GetLastName() . "&date=" . $patientInfo->GetDOB();
    header($_SESSION['previous']);
