<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 1/14/2019
 * Time: 12:31 PM
 */
session_start();

$con = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
if($con->connect_error){
    header('location:/index.html');
} else {

    $querey = "UPDATE Referrals.Provider SET Active='0' WHERE ID='" . $_GET['providerID'] . "'";
    echo $querey;
    $result = $con->query($querey);
    $query = "SELECT * FROM Provider WHERE ID='" . $_GET['providerID'] . "'";
    $result = $con->query($query);
    $row = $result->fetch_row();
    echo var_dump($row);
    $query = "INSERT INTO Referrals.ChangeLog (UserID, ChangeSummery, DateTime) VALUES ('" . $_SESSION['userID'] . "', 'Made provider inactive: " . $row[1] . "', '" . date("Y-m-d h:i:sa") . "')";
    $result = $con->query($query);
    echo var_dump($query);

}


