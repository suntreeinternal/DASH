<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 1/17/2019
 * Time: 7:43 AM
 */


class Patient{

    private $lastname="";
    private $dob = 0;
    public function SelectPatient($DashID){

        $conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');

        $query = "SELECT * FROM Referrals.PatientData WHERE ID='" . $DashID . "'";
        $result = $conReferrals->query($query);
        $row = $result->fetch_row();


        if (strpos($row[1], '-') !== false){
            $con = mssql_connect('sunserver', 'siminternal', 'Watergate2015');
            if (!mssql_select_db('sw_charts', $con)) {
                die('Unable to select database! ');
            }
            $query = "SELECT * FROM dbo.Gen_Demo WHERE Patient_ID='" . $row[1] . "'";
            $result = mssql_query($query);
            $row = mssql_fetch_array($result);
            $this->lastname = $row[1];
            $this->dob = $row[21];


        } else {
            $query = "SELECT * FROM Referrals.TempPatient WHERE ID='" . $row[1] . "'";
            $result = $conReferrals->query($query);
            $row = $result->fetch_row();
            $this->lastname = $row[2];
            $this->dob = $row[3];
        }
    }
    public function GetDOB(){
        return $this->dob;
    }

    public function GetLastName(){
        return $this->lastname;
    }
}
