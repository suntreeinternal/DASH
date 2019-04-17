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

$query = "SELECT * FROM tblPhoneMessages";
$result = mssql_query($query);
if(!mssql_select_db('sw_charts', $con))
    echo "this sucks";

echo "here";
while ($row = mssql_fetch_array($result)) {

    $dateChange = $row['MsgChanged'];
    $location = strrpos($dateChange, ' - ');
    $dateChange = substr($dateChange, $location+3);
    $now = new DateTime($dateChange);
    $dateChange = $now->format('Y-m-d h:i:s');
    echo $dateChange;

    $dob = strtotime($row['PatientDOB']);
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
        $query = "SELECT * FROM Referrals.PatientData WHERE SW_ID='" . $val[0] . "'";
        $swResult = $conReferrals->query($query);
        $patientSW = $swResult->fetch_row();
        if (!$patientSW){
            $query = "INSERT INTO Referrals.PatientData(SW_ID, Message_alert_to_group, Note, Phone_number, temp, pipek) VALUES ('" . $val[0] . "', '0', '', '" . $row['Phone1'] . "', '0', '0')";
            $conReferrals->query($query);
            $query = "SELECT * FROM Referrals.PatientData ORDER BY ID DESC LIMIT 1";
            $temp = $conReferrals->query($query);
            $rowVal = $temp->fetch_row();
            $patientID = $rowVal[0];
        } else {
            $patientID = $patientSW[0];
        }
    }
    $groupTo = -1;
    echo $row['Status'] . "<br/>";
    switch ($row['Status']){
        case 'Provider to MA':
            $groupTo = 0;
            echo "TO MA <br/>";
            break;

        case 'Reception to MA':
            $groupTo = 0;
    }

    if ($groupTo == -1) {
        $query = "INSERT INTO Referrals.PatientPhoneMessages(PatientID, User_ID, Message, UserGroup, ParrentMessage, TimeStamp) VALUES ('" . $patientID . "', 'Import', '" . str_replace("'", "\'", $row['Msg']) . "', 'MA', '0', '" . $dateChange . "')";
    } else {
        $query = "INSERT INTO Referrals.PatientPhoneMessages(PatientID, User_ID, Message, UserGroup, AlertToGroup, ParrentMessage, TimeStamp) VALUES ('" . $patientID . "', 'Import', '" . str_replace("'", "\'", $row['Msg']) . "', 'MA', '0', '" . $groupTo . "', '" . $dateChange . "')";
    }

        $conReferrals->query($query);
    if ($conReferrals->error)
        echo $conReferrals->error . " : " . $row[0] . "<br/><br/><br/>";

    $query = "SELECT * FROM Referrals.PatientPhoneMessages ORDER BY ID DESC LIMIT 1";
    $temp = $conReferrals->query($query);
    $tempVal = $temp->fetch_row();
    if ($conReferrals->error)
        echo $conReferrals->error . " : " . $row[0] . "<br/><br/><br/>";

    $query = "INSERT INTO Referrals.PatientPhoneMessages(PatientID, User_ID, Message, UserGroup, ParrentMessage) VALUES ('" . $patientID  . "', 'Import', '" . str_replace("'", "\'", $row['ProviderResponce']) . "', 'Provider', '" . $tempVal[0] . "')";
    $conReferrals->query($query);
    if ($conReferrals->error) {
        echo var_dump($tempVal) . "</br>";
        echo $conReferrals->error . " : " . $row[0] . "<br/>";
        echo $query . '<br/><br/>';
    }
    if ($row['AttachedText'] == "") {

    } else {
        $query = "INSERT INTO Referrals.Uploads(PatientID, UserId, OriginalFileName, SavesFileName, type, typeID, year, mon) VALUES ('" . $patientID . "', 'Transfer', '" . str_replace("'", "\'", $row['AttachedText']) . "', '" . str_replace("'", "\'", $row['AttachedText']) . "', '0', '0', 'transfer', '2013')";
        $testVal = $conReferrals->query($query);
        if ($conReferrals->error)
            echo $conReferrals->error. "<br/>" . $query . "<br/><br/>";
    }
    if ($row['AttachedText2'] == "") {

    } else {
        $query = "INSERT INTO Referrals.Uploads(PatientID, UserId, OriginalFileName, SavesFileName, type, typeID, year, mon) VALUES ('" . $patientID . "', 'Transfer', '" . str_replace("'", "\'", $row['AttachedText2']) . "', '" . str_replace("'", "\'", $row['AttachedText2']) . "', '0', '0', 'transfer', '2013')";
        $testVal = $conReferrals->query($query);
        if ($conReferrals->error)
            echo $conReferrals->error. "<br/>" . $query . "<br/><br/>";
    }
    if ($row['AttachedText3'] == "") {

    } else {
        $query = "INSERT INTO Referrals.Uploads(PatientID, UserId, OriginalFileName, SavesFileName, type, typeID, year, mon) VALUES ('" . $patientID . "', 'Transfer', '" . str_replace("'", "\'", $row['AttachedText3']) . "', '" . str_replace("'", "\'", $row['AttachedText3']) . "', '0', '0', 'transfer', '2013')";
        $testVal = $conReferrals->query($query);
        if ($conReferrals->error)
            echo $conReferrals->error. "<br/>" . $query . "<br/><br/>";
    }
    if ($row['AttachedText4'] == "") {

    } else {
        $query = "INSERT INTO Referrals.Uploads(PatientID, UserId, OriginalFileName, SavesFileName, type, typeID, year, mon) VALUES ('" . $patientID . "', 'Transfer', '" . $row['AttachedText4'] . "', '" . $row['AttachedText'] . "', '0', '0', 'transfer', '2013')";
        $testVal = $conReferrals->query($query);
        if ($conReferrals->error)
            echo $conReferrals->error. "<br/>" . $query . "<br/><br/>";
    }

}
mssql_close($con);
$conReferrals->close();