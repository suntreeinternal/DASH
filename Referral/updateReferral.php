<?php

//TODO update a current referral

/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 11/13/2018
 * Time: 10:04 AM
 */
//echo var_dump($_GET) . "<br\>";
$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
$query = "UPDATE Referrals.Referrals SET SpecaltyID='" . $_GET['Specialty'] . "', ProviderID='" . $_GET['provider'] . "', Status='" . $_GET['status'] . "', Priority='" . $_GET['priority'] . "', Authorization='" . $_GET['authorization'] . "' WHERE ID='" . $_GET['refID'] ."'";
echo $query;
$result = $conReferrals->query($query);
header($_SESSION['previous']);

echo "<br/>";

//echo $_SESSION['previous'];

