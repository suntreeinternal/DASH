<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 11/7/2018
 * Time: 9:44 AM
 */

session_start();
//echo var_dump($_GET);
$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');

$groupValue = 0;

//echo var_dump($_GET);
//echo var_dump($_SESSION);
if ($_GET['message'] == ''){
    header($_SESSION['previous']);
    die;
} else {
    $query = "INSERT INTO Referrals.MedsNotes(RecordID, UserID, Note, UserGroup) VALUES ('". $_GET['typeID'] . "', '" . $_SESSION['user'] ."', '" . str_replace("'", "\'",$_GET['message']) . "', '" . $_SESSION['group'] . "')";
    $conReferrals->query($query);
    if ($conReferrals->error){
        echo $conReferrals->error;
    }
    header($_SESSION['previous']);
}

//echo $query;