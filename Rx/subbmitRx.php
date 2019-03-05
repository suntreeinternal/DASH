<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 11/30/2018
 * Time: 1:26 PM
 */
//echo var_dump($_GET);
//TODO add to Change log
session_start();
include ("../AuditLog.php");

//$test = "INSERT INTO Referrals.Rx(PatientID, Status, ProviderID, Note, Authorization, presc1, mg1, quan1, dir1, dir21, presc2, mg2, quan2, dir2, dir22, presc3, mg3, quan3, dir3, dir23, presc4, mg4, quan4, dir4, dir24, presc5, mg5, quan5, dir5, dir25, Reason) VALUES ";

$query = "INSERT INTO Referrals.Rx(PatientID, Status, ProviderID, Note, Authorization, presc1, mg1, quan1, dir1, dir21, presc2, mg2, quan2, dir2, dir22, presc3, mg3, quan3, dir3, dir23, presc4, mg4, quan4, dir4, dir24, presc5, mg5, quan5, dir5, dir25, Reason) VALUES
 ('" . $_GET['patientID'] . "', '" . $_GET['status'] . "', '" . $_GET['provider'] . "', '" . $_GET['Note'] . "', '" . $_GET['authorization'] . "', '" .
    $_GET['prescription1'] . "', '" . $_GET['mg1'] . "', '" . $_GET['Quantity1'] . "', '" . $_GET['Dir1'] . "', '" . $_GET['Dir21'] . "', '" .
    $_GET['prescription2'] . "', '" . $_GET['mg2'] . "', '" . $_GET['Quantity2'] . "', '" . $_GET['Dir2'] . "', '" . $_GET['Dir22'] . "', '" .
    $_GET['prescription3'] . "', '" . $_GET['mg3'] . "', '" . $_GET['Quantity3'] . "', '" . $_GET['Dir3'] . "', '" . $_GET['Dir23'] ."', '" .
    $_GET['prescription4'] . "', '" . $_GET['mg4'] . "', '" . $_GET['Quantity4'] . "', '" . $_GET['Dir4'] . "', '" . $_GET['Dir24'] ."', '" .
    $_GET['prescription5'] . "', '" . $_GET['mg5'] . "', '" . $_GET['Quantity5'] . "', '" . $_GET['Dir5'] . "', '" . $_GET['Dir25'] ."', '" . $_GET['Reason'] .
    "')";


$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
//$result = $con->query($query);

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

//TODO fix adding to audit log

//$audit = new AuditLog;
//$audit->SetChange($query);

//echo var_dump($result->fetch_row());
header($_SESSION['previous']);
