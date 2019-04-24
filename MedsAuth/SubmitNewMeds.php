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
echo var_dump($_SESSION);
$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
$query = "INSERT INTO MedsAuth (PatientID, ProviderID, PharmacyName, PharmacyPhone, Status) VALUES ('" . $_SESSION['currentPatient'] . "', '" . $_GET['provider'] . "', '" . str_replace("'", "\'",$_GET['Pharmacy']) ."', '" . $_GET['PhoneNum'] . "', '" . $_GET['status'] . "')";
$result = $conReferrals->query($query);

header($_SESSION['previous']);


