<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 11/13/2018
 * Time: 10:04 AM
 */
//echo var_dump($_GET);
//TODO add to Change log
session_start();

include "../AuditLog.php";
include "../fetchPatientData/patientInfo.php";

echo var_dump($_SESSION);

$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
$query = "INSERT INTO Referrals.Referrals (ProviderID, PatientID, Status, Priority, Reason, SpecaltyID, SpecalistID) VALUES ('" . $_GET['provider'] .  "', '" . $_SESSION['currentPatient'] ."', '" . $_GET['status'] ."', '" . $_GET['priority'] . "', '" . $_GET['Reason'] . "', '" . $_GET['Specialty'] . "', '" . $_GET['Specalist'] . "')";
echo $query;

$result = $conReferrals->query($query);

$audit = new AuditLog();
$patientInfo = new Patient();
$patientInfo->SelectPatient($_SESSION['currentPatient']);


$string = $_SESSION['name'] . " has created a new referral for " . $patientInfo->GetLastName() . " for the following reason " . $_GET['Reason'];
$audit->SetChange($string);

header($_SESSION['previous']);

