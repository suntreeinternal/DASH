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

$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
$query = "INSERT INTO Referrals.Referrals(ProviderID, PatientID, Status, Priority, Authorization, Reason, SpecaltyID, SpecalistID)
 VALUES ('" . $_GET['provider'] .  "', '" . $_SESSION['currentPatient'] ."', '" . $_GET['status'] ."', '" . $_GET['priority']
    . "', '" . $_GET['authorization'] ."', '" . $_GET['Reason'] . "', '" . $_GET['Specialty'] . "', '0')";

echo var_dump($_SESSION);

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

//$result = $conReferrals->query($query);
//header($_SESSION['previous']);

echo "<br/>";

//echo $_SESSION['previous'];

