<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 3/27/2019
 * Time: 8:53 AM
 */

session_start();

$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');

$query = 'UPDATE Referrals.MedsAuth SET ProviderID=\'' . $_GET['provider'] . '\', PharmacyName=\'' . $_GET['Pharmacy'] . '\', PharmacyPhone=\'' . $_GET['PhoneNum'] . '\', Status=\'' . $_GET['status'] . '\' WHERE ID=' . $_GET['typeID'];

$result = $conReferrals->query($query);
$conReferrals->close();

header($_SESSION['previous']);
