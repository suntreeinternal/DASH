<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 1/16/2019
 * Time: 8:37 AM
 */
session_start();

$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
$start = $_GET['start'] . ' 00:00.00';
$end = $_GET['end'] . ' 23:59.59';
$query = "SELECT * FROM Referrals.ChangeLog WHERE UserID ='" . $_GET['name'] . "' AND DateTime >='" . $start ."' AND DateTime <='" . $end . "' ORDER BY ID DESC";
$result = $conReferrals->query($query);
$query = "SELECT * FROM Referrals.Users WHERE ID=" . $_GET['name'];
$getName = $conReferrals->query($query);
$userName = $getName->fetch_row();

echo "<table width='100%' id='main'><tbody>";
echo "<tr>";
echo "<th width='15%'>Name</th>";
echo "<th width='70%'>Action</th>";
echo "<th width='15%'>Date</th>";
echo "</tr>";
while ($row = $result->fetch_row()) {
    echo "<tr>";
    echo "<td>" . $userName[4] . " " . $userName[5] . "</td>";
    echo "<td>" . $row[2] . "</td>";
    $date = date_create($row[3]);
    echo "<td>" .  date_format($date, "m/d/Y H:i:s") . "</td>";
    echo "</tr>";
}
echo "</tbody></table>";