<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 3/20/2019
 * Time: 2:36 PM
 */
session_start();
include "../../AuditLog.php";

$audit = new AuditLog();

echo var_dump($_SESSION);
$con = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
if($con->connect_error){
    header('location:/index.html');
}

$foo = $_GET['Speciality'];

$foobar = ucwords($foo);

$query = "INSERT INTO Referrals.Specialty(Specialty) VALUES ('" . $foobar . "')";
$result = $con->query($query);

$auditString = $_SESSION['name'] . " Added a new speciality, " . $foobar;
$audit->SetChange($auditString);

header("location:/Admin/Admin.php");

