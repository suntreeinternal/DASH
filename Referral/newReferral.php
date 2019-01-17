<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 11/13/2018
 * Time: 10:04 AM
 */
//echo var_dump($_GET);
//TODO add to Change log

$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
$query = "INSERT INTO Referrals.Referrals (ProviderID, PatientID, Status, Priority, Authorization, Reason, Date, SpecaltyID, SpecalistID) VALUES ('" . $_GET['provider'] .  "', '" . $_SESSION['currentPatient'] ."', '" . $_GET['status'] ."', '" . $_GET['priority'] . "', '" . $_GET['authorization'] ."', '" . $_GET['Reason'] . "', '" . $_GET['dateTime'] ."', '" . $_GET['Specialty'] . "', '0')";
$result = $conReferrals->query($query);
header($_SESSION['previous']);

echo "<br/>";

//echo $_SESSION['previous'];

