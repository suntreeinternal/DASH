<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 4/26/2019
 * Time: 9:23 AM
 */
if($_GET['sim']){
    $StringRedirect = '/patientInfo/Patient.php?last=' . $_GET['last'] . '&date=' . $_GET['date'];
    header("location:".$StringRedirect);
} elseif ($_GET['pipek']){
    $StringRedirect = '/patientInfo/selectPatientPipek.php?last=' . $_GET['last'] . '&date=' . $_GET['date'];
    header("location:".$StringRedirect);
}
