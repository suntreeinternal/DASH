<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 1/17/2019
 * Time: 7:43 AM
 */

session_start();

$ReferralId = $_GET['ReferralID'];

$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
$query = "SELECT * FROM Referrals.Referrals WHERE ID='" . $ReferralId . "'";
$result = $conReferrals->query($query);
$row = $result->fetch_row();

$query = "SELECT * FROM Referrals.PatientData WHERE ID='" . $row[2] . "'";
$result = $conReferrals->query($query);
$row = $result->fetch_row();
$_SESSION['swID'] = $row[1];
$_SESSION['currentPatient'] = $row[0];

if (strpos($row[1], '-') !== false){
    $con = mssql_connect('sunserver', 'siminternal', 'Watergate2015');
    if (!mssql_select_db('sw_charts', $con)) {
        die('Unable to select database! ');
    }
    $query = "SELECT * FROM dbo.Gen_Demo WHERE Patient_ID='" . $row[1] . "'";
    $result = mssql_query($query);
    $row = mssql_fetch_array($result);
    $_SESSION['patientName'] = $row[2] . " " . $row[1];
    $_SESSION['patientDOB'] = $row[21];

} else {
    $query = "SELECT * FROM Referrals.TempPatient WHERE ID='" . $row[1] . "'";
    $result = $conReferrals->query($query);
    $row = $result->fetch_row();
    $_SESSION['patientName'] = $row[1] . " " . $row[2];
    $_SESSION['patientDOB'] = $row[3];
}

header("location:../Referral/CurrentReferral.php?ReferralID=" . $ReferralId);
