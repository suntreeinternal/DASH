<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 3/25/2019
 * Time: 2:34 PM
 */

session_start();

$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
$query = "UPDATE  Referrals.RecordRequest SET Requester=" . $_GET['Requester'] . ", Status=" . $_GET['selected'] . ", Reason='" . str_replace("'", "\'", $_GET['Reason']) . "', LastMD='" . str_replace("'", "\'", $_GET['MD']) . "', LastProvider='" . str_replace("'", "\'", $_GET['provider']) . "', Auth=" .$_GET['authorization'] . " WHERE ID=" . $_GET['ID'];
$result = $conReferrals->query($query);
echo $query;
if ($conReferrals->error){
    echo $conReferrals->error;
} else {
    header($_SESSION['previous']);
}

//include '../fetchPatientData/patientInfo.php';


