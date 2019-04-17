<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 4/2/2019
 * Time: 6:47 AM
 */

/** uploading Referrals to new dash
 *
 */
//connection for mssql
$con = mssql_connect('sunserver', 'siminternal', 'Watergate2015');
$conReferrals = new mysqli('localhost', 'siminternal', 'Watergate2015', 'Referrals');

if (!mssql_select_db('ReferralSystem', $con)){
    echo "this sucks";
}


$query = "SELECT * FROM tblReferrals";
$result = mssql_query($query);
if(!mssql_select_db('sw_charts', $con))
    echo "this sucks";

$count = 0;
while ($row = mssql_fetch_array($result)){
    $dateChange = $row['StatusChange'];
    $location = strrpos($dateChange, ' - ');
    $dateChange = substr($dateChange, $location+3);
    $now = new DateTime($dateChange);
    $dateChange = $now->format('Y-m-d h:i:s');
//    echo $dateChange;

    $dob = strtotime($row['DOB']);
    $last = $row['LastName'];
    $last = str_replace("'", "\'", $last);
    $first = $row['FirstName'];
    $first = str_replace("'", "\'", $first);
    $query = "SELECT * FROM dbo.Gen_Demo WHERE last_name='". $last . "' AND first_name='" . $first . "' AND birthdate='" .  date("Ymd", $dob) . "'";
    $patientInSw = mssql_query($query);
    $val = mssql_fetch_array($patientInSw);
    $patientID;
    if(!$val){
        $query = "SELECT * FROM Referrals.TempPatient WHERE LastName='" . $last . "' AND FirstName='" . $first . "' AND BirthDate='" . date("Ymd", $dob) . "'";
        $resultTemp = $conReferrals->query($query);
//        echo $query;
        $rowTemp = $resultTemp->fetch_row();
        if ($rowTemp){
            $query = "SELECT * FROM Referrals.PatientData WHERE SW_ID='" . $rowTemp[0] . "'";
            $temp = $conReferrals->query($query);
            $rowVal = $temp->fetch_row();
            $patientID = $rowVal[0];
        } else {
            //Import patient into temp
            $query = "INSERT INTO Referrals.TempPatient(FirstName, LastName, BirthDate) VALUES ('" . $first . "', '" . $last . "', '" . date("Ymd", $dob) ."')";
            $conReferrals->query($query);
            //get temp patient ID
            $query = "SELECT * FROM Referrals.TempPatient ORDER BY ID DESC LIMIT 1";
            $temp = $conReferrals->query($query);
            $rowVal = $temp->fetch_row();
            //Import patient into Patient data with TEMP ID
            $query = "INSERT INTO Referrals.PatientData(SW_ID, Message_alert_to_group, Note, Phone_number, temp, pipek) VALUES ('" . $rowVal[0] . "', '0', '', '" . $row['BestContact1'] . "', '0', '0')";
            $conReferrals->query($query);
            //Get Patient ID
            $query = "SELECT * FROM Referrals.PatientData ORDER BY ID DESC LIMIT 1";
            $temp = $conReferrals->query($query);
            $rowVal = $temp->fetch_row();
            $patientID = $rowVal[0];
        }
        $resultTemp->close();
    } else {
        $query = "SELECT * FROM Referrals.PatientData WHERE SW_ID='" . $val[0] . "'";
        $swResult = $conReferrals->query($query);
        $patientSW = $swResult->fetch_row();
        if (!$patientSW){
            $query = "INSERT INTO Referrals.PatientData(SW_ID, Message_alert_to_group, Note, Phone_number, temp, pipek) VALUES ('" . $val[0] . "', '0', '', '" . $row['BestContact1'] . "', '0', '0')";
            $conReferrals->query($query);
            $query = "SELECT * FROM Referrals.PatientData ORDER BY ID DESC LIMIT 1";
            $temp = $conReferrals->query($query);
            $rowVal = $temp->fetch_row();
            $patientID = $rowVal[0];
        } else {
            $patientID = $patientSW[0];
        }

//        echo $row[0] . " : " . $val[0] . " : " . $row['FirstName'] . " : " . $row["LastName"] . " : " . date("Ymd", $dob) . "<br/>";
    }
//    echo $patientID . " </br>";
    $foo = ucwords(strtolower($row['Specialty']));

    $query = "SELECT * FROM Referrals.Provider WHERE ProviderName='" . $row['Provider'] . "'";
    $temp = $conReferrals->query($query);
    $providerID = $temp->fetch_row()[0];

    $query = "SELECT * FROM Referrals.Specialty WHERE Specialty='" . str_replace("'", "\'", $foo) . "'";
    $temp = $conReferrals->query($query);
    $specialtyId = $temp->fetch_row()[0];

    $reason = $row['DiagnosisReason'];

    $query = "SELECT * FROM Referrals.Specialist WHERE DrName='" . str_replace("'", "\'", $row['Specialists']) . "'";
    $temp = $conReferrals->query($query);
    $list = $temp->fetch_row()[0];


    $priority;
    switch ($row['Priority']){
        case '1- ASAP':
            $priority = 1;
            break;

        default:
            $priority = 3;
    }

    $status = 0;
    switch ($row['Status']){
        case 'COMPLETED':
            $status = 4;
            break;

        case 'Completed':
            $status = 4;
            break;

        case 'PENDING SOAP/DEMO missing':
            $status = 2;
            break;

        case 'PENDING Appointment from Specialist':
            $status = 3;
            break;

        case 'PATIENT DECLINED APPT':
            $status = 4;
            break;

        case 'NEW':
            $status = 5;
            break;

        default:
            $status = 6;
            echo "ERROR: ". $row['Status'] . "<br/><br/>";
            break;

    }
    if($list == '')
        $list = 0;

    if($providerID == '')
        $providerID = 1;

    if($specialtyId == '')
        $specialtyId = 0;


    $query = "INSERT INTO Referrals.Referrals (ProviderID, PatientID, Status, Priority, Authorization, Reason, SpecaltyID, SpecalistID, Date) VALUES ('" . $providerID . "', '" . $patientID
        . "', '" . $status . "', '" . $priority . "', '0', '" . str_replace("'", "\'", $reason) . "', '" . $specialtyId . "', '" . $list . "', '" .  $dateChange . "')";

    $conReferrals->query($query);
    if ($conReferrals->error)
        echo $conReferrals->error. " test : <br/>" . $reason . "<br/><br/>";
    $query = "SELECT * FROM Referrals.Referrals ORDER BY ID DESC LIMIT 1";
    $test = $conReferrals->query($query);
    $testVal = $test->fetch_row();
    if ($conReferrals->error)
        echo $conReferrals->error. " set : <br/><br/><br/>";
    $query = "INSERT INTO Referrals.Note (ReferralID, UserID, Note, UserGroup) VALUES ('" . $testVal[0] . "', 'Import', '" . str_replace("'", "\'", $row['Notes']) . "', 'MA')";
    $conReferrals->query($query);
    if ($conReferrals->error)
        echo $conReferrals->error . " : " . $row[0] . "<br/><br/><br/>";

    if ($row['AttachedText'] == "") {

    } else {
        $query = "INSERT INTO Referrals.Uploads(PatientID, UserId, OriginalFileName, SavesFileName, type, typeID, year, mon) VALUES ('" . $patientID . "', 'Transfer', '" . $row['AttachedText'] . "', '" . $row['AttachedText'] . "', '0', '0', 'transfer', '2013')";
        $testVal = $conReferrals->query($query);
        if ($conReferrals->error)
            echo $conReferrals->error. "<br/>" . $query . "<br/><br/>";
    }
}
mssql_close($con);
$conReferrals->close();