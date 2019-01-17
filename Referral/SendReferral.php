<?php

/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 11/13/2018
 * Time: 10:04 AM
 */
//echo var_dump($_GET) . "<br\>";
//TODO add to Change log

$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
$query = "UPDATE Referrals.Referrals SET LastSent='" . date("Y-m-d H:i:s") . "' WHERE ID=" . $_GET['ReferralID'];
echo $query;
$result = $conReferrals->query($query);
header($_SESSION['previous']);

