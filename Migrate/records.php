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


$query = "SELECT * FROM tblRequests";
$result = mssql_query($query);
if(!mssql_select_db('sw_charts', $con))
    echo "this sucks";

$count = 0;
while ($row = mssql_fetch_array($result)){
//    echo var_dump($row) . "</br></br>";
//    $dateChange = $row['StatusChange'];
//    $location = strrpos($dateChange, ' - ');
//    $dateChange = substr($dateChange, $location+3);
//    $now = new DateTime($dateChange);
//    $dateChange = $now->format('Y-m-d h:i:s');
////    echo $dateChange;
//
//    $dob = strtotime($row['DOB']);
    $last = $row['LastName'];
    $last = str_replace("'", "\'", $last);
    $first = $row['FirstName'];
    $first = str_replace("'", "\'", $first);
    $phoneNumber = $row['Phone1'];

//    echo $first . " " . $last . " : ";
    $query = "SELECT * FROM sw_charts.dbo.Gen_Demo WHERE last_name ='" . $last . "' AND first_name ='" . $first . "'";
    $resultMysql = mssql_query($query);
    $numRows = mssql_num_rows($resultMysql);
    $val = mssql_fetch_array($resultMysql);
    if($numRows != 1){
//        echo "sw contains more then 1 " . $first . " " . $last . "<br/>";
        $query = "SELECT * FROM Referrals.TempPatient WHERE LastName='" . $last . "' AND FirstName='" . $first . "'";
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
            $query = "INSERT INTO Referrals.TempPatient(FirstName, LastName, BirthDate) VALUES ('" . $first . "', '" . $last . "', '2222/01/01')";
            $conReferrals->query($query);
//            //get temp patient ID
            $query = "SELECT * FROM Referrals.TempPatient ORDER BY ID DESC LIMIT 1";
            $temp = $conReferrals->query($query);
            $rowVal = $temp->fetch_row();
            //Import patient into Patient data with TEMP ID
            $query = "INSERT INTO Referrals.PatientData(SW_ID, Message_alert_to_group, Note, Phone_number, temp, pipek) VALUES ('" . $rowVal[0] . "', '0', '', '" . $row['Phone1'] . "', '0', '0')";
            $conReferrals->query($query);
            //Get Patient ID
            $query = "SELECT * FROM Referrals.PatientData ORDER BY ID DESC LIMIT 1";
            $temp = $conReferrals->query($query);
            $rowVal = $temp->fetch_row();
            $patientID = $rowVal[0];
        }
        $resultTemp->close();
    } else {
//        echo "sw contains only 1 " . $first . " " . $last . " " . $val[0] . "<br/>";

        $query = "SELECT * FROM Referrals.PatientData WHERE SW_ID='" . $val[0] . "'";
        $swResult = $conReferrals->query($query);
        $patientSW = $swResult->fetch_row();
        if (!$patientSW){
            $query = "INSERT INTO Referrals.PatientData(SW_ID, Message_alert_to_group, Note, Phone_number, temp, pipek) VALUES ('" . $val[0] . "', '0', '', '" . $row['Phone1'] . "', '0', '0')";
            $conReferrals->query($query);
//            echo $query . "<br/>";
            $query = "SELECT * FROM Referrals.PatientData ORDER BY ID DESC LIMIT 1";
            $temp = $conReferrals->query($query);
            $rowVal = $temp->fetch_row();
            $patientID = $rowVal[0];
        } else {
            $patientID = $patientSW[0];
        }

    }
//    echo $patientID . " </br>";
    $check = 0;

    if ($row['InvoiceRequired']){
        $check = 1;
    }

    $status = 0;
//    echo var_dump($row) . "<br/><br/>";
    switch ($row['Status']){
        case 'Sent':
            $status = 3;
            break;

        case 'Approved':
            $status = 2;
            break;

        case 'Needs Approval':
            $status = 1;
            break;

        case 'Waiting for CK':
            $status = 0;
            break;

        default:
            echo "ERROR stat: " . var_dump($row) . "<br/>";
    }

    $requester = 0;
    switch ($row['Party3rdRequester']){
        case'Attorney':
            $requester = 2;
            break;

        case 'SSI':
            $requester = 4;
            break;

        case 'Patient':
            $requester = 3;
            break;

        case 'Life / Health Insurance':
            $requester = 5;
            break;

        case 'Doctors Office':
            $requester = 1;
            break;

        default:
            echo "ERROR3rd: " . $val['Party3rdRequester'] . "<br/>";
    }
    $dateChange = $row['StatusChanged'];
    $location = strrpos($dateChange, ' - ');
    $dateChange = substr($dateChange, $location+3);
    $now = new DateTime($dateChange);
    $dateChange = $now->format('Y-m-d h:i:s');

    $query = "INSERT INTO Referrals.RecordRequest (PatientID, Requester, Status, Auth, Date) VALUES (".
        "'" . $patientID .
        "', '" . $requester .
        "', '" . $status .
        "', '" . $check .
        "', '" . $dateChange . "')";
//    echo $query . "</br></br>";
    $conReferrals->query($query);
    if ($conReferrals->error){
        echo "ERROR: " . $conReferrals->error;
    }

    $query = "SELECT * FROM Referrals.RecordRequest ORDER BY ID DESC LIMIT 1";
    $test = $conReferrals->query($query);
    $testVal = $test->fetch_row();

    if ($row['AttachedText'] == "") {

    } else {
        $query = "INSERT INTO Referrals.Uploads(PatientID, UserId, OriginalFileName, SavesFileName, type, typeID, year, mon) VALUES ('" . $patientID . "', 'Transfer', '" . $row['AttachedText'] . "', '" . $row['AttachedText'] . "', '2', '" . $testVal[0] . "', 'transfer', '2013')";
        $conReferrals->query($query);
        if ($conReferrals->error)
            echo $conReferrals->error. "<br/>" . $query . "<br/><br/>";
    }

    if ($row['AttachedText2'] == "") {

    } else {
        $query = "INSERT INTO Referrals.Uploads(PatientID, UserId, OriginalFileName, SavesFileName, type, typeID, year, mon) VALUES ('" . $patientID . "', 'Transfernjn', '" . $row['AttachedText2'] . "', '" . $row['AttachedText2'] . "', '2', '" . $testVal[0] . "', 'transfer', '2013')";
        $conReferrals->query($query);
        if ($conReferrals->error)
            echo $conReferrals->error. "<br/>" . $query . "<br/><br/>";
    }

}
mssql_close($con);
$conReferrals->close();