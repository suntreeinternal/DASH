<?php
    session_start();
    /**
     * Created by PhpStorm.
     * User: SimInternal
     * Date: 11/2/2018
     * Time: 12:17 PM
     */
    $phone = str_ireplace('-','',$_GET['phone']);

    $con = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
    if($con->connect_error){
        header('location:/index.html');
    } else {
        $query = "SELECT * FROM Referrals.PatientData WHERE ID='" . $_SESSION['currentPatient'] . "'";
        $result = $con->query($query);
        $row = $result->fetch_row();
        $phoneOld = str_ireplace('-','',$row[4]);
        $query = 'UPDATE Referrals.PatientData SET Phone_number=\'' . $phone . '\' WHERE ID=\'' . $_SESSION['currentPatient'] . '\'';
        $result = $con->query($query);
        $query = "INSERT INTO Referrals.ChangeLog(UserID, ChangeSummery) VALUES ('" . $_SESSION['userID'] ."', 'Patient phone number Changed from " . $phoneOld . " to " . $phone . "')";
        $result = $con->query($query);
    }
//    echo $_SESSION['previous'];
header($_SESSION['previous']);