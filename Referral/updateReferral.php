<?php


/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 11/13/2018
 * Time: 10:04 AM
 */
//TODO add to Change log

session_start();

$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');

$query = "UPDATE Referrals.Referrals SET Reason='" . $_GET['Reason'] . "', SpecalistID=" . $_GET['Specalist'] . ", SpecaltyID=" . $_GET['Specialty'] . ", ProviderID=" . $_GET['provider'] . ", Status=" . $_GET['status'] . ", Priority=" . $_GET['priority'] . " WHERE ID=" . $_GET['refID'];
echo $query;

$result = $conReferrals->query($query);

header($_SESSION['previous']);
$conReferrals->close();
echo "<br/>";


