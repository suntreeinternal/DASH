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
$query = "INSERT INTO Referrals.RecordRequest (PatientID, Requester, Status, Auth, CheckImg, ReferralImg, Reason) VALUES
          ('" . $_SESSION['currentPatient'] . "', '" . $_GET['requester'] . "', '" . $_GET['status'] . "', '" . $_GET['authorization'] . "', '" . "" . "', '" . "" . "', '" . $_GET['Reason'] . "')";

//$result = $conReferrals->query($query);
if (!$result = $conReferrals->query($query)) {
    // Oh no! The query failed.
    echo "Sorry, the website is experiencing problems.";

    // Again, do not do this on a public site, but we'll show you how
    // to get the error information
    echo "Error: Our query failed to execute and here is why: </br>";
    echo "Query: " . $query . "</br>";
    echo "Errno: " . $conReferrals->errno . "</br>";
    echo "Error: " . $conReferrals->error . "<br/>";
} else {
    header($_SESSION['previous']);
}
//$audit = new AuditLog;
//$string = "New record request created for " . $_SESSION[patientName] . " Requester is " . $_GET['requester'] . " Status " . $_GET['status'] . " Authorization " . $_GET['authorization'] . " Reason " . $_GET['Reason'];
//echo $string;
//$audit->SetChange($string);

//header($_SESSION['previous']);




