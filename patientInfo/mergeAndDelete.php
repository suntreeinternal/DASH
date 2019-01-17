<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 11/16/2018
 * Time: 12:48 PM
 */
echo var_dump($_GET);
$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
$query = 'SELECT * FROM Referrals.PatientData WHERE SW_ID=\''. $_GET['id'] . '\'';
$result = $conReferrals->query($query);
$row = $result->fetch_row();
$id = $row[0];

$query = "UPDATE Referrals.PatientData SET SW_ID='" . $_GET['sw'] . "' WHERE ID='" . $id ."'";
$result = $conReferrals->query($query);

$query = 'SELECT * FROM Referrals.TempPatient WHERE ID=\''. $_GET['id'] . '\'';
$result = $conReferrals->query($query);
$row = $result->fetch_row();
$name = $row[1] . " " . $row[2];


$query = 'DELETE FROM Referrals.TempPatient WHERE ID=\''. $_GET['id'] . '\'';
echo "<br/>" . $query;
$result = $conReferrals->query($query);

$query = "INSERT INTO Referrals.ChangeLog (UserID, ChangeSummery, DateTime) VALUES ('" . $_SESSION['userID'] . "', 'Temporary patient merged with patient in SW " . $name . "', '" . date("Y-m-d h:i:sa") . "')";

$result = $conReferrals->query($query);
header($_SESSION['previous']);




