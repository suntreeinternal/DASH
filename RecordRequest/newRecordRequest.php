<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 11/13/2018
 * Time: 10:04 AM
 */

include ("../AuditLog.php");
session_start();

$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
$query = "INSERT INTO Referrals.RecordRequest (PatientID, Requester, Status, Auth, Reason) VALUES
          ('" . $_SESSION['currentPatient'] . "', '" . $_GET['requester'] . "', '" . $_GET['status'] . "', '" . $_GET['authorization'] . "', '" . str_replace("'", "\'", $_GET['Reason']) . "')";

$result = $conReferrals->query($query);
echo $conReferrals->error;
$audit = new AuditLog;
$string = "New record request created for " . $_SESSION[patientName] . " Requester is " . $_GET['requester'] . " Status " . $_GET['status'] . " Authorization " . $_GET['authorization'] . " Reason " . str_replace("'", "\'", $_GET['Reason']);
echo $string;
$audit->SetChange($string);

header($_SESSION['previous']);




