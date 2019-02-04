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

$query = 'UPDATE Referrals.Users SET Password="' . $_GET['password'] .'", GroupID="' . $_GET['group'] . '", RightsID="' . $_GET['rights'] . '"  WHERE Referrals.Users.UserName="' . $_GET['user'] . '"';
$result = $con->query($query);



header('location:../Admin/Admin.php');