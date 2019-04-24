<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 11/30/2018
 * Time: 1:26 PM
 */
//echo var_dump($_GET);
//TODO add to Change log
include ("../AuditLog.php");
session_start();
$query = "INSERT INTO Referrals.Rx(PatientID, Status, ProviderID, Note, Authorization, presc1, mg1, quan1, dir1, dir21, presc2, mg2, quan2, dir2, dir22, presc3, mg3, quan3, dir3, dir23, presc4, mg4, quan4, dir4, dir24, presc5, mg5, quan5, dir5, dir25, Reason) VALUES
 ('" . $_GET['patientID'] . "', '" . $_GET['status'] . "', '" . $_GET['provider'] . "', '" . str_replace("'", "\'",$_GET['note']) . "', '" . $_GET['authorization'] . "', '" .
    str_replace("'", "\'",$_GET['prescription1']) . "', '" . str_replace("'", "\'",$_GET['mg1']) . "', '" . str_replace("'", "\'",$_GET['Quantity1']) . "', '" . str_replace("'", "\'",$_GET['Dir1']) . "', '" . str_replace("'", "\'",$_GET['Dir21']) . "', '" .
    str_replace("'", "\'",$_GET['prescription2']) . "', '" . str_replace("'", "\'",$_GET['mg2']) . "', '" . str_replace("'", "\'",$_GET['Quantity2']) . "', '" . str_replace("'", "\'",$_GET['Dir2']) . "', '" . str_replace("'", "\'",$_GET['Dir22']) . "', '" .
    str_replace("'", "\'",$_GET['prescription3']) . "', '" . str_replace("'", "\'",$_GET['mg3']) . "', '" . str_replace("'", "\'",$_GET['Quantity3']) . "', '" . str_replace("'", "\'",$_GET['Dir3']) . "', '" . str_replace("'", "\'",$_GET['Dir23']) ."', '" .
    str_replace("'", "\'",$_GET['prescription4']) . "', '" . str_replace("'", "\'",$_GET['mg4']) . "', '" . str_replace("'", "\'",$_GET['Quantity4']) . "', '" . str_replace("'", "\'",$_GET['Dir4']) . "', '" . str_replace("'", "\'",$_GET['Dir24']) ."', '" .
    str_replace("'", "\'",$_GET['prescription5']) . "', '" . str_replace("'", "\'",$_GET['mg5']) . "', '" . str_replace("'", "\'",$_GET['Quantity5']) . "', '" . str_replace("'", "\'",$_GET['Dir5']) . "', '" . str_replace("'", "\'",$_GET['Dir25']) ."', '" . str_replace("'", "\'",$_GET['Reason']) .
    "')";

echo $query;

$con = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
$result = $con->query($query);



//TODO fix adding to audit log

//$audit = new AuditLog;
//$audit->SetChange($query);

//echo var_dump($result->fetch_row());
header($_SESSION['previous']);
