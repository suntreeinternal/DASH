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

//echo var_dump($_GET);
$con = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
if($con->connect_error){
    header('location:/index.html');
}

$foo = $_GET['DrName'];

$foobar = ucwords($foo);

$query = "INSERT INTO Referrals.Specialist(SpecialtyID, DrName, Location, Phone, Fax, Note) VALUES ('". $_GET['Speciality'] . "', '" . $foobar . "', '" . $_GET['address'] . "', '" .
    $_GET['phone'] . "' ,'" . $_GET['fax'] . "', '" . $_GET['note'] ."')";
echo $query;
$result = $con->query($query);



$auditString = $_SESSION['name'] . " Added a new specialist, " . $foobar;
$audit->SetChange($auditString);

header("location:/Admin/Admin.php");

