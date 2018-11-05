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
        $query = 'UPDATE Referrals.PatientData SET Phone_number=\'' . $phone . '\' WHERE SW_ID=\'' . $_SESSION['currentPatient'] . '\'';
        $result = $con->query($query);
    }
    //TODO make it so it auto redirects back to previous page