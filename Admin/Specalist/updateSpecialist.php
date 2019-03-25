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
if ($_GET['delete'] == true){
    $query = "DELETE FROM Referrals.Specialist WHERE ID=" . $_GET['id'];
    $result = $con->query($query);

    $auditString = $_SESSION['name'] . " Deleted specialist, " . $foobar;
    $audit->SetChange($auditString);

} else {
    $query = "UPDATE Referrals.Specialist SET Location='" . $_GET['address'] . "', SpecialtyID='" . $_GET['Speciality'] . "', DrName='" . $_GET['DrName'] . "', Phone='" . $_GET['phone'] . "', Fax='" . $_GET['fax'] . "', Note='" . $_GET['note'] . "' WHERE ID=" . $_GET['id'];
    echo $query;
    $result = $con->query($query);


    $auditString = $_SESSION['name'] . " updated a specialist, " . $foobar;
    $audit->SetChange($auditString);
}

header("location:/Admin/Admin.php");

