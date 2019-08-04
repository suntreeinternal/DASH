<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 2/14/2019
 * Time: 7:54 AM
 */
session_start();

$update =  $_SESSION['name'] . " " . date("m/d/Y h:i:sa");

//var_dump($_GET);
if ($_SESSION['group'] == "Provider") {
//    if ($_GET['authorization'] == 0){
//        $query = "UPDATE Referrals.Rx SET Status='2', ProviderID='" . $_GET['provider'] . "', Note='" . str_replace("'", "\'", $_GET['note']) . "', Authorization='" . $_GET['authorization'] .
//            "', presc1='" . str_replace("'", "\'", $_GET['prescription1']) . "', mg1='" . str_replace("'", "\'", $_GET['mg1']) . "', quan1='" . str_replace("'", "\'", $_GET['Quantity1']) . "', dir1='" . str_replace("'", "\'", $_GET['Dir1']) . "', dir21='" . str_replace("'", "\'", $_GET['Dir21']) .
//            "', presc2='" . str_replace("'", "\'", $_GET['prescription2']) . "', mg2='" . str_replace("'", "\'", $_GET['mg2']) . "', quan2='" . str_replace("'", "\'", $_GET['Quantity2']) . "', dir2='" . str_replace("'", "\'", $_GET['Dir2']) . "', dir22='" . str_replace("'", "\'", $_GET['Dir22']) .
//            "', presc3='" . str_replace("'", "\'", $_GET['prescription3']) . "', mg3='" . str_replace("'", "\'", $_GET['mg3']) . "', quan3='" . str_replace("'", "\'", $_GET['Quantity3']) . "', dir3='" . str_replace("'", "\'", $_GET['Dir3']) . "', dir23='" . str_replace("'", "\'", $_GET['Dir23']) .
//            "', presc4='" . str_replace("'", "\'", $_GET['prescription4']) . "', mg4='" . str_replace("'", "\'", $_GET['mg4']) . "', quan4='" . str_replace("'", "\'", $_GET['Quantity4']) . "', dir4='" . str_replace("'", "\'", $_GET['Dir4']) . "', dir24='" . str_replace("'", "\'", $_GET['Dir24']) .
//            "', presc5='" . str_replace("'", "\'", $_GET['prescription5']) . "', mg5='" . str_replace("'", "\'", $_GET['mg5']) . "', quan5='" . str_replace("'", "\'", $_GET['Quantity5']) . "', dir5='" . str_replace("'", "\'", $_GET['Dir5']) . "', dir25='" . str_replace("'", "\'", $_GET['Dir25']) .
//            "', Reason='" . str_replace("'", "\'", $_GET['Reason']) . "', ProviderNote='" . str_replace("'", "\'", $_GET['ProviderNote']) . "' WHERE ID='" . $_GET['RxId'] . "'";
//    } else {
        $query = "UPDATE Referrals.Rx SET Status='" . $_GET['status'] . "', ProviderID='" . $_GET['provider'] . "', Note='" . str_replace("'", "\'", $_GET['note']) . "', Authorization='" . $_GET['authorization'] .
            "', presc1='" . str_replace("'", "\'", $_GET['prescription1']) . "', mg1='" . str_replace("'", "\'", $_GET['mg1']) . "', quan1='" . str_replace("'", "\'", $_GET['Quantity1']) . "', dir1='" . str_replace("'", "\'", $_GET['Dir1']) . "', dir21='" . str_replace("'", "\'", $_GET['Dir21']) .
            "', presc2='" . str_replace("'", "\'", $_GET['prescription2']) . "', mg2='" . str_replace("'", "\'", $_GET['mg2']) . "', quan2='" . str_replace("'", "\'", $_GET['Quantity2']) . "', dir2='" . str_replace("'", "\'", $_GET['Dir2']) . "', dir22='" . str_replace("'", "\'", $_GET['Dir22']) .
            "', presc3='" . str_replace("'", "\'", $_GET['prescription3']) . "', mg3='" . str_replace("'", "\'", $_GET['mg3']) . "', quan3='" . str_replace("'", "\'", $_GET['Quantity3']) . "', dir3='" . str_replace("'", "\'", $_GET['Dir3']) . "', dir23='" . str_replace("'", "\'", $_GET['Dir23']) .
            "', presc4='" . str_replace("'", "\'", $_GET['prescription4']) . "', mg4='" . str_replace("'", "\'", $_GET['mg4']) . "', quan4='" . str_replace("'", "\'", $_GET['Quantity4']) . "', dir4='" . str_replace("'", "\'", $_GET['Dir4']) . "', dir24='" . str_replace("'", "\'", $_GET['Dir24']) .
            "', presc5='" . str_replace("'", "\'", $_GET['prescription5']) . "', mg5='" . str_replace("'", "\'", $_GET['mg5']) . "', quan5='" . str_replace("'", "\'", $_GET['Quantity5']) . "', dir5='" . str_replace("'", "\'", $_GET['Dir5']) . "', dir25='" . str_replace("'", "\'", $_GET['Dir25']) .
            "', Reason='" . str_replace("'", "\'", $_GET['Reason']) . "', ProviderNote='" . str_replace("'", "\'", $_GET['ProviderNote']) . "', LastUpdatedBy='" . $update . "' WHERE ID='" . $_GET['RxId'] . "'";
//    }
} else {
    $query = "UPDATE Referrals.Rx SET Status='" . $_GET['status'] . "', ProviderID='" . $_GET['provider'] . "', Note='" . str_replace("'", "\'", $_GET['note']) .
        "', presc1='" . str_replace("'", "\'", $_GET['prescription1']) . "', mg1='" . str_replace("'", "\'", $_GET['mg1']) . "', quan1='" . str_replace("'", "\'", $_GET['Quantity1']) . "', dir1='" . str_replace("'", "\'", $_GET['Dir1']) . "', dir21='" . str_replace("'", "\'", $_GET['Dir21']) .
        "', presc2='" . str_replace("'", "\'", $_GET['prescription2']) . "', mg2='" . str_replace("'", "\'", $_GET['mg2']) . "', quan2='" . str_replace("'", "\'", $_GET['Quantity2']) . "', dir2='" . str_replace("'", "\'", $_GET['Dir2']) . "', dir22='" . str_replace("'", "\'", $_GET['Dir22']) .
        "', presc3='" . str_replace("'", "\'", $_GET['prescription3']) . "', mg3='" . str_replace("'", "\'", $_GET['mg3']) . "', quan3='" . str_replace("'", "\'", $_GET['Quantity3']) . "', dir3='" . str_replace("'", "\'", $_GET['Dir3']) . "', dir23='" . str_replace("'", "\'", $_GET['Dir23']) .
        "', presc4='" . str_replace("'", "\'", $_GET['prescription4']) . "', mg4='" . str_replace("'", "\'", $_GET['mg4']) . "', quan4='" . str_replace("'", "\'", $_GET['Quantity4']) . "', dir4='" . str_replace("'", "\'", $_GET['Dir4']) . "', dir24='" . str_replace("'", "\'", $_GET['Dir24']) .
        "', presc5='" . str_replace("'", "\'", $_GET['prescription5']) . "', mg5='" . str_replace("'", "\'", $_GET['mg5']) . "', quan5='" . str_replace("'", "\'", $_GET['Quantity5']) . "', dir5='" . str_replace("'", "\'", $_GET['Dir5']) . "', dir25='" . str_replace("'", "\'", $_GET['Dir25']) .
        "', Reason='" . str_replace("'", "\'", $_GET['Reason']) . "', ProviderNote='" . str_replace("'", "\'", $_GET['ProviderNote']) . "', LastUpdatedBy='" . $update . "' WHERE ID='" . $_GET['RxId'] . "'";
}
echo $query;
$con = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
$result = $con->query($query);
$con->close();

header($_SESSION['previous']);
//echo var_dump($_SESSION);