<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 2/4/2019
 * Time: 8:47 AM
 */

session_start();
echo var_dump($_SESSION) . '<br><br>';
echo var_dump($_GET);

$con = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');

$query = 'INSERT INTO Referrals.Users(GroupID, RightsID, UserName, FirstName, LastName, Password) VALUES ("' . $_GET['group'] . '", "' . $_GET['rights'] . '", "' . $_GET['user'] . '", "' . $_GET['fist'] . '", "' . $_GET['last'] . '", "' . $_GET['password'] . '")';
$result = $con->query($query);



header('location:../Admin/Admin.php');