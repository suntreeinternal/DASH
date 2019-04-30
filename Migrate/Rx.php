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


$query = "SELECT * FROM tblRXheader";
$result = mssql_query($query);
if(!mssql_select_db('sw_charts', $con))
    echo "this sucks";

$count = 0;
$count1 = 0;
$count2 = 0;
$count3 = 0;
$count4 = 0;
$countVal = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
while ($row = mssql_fetch_array($result)){
//    echo var_dump($row) . "<br/><br/>";
    $dateChange = $row['StatusChange'];
    $location = strrpos($dateChange, ' - ');
    $dateChange = substr($dateChange, $location+3);
    $now = new DateTime($dateChange);
    $dateChange = $now->format('Y-m-d h:i:s');
//    echo $dateChange;

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
            echo $conReferrals->error;
            $query = "SELECT * FROM Referrals.PatientData ORDER BY ID DESC LIMIT 1";
            $temp = $conReferrals->query($query);
            $rowVal = $temp->fetch_row();
            $patientID = $rowVal[0];
        } else {
            $patientID = $patientSW[0];
        }

//        echo $row[0] . " : " . $val[0] . " : " . $row['FirstName'] . " : " . $row["LastName"] . " : " . date("Ymd", $dob) . "<br/>";
    }

//    echo  . " </br>";

    $status = $row['Status'];

    $statusVal = 0;

    switch ($status){
        case 'Patient Notified':
            $statusVal = 6;
            break;

        case 'RX to MA':
            $statusVal =1;
            break;

        case 'RX to Provider':
            break;

        case 'RX to Reception':
            $statusVal =3;
            break;

        case 'RX to eScribe':
            $statusVal =4;
            break;

        case 'Pharmacy Called':
            $statusVal =5;
            break;
        default :
            $statusVal =2;
//            echo $status . "<br/>";
            break;
    }
    $query = "SELECT * FROM Referrals.Provider WHERE ProviderName='" . $row['Provider'] . "'";
    $temp = $conReferrals->query($query);
    $providerID = $temp->fetch_row()[0];
    if (!$providerID)
        $providerID = 1;

//    echo $query . " : " . $providerID . "<br/>";

    $va1StatOption = $row['StatusOption'];

    if (!$va1StatOption)
        $va1StatOption = 0;

//    $query = "INSERT INTO Referrals.Rx(PatientID, DateCreated, Status, ProviderID, Note, Authorization) VALUES
//        ('" . $patientID . "', '" .
//        $providerID . "', '" .
//        "" . "', '" .
//        $dateChange . "', '" .
//        $statusVal . "', '" .
//      $va1StatOption . "')";
//
//    $conReferrals->query($query);
//    if ($conReferrals->error){
//        echo $conReferrals->error . "    :    " .$query . " <br/><br/>";
//    }

    $query = "SELECT * FROM ReferralSystem.dbo.tblRXdetail WHERE HeaderID = '" . $row['ID'] . "'";
    $test = mssql_query($query);
    $testCnt = mssql_num_rows($test);
//    echo $testCnt . "<br/>";
    if ($testCnt == 0){
        $query = "INSERT INTO Referrals.Rx(PatientID, DateCreated, Status, ProviderID, Note, Authorization, Reason) VALUES ('"
            . $patientID . "','"
            . $dateChange . "','"
            . $statusVal . "','"
            . $providerID . "','"
            . "" . "','"
            . $va1StatOption . "','"
            . "" . "')";
        $conReferrals->query($query);
        if ($conReferrals->error){
            echo $conReferrals->error . "<br/><br/>";
        }
    } elseif ($testCnt == 1){
        $tempRow = mssql_fetch_array($test);
//        echo var_dump($tempRow) . "<br/>";
        $query = "INSERT INTO Referrals.Rx(PatientID, DateCreated, Status, ProviderID, Note, Authorization, Reason, presc1, mg1, quan1, dir1, dir21) VALUES ('"
            . $patientID . "','"
            . $dateChange . "','"
            . $statusVal . "','"
            . $providerID . "','"
            . "" . "','"
            . $va1StatOption . "','"
            . "" . "','"
            . str_replace("'", "\'",$tempRow['Prescription']) . "','"
            . str_replace("'", "\'",$tempRow['MG']) . "','"
            . str_replace("'", "\'",$tempRow['Quantity']) . "','"
            . str_replace("'", "\'",$tempRow['Directions']) . "','"
            . str_replace("'", "\'",$tempRow['Directions2'])
            ."')";
        $conReferrals->query($query);
        if ($conReferrals->error){
            echo $conReferrals->error . "<br/><br/>";
            echo $query;
        }
    } elseif ($testCnt == 2){
        $tempRow = mssql_fetch_array($test);
        $tempR0w2 = mssql_fetch_array($test);
//        echo var_dump($tempRow) . "<br/>";
        $query = "INSERT INTO Referrals.Rx(PatientID, DateCreated, Status, ProviderID, Note, Authorization, Reason, presc1, mg1, quan1, dir1, dir21, presc2, mg2, quan2, dir2, dir22) VALUES ('"
            . $patientID . "','"
            . $dateChange . "','"
            . $statusVal . "','"
            . $providerID . "','"
            . "" . "','"
            . $va1StatOption . "','"
            . "" . "','"
            . str_replace("'", "\'",$tempRow['Prescription']) . "','"
            . str_replace("'", "\'",$tempRow['MG']) . "','"
            . str_replace("'", "\'",$tempRow['Quantity']) . "','"
            . str_replace("'", "\'",$tempRow['Directions']) . "','"
            . str_replace("'", "\'",$tempRow['Directions2']) . "','"
            . str_replace("'", "\'",$tempRow2['Prescription']) . "','"
            . str_replace("'", "\'",$tempRow2['MG']) . "','"
            . str_replace("'", "\'",$tempRow2['Quantity']) . "','"
            . str_replace("'", "\'",$tempRow2['Directions']) . "','"
            . str_replace("'", "\'",$tempRow2['Directions2'])
            ."')";
        $conReferrals->query($query);
        if ($conReferrals->error){
            echo $conReferrals->error . "<br/><br/>";
            echo $query;
        }
    } elseif ($testCnt == 3){
        $tempRow = mssql_fetch_array($test);
        $tempR0w2 = mssql_fetch_array($test);
        $tempR0w3 = mssql_fetch_array($test);

//        echo var_dump($tempRow) . "<br/>";
        $query = "INSERT INTO Referrals.Rx(PatientID, DateCreated, Status, ProviderID, Note, Authorization, Reason, presc1, mg1, quan1, dir1, dir21, presc2, mg2, quan2, dir2, dir22, presc3, mg3, quan3, dir3, dir23) VALUES ('"
            . $patientID . "','"
            . $dateChange . "','"
            . $statusVal . "','"
            . $providerID . "','"
            . "" . "','"
            . $va1StatOption . "','"
            . "" . "','"
            . str_replace("'", "\'",$tempRow['Prescription']) . "','"
            . str_replace("'", "\'",$tempRow['MG']) . "','"
            . str_replace("'", "\'",$tempRow['Quantity']) . "','"
            . str_replace("'", "\'",$tempRow['Directions']) . "','"
            . str_replace("'", "\'",$tempRow['Directions2']) . "','"
            . str_replace("'", "\'",$tempRow2['Prescription']) . "','"
            . str_replace("'", "\'",$tempRow2['MG']) . "','"
            . str_replace("'", "\'",$tempRow2['Quantity']) . "','"
            . str_replace("'", "\'",$tempRow2['Directions']) . "','"
            . str_replace("'", "\'",$tempRow2['Directions2']) . "','"
            . str_replace("'", "\'",$tempRow3['Prescription']) . "','"
            . str_replace("'", "\'",$tempRow3['MG']) . "','"
            . str_replace("'", "\'",$tempRow3['Quantity']) . "','"
            . str_replace("'", "\'",$tempRow3['Directions']) . "','"
            . str_replace("'", "\'",$tempRow3['Directions2'])
            ."')";
        $conReferrals->query($query);
        if ($conReferrals->error){
            echo $conReferrals->error . "<br/><br/>";
            echo $query;
        }
    } elseif ($testCnt == 4){
        $tempRow = mssql_fetch_array($test);
        $tempR0w2 = mssql_fetch_array($test);
        $tempR0w3 = mssql_fetch_array($test);
        $tempR0w4 = mssql_fetch_array($test);
//        echo var_dump($tempRow) . "<br/>";
        $query = "INSERT INTO Referrals.Rx(PatientID, DateCreated, Status, ProviderID, Note, Authorization, Reason, presc1, mg1, quan1, dir1, dir21, presc2, mg2, quan2, dir2, dir22, presc3, mg3, quan3, dir3, dir23, presc4, mg4, quan4, dir4, dir24) VALUES ('"
            . $patientID . "','"
            . $dateChange . "','"
            . $statusVal . "','"
            . $providerID . "','"
            . "" . "','"
            . $va1StatOption . "','"
            . "" . "','"
            . str_replace("'", "\'",$tempRow['Prescription']) . "','"
            . str_replace("'", "\'",$tempRow['MG']) . "','"
            . str_replace("'", "\'",$tempRow['Quantity']) . "','"
            . str_replace("'", "\'",$tempRow['Directions']) . "','"
            . str_replace("'", "\'",$tempRow['Directions2']) . "','"
            . str_replace("'", "\'",$tempRow2['Prescription']) . "','"
            . str_replace("'", "\'",$tempRow2['MG']) . "','"
            . str_replace("'", "\'",$tempRow2['Quantity']) . "','"
            . str_replace("'", "\'",$tempRow2['Directions']) . "','"
            . str_replace("'", "\'",$tempRow2['Directions2']) . "','"
            . str_replace("'", "\'",$tempRow3['Prescription']) . "','"
            . str_replace("'", "\'",$tempRow3['MG']) . "','"
            . str_replace("'", "\'",$tempRow3['Quantity']) . "','"
            . str_replace("'", "\'",$tempRow3['Directions']) . "','"
            . str_replace("'", "\'",$tempRow3['Directions2']) . "','"
            . str_replace("'", "\'",$tempRow4['Prescription']) . "','"
            . str_replace("'", "\'",$tempRow4['MG']) . "','"
            . str_replace("'", "\'",$tempRow4['Quantity']) . "','"
            . str_replace("'", "\'",$tempRow4['Directions']) . "','"
            . str_replace("'", "\'",$tempRow4['Directions2'])
            ."')";
        $conReferrals->query($query);
        if ($conReferrals->error){
            echo $conReferrals->error . "<br/><br/>";
            echo $query;
        }
    } elseif ($testCnt == 5){
        $tempRow = mssql_fetch_array($test);
        $tempR0w2 = mssql_fetch_array($test);
        $tempR0w3 = mssql_fetch_array($test);
        $tempR0w4 = mssql_fetch_array($test);
        $tempR0w5 = mssql_fetch_array($test);
//        echo var_dump($tempRow) . "<br/>";
        $query = "INSERT INTO Referrals.Rx(PatientID, DateCreated, Status, ProviderID, Note, Authorization, Reason, presc1, mg1, quan1, dir1, dir21, presc2, mg2, quan2, dir2, dir22, presc3, mg3, quan3, dir3, dir23, presc4, mg4, quan4, dir4, dir24, presc5, mg5, quan5, dir5, dir25) VALUES ('"
            . $patientID . "','"
            . $dateChange . "','"
            . $statusVal . "','"
            . $providerID . "','"
            . "" . "','"
            . $va1StatOption . "','"
            . "" . "','"
            . str_replace("'", "\'",$tempRow['Prescription']) . "','"
            . str_replace("'", "\'",$tempRow['MG']) . "','"
            . str_replace("'", "\'",$tempRow['Quantity']) . "','"
            . str_replace("'", "\'",$tempRow['Directions']) . "','"
            . str_replace("'", "\'",$tempRow['Directions2']) . "','"
            . str_replace("'", "\'",$tempRow2['Prescription']) . "','"
            . str_replace("'", "\'",$tempRow2['MG']) . "','"
            . str_replace("'", "\'",$tempRow2['Quantity']) . "','"
            . str_replace("'", "\'",$tempRow2['Directions']) . "','"
            . str_replace("'", "\'",$tempRow2['Directions2']) . "','"
            . str_replace("'", "\'",$tempRow3['Prescription']) . "','"
            . str_replace("'", "\'",$tempRow3['MG']) . "','"
            . str_replace("'", "\'",$tempRow3['Quantity']) . "','"
            . str_replace("'", "\'",$tempRow3['Directions']) . "','"
            . str_replace("'", "\'",$tempRow3['Directions2']) . "','"
            . str_replace("'", "\'",$tempRow4['Prescription']) . "','"
            . str_replace("'", "\'",$tempRow4['MG']) . "','"
            . str_replace("'", "\'",$tempRow4['Quantity']) . "','"
            . str_replace("'", "\'",$tempRow4['Directions']) . "','"
            . str_replace("'", "\'",$tempRow4['Directions2']). "','"
            . str_replace("'", "\'",$tempRow5['Prescription']) . "','"
            . str_replace("'", "\'",$tempRow5['MG']) . "','"
            . str_replace("'", "\'",$tempRow5['Quantity']) . "','"
            . str_replace("'", "\'",$tempRow5['Directions']) . "','"
            . str_replace("'", "\'",$tempRow5['Directions2'])
            . "')";
        $conReferrals->query($query);
        if ($conReferrals->error){
            echo $conReferrals->error . "<br/><br/>";
            echo $query;
        }
    } else {
        echo "TOO MANY<br/>";
    }

    $query = "SELECT * FROM Referrals.Rx ORDER BY ID DESC LIMIT 1";
    $row = $conReferrals->query($query)->fetch_row();
    $RxId = $row[0];
//    echo $RxId;






//    $conReferrals->query($query);
//    if ($conReferrals->error)
//        echo $conReferrals->error. " test : <br/>" . $reason . "<br/><br/>";
//    $query = "SELECT * FROM Referrals.Referrals ORDER BY ID DESC LIMIT 1";
//    $test = $conReferrals->query($query);
//    $testVal = $test->fetch_row();
//    if ($conReferrals->error)
//        echo $conReferrals->error. " set : <br/><br/><br/>";
//    $query = "INSERT INTO Referrals.Note (ReferralID, UserID, Note, UserGroup) VALUES ('" . $testVal[0] . "', 'Import', '" . str_replace("'", "\'", $row['Notes']) . "', 'MA')";
//    $conReferrals->query($query);
//    if ($conReferrals->error)
//        echo $conReferrals->error . " : " . $row[0] . "<br/><br/><br/>";
//
//    if ($row['AttachedText'] == "") {
//
//    } else {
//        $query = "INSERT INTO Referrals.Uploads(PatientID, UserId, OriginalFileName, SavesFileName, type, typeID, year, mon) VALUES ('" . $patientID . "', 'Transfer', '" . $row['AttachedText'] . "', '" . $row['AttachedText'] . "', '0', '0', 'transfer', '2013')";
//        $testVal = $conReferrals->query($query);
//        if ($conReferrals->error)
//            echo $conReferrals->error. "<br/>" . $query . "<br/><br/>";
//    }
}
mssql_close($con);
$conReferrals->close();
echo var_dump($countVal);