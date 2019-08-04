<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 1/16/2019
 * Time: 8:37 AM
 */
session_start();

if (sizeof($_SESSION) == 0){
    header('location:../index.html');
}

$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
$query = "SELECT * FROM Referrals.Specialist WHERE SpecialtyID=" .$_GET['specality'];
$result = $conReferrals->query($query);
echo '<option value="-1">' . '</option>';
while ($row = $result->fetch_row()){
    echo '<option value="'. $row[0] .'">'. $row[2] . '</option>';
}