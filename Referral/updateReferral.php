<?php


/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 11/13/2018
 * Time: 10:04 AM
 */
//TODO add to Change log

session_start();

$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');

$query = "SELECT * FROM Referrals.Referrals WHERE ID=" . $_GET['refID'];
$result = $conReferrals->query($query);
$row = $result->fetch_row();

$strinNow = $row[1] . $row[2] . $row[3] . $row[4] . $row[5] . $row[6] . $row[7] . $row[8] . $row[9];

echo $strinNow . '<br/>';
echo $_GET['test'];

if($strinNow == $_GET['test'])
{
    $query = "UPDATE Referrals.Referrals SET Reason='" . str_replace("'", "\'",$_GET['Reason']) . "', SpecalistID=" . $_GET['Specalist'] . ", SpecaltyID=" . $_GET['Specialty'] . ", ProviderID=" . $_GET['provider'] . ", Status=" . $_GET['status'] . ", Priority=" . $_GET['priority'] . " WHERE ID=" . $_GET['refID'];
//echo $query;

    $result = $conReferrals->query($query);

header($_SESSION['previous']);
} else {
    echo 'Strings do not match.';
}
$conReferrals->close();
echo "<br/>";


