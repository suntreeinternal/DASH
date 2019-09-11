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

//echo var_dump($_SESSION);

$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
if ($_GET['Print']) {
    $query = "INSERT INTO Referrals.Referrals (ProviderID, PatientID, Status, Priority, Reason, SpecaltyID, SpecalistID, CreatedBy, PatientContacted) VALUES ('" . $_GET['provider'] . "', '" . $_SESSION['currentPatient'] . "', '" . $_GET['status'] . "', '" . $_GET['priority'] . "', '" . str_replace("'", "\'", $_GET['Reason']) . "', '" . $_GET['Specialty'] . "', '" . $_GET['Specalist'] . "', '" . $_SESSION['name'] . "','1')";
} else {
    $query = "INSERT INTO Referrals.Referrals (ProviderID, PatientID, Status, Priority, Reason, SpecaltyID, SpecalistID, CreatedBy, PatientContacted) VALUES ('" . $_GET['provider'] . "', '" . $_SESSION['currentPatient'] . "', '" . $_GET['status'] . "', '" . $_GET['priority'] . "', '" . str_replace("'", "\'", $_GET['Reason']) . "', '" . $_GET['Specialty'] . "', '" . $_GET['Specalist'] . "', '" . $_SESSION['name'] . "','0')";
}

echo $query;

$result = $conReferrals->query($query);

$audit = new AuditLog();
$patientInfo = new Patient();
$patientInfo->SelectPatient($_SESSION['currentPatient']);


$string = $_SESSION['name'] . " has created a new referral for " . $patientInfo->GetLastName() . " for the following reason " . $_GET['Reason'];
$audit->SetChange($string);



if($_GET['Print']){
//    include ("/Referral/ReferralPrintOut.php");
    echo "<script type=\"text/javascript\">
        window.open('ReferralPrintOut.php?patient=" . $patientInfo->GetFullName() . "&specality=". $_GET['Specialty'] . "', '_blank');
        window.open('../" . substr($_SESSION['previous'], 10) . "', '_self');
    </script>";

} else {
    header($_SESSION['previous']);
}
