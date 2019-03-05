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
if ($_GET['message'] == ''){
    header($_SESSION['previous']);
    die;
} else {
    $query = 'INSERT INTO Note(ReferralID, UserID, Note, UserGroup) values (\'' . $_GET['refNum'] . '\', \'' . $_SESSION['name'] . '\', \'' . $_GET['message'] . '\',\'' . $_SESSION['group'] . '\')';
    $result = $conReferrals->query($query);
    header($_SESSION['previous']);
}

echo $query;