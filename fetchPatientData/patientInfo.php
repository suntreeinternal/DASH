<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 1/17/2019
 * Time: 7:43 AM
 */


class Patient{

    private $lastName="";
    private $firstName="";
    private $dob = 0;
    private $SwId = '';

    public function SelectPatient($DashID){

        $conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');

        $query = "SELECT * FROM Referrals.PatientData WHERE ID='" . $DashID . "'";
        $result = $conReferrals->query($query);
        $row = $result->fetch_row();
        $this->SwId = $row[1];

        if (strpos($row[1], '-') !== false){
            $con = mssql_connect('sunserver', 'siminternal', 'Watergate2015');
            if (!mssql_select_db('sw_charts', $con)) {
                die('Unable to select database! ');
            }
            $query = "SELECT * FROM dbo.Gen_Demo WHERE Patient_ID='" . $row[1] . "'";
            $result = mssql_query($query);
            $row = mssql_fetch_array($result);
            $this->lastName = $row[1];
            $this->dob = $row[21];
            $this->firstName = $row[2];


        } else {
            $query = "SELECT * FROM Referrals.TempPatient WHERE ID='" . $row[1] . "'";
            $result = $conReferrals->query($query);
            $row = $result->fetch_row();
            $this->lastName = $row[2];
            $this->dob = $row[3];
            $this->firstName = $row[1];
        }
    }
    public function GetDOB(){
        return $this->dob;
    }

    public function GetLastName(){
        return $this->lastName;
    }

    public function GetFirstName(){
        return $this->firstName;
    }

    public function GetFullName(){
        $string = $this->firstName . " " . $this->lastName;
        return $string;
    }

    /**
     * @return string
     */
    public function getSwId()
    {
        return $this->SwId;
    }

}
