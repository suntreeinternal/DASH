<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 2/14/2019
 * Time: 7:54 AM
 */
session_start();

var_dump($_GET);

$query = 'UPDATE Referrals.Rx SET Status=' . $_GET['status'] . ', ProviderID='. $_GET['provider'] . ', Note='. $_GET['Note'] . ', Authorization='. $_GET['authorization'] .
    ', presc1='. $_GET['prescription1'] . ', mg1='. $_GET['mg1'] . ', quan1='. $_GET['Quantity1'] . ', dir1='. $_GET['Dir1'] . ', dir21='. $_GET['Dir21'] .
    ', presc1='. $_GET['prescription2'] . ', mg1='. $_GET['mg2'] . ', quan1='. $_GET['Quantity2'] . ', dir1='. $_GET['Dir2'] . ', dir21='. $_GET['Dir22'] .
    ', presc1='. $_GET['prescription3'] . ', mg1='. $_GET['mg3'] . ', quan1='. $_GET['Quantity3'] . ', dir1='. $_GET['Dir3'] . ', dir21='. $_GET['Dir23'] .
    ', presc1='. $_GET['prescription4'] . ', mg1='. $_GET['mg4'] . ', quan1='. $_GET['Quantity4'] . ', dir1='. $_GET['Dir4'] . ', dir21='. $_GET['Dir24'] .
    ', presc1='. $_GET['prescription5'] . ', mg1='. $_GET['mg5'] . ', quan1='. $_GET['Quantity5'] . ', dir1='. $_GET['Dir5'] . ', dir21='. $_GET['Dir25'] .
    ', Reason='. $_GET['Reason'] . ' WHERE ID='. $_GET['RxId'];


$con = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
$result = $con->query($query);
