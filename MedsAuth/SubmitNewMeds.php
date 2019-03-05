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

//echo var_dump($_SESSION);
$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
$query = "INSERT INTO MedsAuth (PatientID, ProviderID, PharmacyName, PharmacyPhone, Status) VALUES ('" . $_SESSION['currentPatient'] . "', '" . $_GET['provider'] . "', '" . $_GET['Pharmacy'] ."', '0', '" . $_GET['status'] . "')";
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

//header($_SESSION['previous']);


