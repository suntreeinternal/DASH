<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 3/25/2019
 * Time: 2:34 PM
 */

session_start();

$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
$query = "UPDATE  Referrals.RecordRequest SET Requester=" . $_GET['Requester'] . ", Status=" . $_GET['selected'] . ", Reason='" . $_GET['Reason'] . "', Auth=" .$_GET['authorization'] . " WHERE ID=" . $_GET['ID'];
$result = $conReferrals->query($query);
echo $query;

include '../fetchPatientData/patientInfo.php';


header($_SESSION['previous']);
