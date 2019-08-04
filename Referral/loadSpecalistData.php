<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 3/26/2019
 * Time: 3:15 PM
 */
session_start();

if (sizeof($_SESSION) == 0){
    header('location:../index.html');
}
$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
if ($_GET['id'] == -1){
// echo "asdfasdf";
} else {
    $query = "SELECT * FROM Referrals.Specialist WHERE ID=" . $_GET['id'];
    $result = $conReferrals->query($query);

    $row = $result->fetch_row();

    $speacalistTable = "<table name='specialistInfo' border='1' style='border-collapse: collapse' width='100%'><tbody><tr><th>Location</th><th>Fax</th><th>Phone</th><th>Notes</th></tr>";
    $speacalistTable .= "<tr><td>" . $row[3] . "</td>";
    $speacalistTable .= "<td>" . $row[5] . "</td>";
    $speacalistTable .= "<td>" . $row[4] . "</td>";
    $speacalistTable .= "<td>" . $row[6] . "</td></tr>";
    $speacalistTable .= "</tbody></table>";

    echo $speacalistTable;
}