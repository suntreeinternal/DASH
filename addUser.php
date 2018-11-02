<?php
/**
 * Created by PhpStorm.
 * User: siminternal
 * Date: 11/1/2018
 * Time: 10:55 AM
 */
    session_start();
    $groupID = 6;
    $rightsID = 5;
    $userName = $_GET['user'];
    $password = $_GET['pass'];
    $con = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
    $query = 'SELECT * FROM Referrals.Users WHERE UserName=\'' . $userName . '\'';
    $result = $con->query($query);
    if ($result->num_rows > 0){
        echo 'error';
        header('location: /Admin.php');
    } else {

        if ($_GET['rights'] == "superAdminRights") {
            $rightsID = 1;
        }

        if ($rightsID == 1) {
            $query = "CREATE USER'" . $userName . "'@'localhost' IDENTIFIED BY '" . $password . "'";
            $result = $con->query($query);
            $query = "INSERT INTO Users (GroupID, RightsID, UserName, FirstName, LastName) VALUES ('1','1', '" . $userName . "', '" . $_GET['first'] . "', '" . $_GET['last'] . "')";
            $result = $con->query($query);
//        $row = $result->fetch_row();
            echo $query;
        }
        header('location: /Admin.php');
    }
