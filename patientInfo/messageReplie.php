<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 2/7/2019
 * Time: 3:29 PM
 */

session_start();

include ("../AuditLog.php");


$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');


$query = 'UPDATE PatientPhoneMessages SET AlertToGroup = NULL WHERE id=\'' . $_GET["messageID"] .'\'' ;

$result = $conReferrals->query($query);

$audit = new AuditLog;
$string = "Cleared request from Patient " . $_GET['messageID'];
$audit->SetChange($string);

//TODO set the message to show here


header($_SESSION['previous']);
