<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 11/13/2018
 * Time: 10:04 AM
 */
//echo var_dump($_GET);
//TODO add to Change log

echo var_dump($_SESSION);
$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
$query = "INSERT INTO MedsAuth (PatientID, ProviderID, DateCreated, PharmacyName, PharmacyPhone, Status) VALUES ('" . $_SESSION['currentPatient'] . "', '" . $_GET['provider'] . "', '" . $_GET['dateTime'] . "', '" . $_GET['Pharmacy'] ."', '" . $_GET['PhoneNum'] . "', '" . $_GET['status'] . "')";
$result = $conReferrals->query($query);
header($_SESSION['previous']);


