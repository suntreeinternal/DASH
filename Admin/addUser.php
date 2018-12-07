<?php
/**
 * Created by PhpStorm.
 * User: siminternal
 * Date: 11/1/2018
 * Time: 10:55 AM
 */
    session_start();
    $group = $_GET['group'];
    switch ($group){
        case ('receptionGroup'):
            $groupID = 2;
            break;

        case ('adminGroup'):
            $groupID = 1;
            break;

        case ('providerGroup'):
            $groupID = 3;
            break;

        case ('referralsGroup'):
            $groupID = 4;
            break;

        case ('maGroup'):
            $groupID = 5;
            break;

        case ('inactiveGroup'):
            $groupID = 6;
            break;
    }
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
            $query = "INSERT INTO Users (GroupID, RightsID, UserName, FirstName, LastName) VALUES ('" . $groupID . "','1', '" . $userName . "', '" . $_GET['first'] . "', '" . $_GET['last'] . "')";
            $result = $con->query($query);
//        $row = $result->fetch_row();
            echo $query;
        }
        header('location: /Admin.php');
    }
