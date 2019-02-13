<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 11/13/2018
 * Time: 10:04 AM
 */

include ("../AuditLog.php");

$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
$query = "INSERT INTO Referrals.RecordRequest (PatientID, Requester, Status, Auth, CheckImg, ReferralImg, Reason, Date) VALUES
          ('" . $_SESSION['currentPatient'] . "', '" . $_GET['requester'] . "', '" . $_GET['status'] . "', '" . $_GET['authorization'] . "', '" . "" . "', '" . "" . "', '" . $_GET['Reason'] . "','" . date("Y-m-d h:i:sa") . "')";

$result = $conReferrals->query($query);

$audit = new AuditLog;
$string = "New record request created for " . $_SESSION[patientName] . " Requester is " . $_GET['requester'] . " Status " . $_GET['status'] . " Authorization " . $_GET['authorization'] . " Reason " . $_GET['Reason'];
echo $string;
$audit->SetChange($string);

header($_SESSION['previous']);




