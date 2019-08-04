<?php

phpinfo();
///**
// * Created by PhpStorm.
// * User: SimInternal
// * Date: 5/9/2019
// * Time: 6:54 AM
// */
//
////connection for mssql
//$con = mssql_connect('sunserver', 'siminternal', 'Watergate2015');
////$con2 = mssql_connect('sunserver', 'siminternal', 'Watergate2015');
////connection for sqli
//$conReferrals = new mysqli('localhost', 'siminternal', 'Watergate2015', 'Referrals');
//
////$query = "SELECT * FROM tblSpecialties ORDER BY Specialties ASC";
//
//if (!mssql_select_db('sw_charts', $con)){
//    echo "this sucks";
//}
//$query = "SELECT * FROM Referrals.TempPatient";
//$temp = $conReferrals->query($query);
//while ($row = $temp->fetch_row()){
////    echo str_replace("-", "", $row[3]) . "<br/>";
//
//    $query = "SELECT * FROM dbo.Gen_Demo WHERE last_name='" . $row[2] . "' AND birthdate='" .str_replace("-", "", $row[3]) . "'";
//    $test = mssql_query($query);
//    $SW = mssql_fetch_array($test);
//    echo $row[2] . " : " . $row[1] . "<br/><br/>";
//
//    if ($SW) {
//        $query = "SELECT * FROM Referrals.PatientData WHERE SW_ID='" . $SW[0] . "'";
//        $val = $conReferrals->query($query);
//        $testt = $val->fetch_row();
//        if ($testt) {
//            echo var_dump($testt) . "<br/>";
//            $PatientNumberSW = $testt[0];
//
//        }
//
//        $query = "SELECT * FROM Referrals.PatientData WHERE SW_ID='" . $row[0] . "'";
//        $val = $conReferrals->query($query);
//        $testtt = $val->fetch_row();
//        if ($testtt) {
//            echo var_dump($testtt) . "<br/><br/>";
//            $patientNumberTEMP = $testtt[0];
//
//        }
//        if ($testt and $testtt ) {
//            //
//
////            $query = "UPDATE Referrals.RecordRequest SET PatientID=" . $PatientNumberSW . " WHERE PatientID=" . $patientNumberTEMP;
////            $conReferrals->query($query);
////            $query = "UPDATE Referrals.Referrals SET PatientID=" . $PatientNumberSW . " WHERE PatientID=" . $patientNumberTEMP;
////            $conReferrals->query($query);
////            $query = "UPDATE Referrals.Rx SET PatientID=" . $PatientNumberSW . " WHERE PatientID=" . $patientNumberTEMP;
////            $conReferrals->query($query);
////            $query = "UPDATE Referrals.MedsAuth SET PatientID=" . $PatientNumberSW . " WHERE PatientID=" . $patientNumberTEMP;
////            $conReferrals->query($query);
////            $query = "UPDATE Referrals.PatientPhoneMessages SET PatientID=" . $PatientNumberSW . " WHERE PatientID=" . $patientNumberTEMP;
////            $conReferrals->query($query);
////
////            $query = "DELETE FROM Referrals.TempPatient WHERE ID=" . $row[0];
////            $conReferrals->query($query);
////        $query = "DELETE FROM Referrals.PatientData WHERE ID=" . $patientNumberTEMP;
////        $conReferrals->query($query);
//        }
//
//    }
//}
