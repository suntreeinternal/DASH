<?php


/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 11/13/2018
 * Time: 10:04 AM
 */
//TODO add to Change log

session_start();

echo var_dump($_GET);

echo '<br/><br/>';

$update =  $_SESSION['name'] . " " . date("m/d/Y h:i:sa");

$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');

$query = "SELECT * FROM Referrals.Referrals WHERE ID=" . $_GET['refID'];
$result = $conReferrals->query($query);
$row = $result->fetch_row();

$strinNow = $row[1] . $row[2]  . $row[3]  . $row[4]  . $row[5]  . $row[6]  . $row[7]  . $row[8]  . $row[9];

echo $strinNow . '<br/><br/>';
echo $_GET['test']. '<br/><br/>';

if ($_GET['Contacted'] == 'on'){
    $query = "UPDATE Referrals.Referrals SET PatientContacted=1 WHERE ID=" . $_GET['refID'];
    $result = $conReferrals->query($query);
    $query = "UPDATE Referrals.Referrals SET dateContacted='" . $_GET['notifiedDate'] . "' WHERE ID=" . $_GET['refID'];
    $result = $conReferrals->query($query);

} elseif ($_GET['phone'] == 'on'){
    $query = "UPDATE Referrals.Referrals SET PatientContacted=2 WHERE ID=" . $_GET['refID'];
    $result = $conReferrals->query($query);
    $query = "UPDATE Referrals.Referrals SET dateContacted='" . $_GET['notifiedDate'] . "' WHERE ID=" . $_GET['refID'];
    $result = $conReferrals->query($query);

} else {
    $query = "UPDATE Referrals.Referrals SET PatientContacted=0 WHERE ID=" . $_GET['refID'];
    $result = $conReferrals->query($query);
}



if($strinNow == $_GET['test'])
{
    $query = "UPDATE Referrals.Referrals SET updatedBy='" . $update . "', Reason='" . str_replace("'", "\'",$_GET['Reason']) . "', SpecalistID=" . $_GET['Specalist'] . ", SpecaltyID=" . $_GET['Specialty'] . ", ProviderID=" . $_GET['provider'] . ", Status=" . $_GET['status'] . ", Priority=" . $_GET['priority'] . " WHERE ID=" . $_GET['refID'];
    echo '<br/><br/>' . $query;
    $result = $conReferrals->query($query);

    if ($_GET['goback']){
        header("location:/Reports/FrontPage/Report.php?query=" . $_GET['goback']);
    } else {
        header($_SESSION['previous']);
    }
} else {
    echo 'Strings do not match.';
}
$conReferrals->close();
echo "<br/>";




