<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 1/11/2019
 * Time: 10:19 AM
 */

session_start();

$con = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
if($con->connect_error){
    header('location:/index.html');
} else {
    $query = 'SELECT * FROM Referrals.Users WHERE UserName="' . $_GET['user'] .'"';
}

echo var_dump($_POST);

$query = "INSERT INTO Referrals.Provider (ProviderName, Initals, Active, Color, Text) VALUES ('" . $_POST['name'] . "', '" . $_POST['initials'] . "', '1', '" . $_POST['Color'] . "', '000000')";
$result = $con->query($query);
$query = "INSERT INTO Referrals.ChangeLog (UserID, ChangeSummery, DateTime) VALUES ('" . $_SESSION['userID'] . "', 'Added new provider to practice: " . $_POST['name'] . "', '" . date("Y-m-d h:i:sa") . "')";
$result = $con->query($query);

//echo $query;