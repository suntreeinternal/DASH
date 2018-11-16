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
        $query = 'UPDATE Referrals.PatientData SET Phone_number=\'' . $phone . '\' WHERE ID=\'' . $_SESSION['currentPatient'] . '\'';
        $result = $con->query($query);
        $query = "INSERT INTO Referrals.ChangeLog(UserID, WhatChanged, ChangeLocation, DateTime) VALUES ('" . $_SESSION['userID'] ."','Some thing',' patient phone number ',' " . date("Y-m-d h:i:sa") . "')";
        $result = $con->query($query);
    }
//    echo $_SESSION['currentPatient'];
header($_SESSION['previous']);