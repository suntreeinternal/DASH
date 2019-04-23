<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 4/17/2019
 * Time: 2:02 PM
 */
session_start();
echo var_dump($_SESSION);

$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');

$query = "INSERT INTO Referrals.RecordNote (RecordID, UserID, Note, UserGroup) VALUES ('" . $_GET['typeID'] . "', '" . $_SESSION['user'] . "', '" . str_replace("'", "\'", $_GET['message']) . "', '" . $_SESSION['group'] . "' )";

$conReferrals->query($query);

if($conReferrals->error){
    echo 'ERROR: ' . $conReferrals->error;
} else {
    header($_SESSION['previous']);
}