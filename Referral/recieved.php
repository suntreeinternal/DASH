<?php

/**
 * Created by PhpStorm.
 * User: Zachary Paryzek
 * Date: 11/13/2018
 * Time: 10:04 AM
 */


include "../AuditLog.php";
include "../fetchPatientData/patientInfo.php";
session_start();


$audit = new AuditLog();
$getPatient = new Patient();


$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
//$query = "UPDATE Referrals.Referrals SET LastSent='" . date("Y-m-d H:i:s") . "' WHERE ID=" . $_GET['ReferralID'];
//$result = $conReferrals->query($query);

$query = "SELECT * FROM Referrals.Referrals WHERE ID=" . $_GET['ReferralID'];
$result = $conReferrals->query($query);
$row = $result->fetch_row();
//
$query = "INSERT INTO Referrals.Note (ReferralID, UserID, Note, UserGroup) VALUES (" . $_GET['ReferralID'] . ",'" . $_SESSION['name'] . "','Referral Received','" . $_SESSION['group'] . "')";
////echo $query;
$result = $conReferrals->query($query);
echo $conReferrals->error;


$getPatient->SelectPatient($row[2]);

$String = 'Referral received for ' . $getPatient->GetFullName();

$audit->SetChange($String);

header($_SESSION['previous']);

