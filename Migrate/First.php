<?php


//connection for mssql
$con = mssql_connect('sunserver', 'siminternal', 'Watergate2015');
//$con2 = mssql_connect('sunserver', 'siminternal', 'Watergate2015');
//connection for sqli
$conReferrals = new mysqli('localhost', 'siminternal', 'Watergate2015', 'Referrals');

//$query = "SELECT * FROM tblSpecialties ORDER BY Specialties ASC";

if (!mssql_select_db('ReferralSystem', $con)){
    echo "this sucks";
}
//
$query = "DELETE FROM Referrals.Users WHERE ID IS NOT NULL";
$conReferrals->query($query);
echo $query . "</br>";
$query = "ALTER TABLE Referrals.Users AUTO_INCREMENT=1";
$conReferrals->query($query);
echo $query . "</br>";

$query = "DELETE FROM Referrals.Uploads WHERE Id IS NOT NULL ";
$conReferrals->query($query);
$query = "ALTER TABLE Referrals.Uploads AUTO_INCREMENT=1";
$conReferrals->query($query);
echo $query . "</br>";
$query = "DELETE FROM Referrals.TempPatient WHERE ID IS NOT NULL ";
$conReferrals->query($query);
$query = "ALTER TABLE Referrals.TempPatient AUTO_INCREMENT=1";
$conReferrals->query($query);
echo $query . "</br>";

$query = "DELETE FROM Referrals.Specialty WHERE ID IS NOT NULL";
$conReferrals->query($query);
$query = "ALTER TABLE Referrals.Specialty AUTO_INCREMENT=1";
$conReferrals->query($query);
echo $query . "</br>";

$query = "DELETE FROM Referrals.Specialist WHERE ID IS NOT NULL";
$conReferrals->query($query);
$query = "ALTER TABLE Referrals.Specialist AUTO_INCREMENT=800";
$conReferrals->query($query);
echo $query . "</br>";

$query = "DELETE FROM Referrals.Rx WHERE ID IS NOT NULL ";
$conReferrals->query($query);
$query = "ALTER TABLE Referrals.Rx AUTO_INCREMENT=1";
$conReferrals->query($query);
echo $query . "</br>";

$query = "DELETE FROM Referrals.Referrals WHERE ID IS NOT NULL ";
$conReferrals->query($query);
$query = "ALTER TABLE Referrals.Referrals AUTO_INCREMENT=1";
$conReferrals->query($query);
echo $query . "</br>";
$query = "DELETE FROM Referrals.RecordRequest WHERE ID IS NOT NULL ";
$conReferrals->query($query);
$query = "ALTER TABLE Referrals.RecordRequest AUTO_INCREMENT=1";
$conReferrals->query($query);
echo $query . "</br>";

$query = "DELETE FROM Referrals.PatientPhoneMessages WHERE id IS NOT NULL ";
$conReferrals->query($query);
$query = "ALTER TABLE Referrals.PatientPhoneMessages AUTO_INCREMENT=1";
$conReferrals->query($query);
echo $query . "</br>";

$query = "DELETE FROM Referrals.PatientData WHERE ID IS NOT NULL";
$conReferrals->query($query);
$query = "ALTER TABLE Referrals.PatientData AUTO_INCREMENT=1";
$conReferrals->query($query);
echo $query . "</br>";

$query = "DELETE FROM Referrals.Note WHERE ID IS NOT NULL";
$conReferrals->query($query);
echo $query . "</br>";
$query = "ALTER TABLE Referrals.Note AUTO_INCREMENT=1";
$conReferrals->query($query);
echo $query . "</br>";

$query = "DELETE FROM Referrals.MessageAboutPatient WHERE ID IS NOT NULL";
$conReferrals->query($query);
echo $query . "</br>";

$query = "DELETE FROM Referrals.MedsAuth WHERE ID IS NOT NULL";
$conReferrals->query($query);
$query = "ALTER TABLE Referrals.MedsAuth AUTO_INCREMENT=1";
$conReferrals->query($query);
echo $query . "</br>";

$query = "DELETE FROM Referrals.ChangeLog WHERE ID IS NOT NULL";
$conReferrals->query($query);
echo $query . "</br>";

$query = "DELETE FROM Referrals.RecordNote WHERE ID IS NOT NULL";
$conReferrals->query($query);
$query = "ALTER TABLE Referrals.RecordNote AUTO_INCREMENT=1";
$conReferrals->query($query);
echo $query . "</br>";

//if (!mssql_select_db('sw_charts', $con2)){
//    echo "this sucks";
//}


/** IMPORTING SPECIALITY */
$query = "SELECT * FROM tblSpecialties ORDER BY Specialties ASC";
$count = 0;
$result = mssql_query($query);
while ($row = mssql_fetch_array($result)){
    $foo = ucwords(strtolower($row['Specialties']));
    $foo = str_replace("'", "\'", $foo);
    $queryPut = "INSERT INTO Referrals.Specialty(Specialty) VALUES (\"" . $foo . "\")";
    echo $queryPut . " : ";
    $resultPut = $conReferrals->query($queryPut);

    echo  $foo . "</br>";
    $count ++;
}


/** IMPORTING SPECIALIST  */
$query = "SELECT * FROM tblSpecialists";

$result = mssql_query($query);
$count = 1;
while ($row = mssql_fetch_array($result)){
    $foo = ucwords(strtolower($row['Specialty']));
    echo $foo . " : ";
    $query = "SELECT * FROM Referrals.Specialty WHERE Specialty='" . $foo . "'";
    $resultReferral = $conReferrals->query($query);
    $val = $resultReferral->fetch_row();
    if (!$val) {
        $query = "INSERT INTO Referrals.Specialty(Specialty) VALUES ('" . $foo ."')";
        $conReferrals->query($query);
        $query = "SELECT * FROM Referrals.Specialty WHERE Specialty='" . $foo . "'";
        $resultReferral = $conReferrals->query($query);
        $val = $resultReferral->fetch_row();
    }
    if (!$row['DoctorName'] == "") {
        $query = "INSERT INTO Referrals.Specialist(ID, SpecialtyID, DrName, Location, Phone, Fax, Note) VALUES ('" . $count . "', '" . $val[0] . "', '" . str_replace("'", "\'", $row['DoctorName']) .
            "', '" . str_replace("'", "\'", $row['Location']) . "', '" . $row['Phone'] . "', '" . $row['Fax'] . "', '" . str_replace("'", "\'", $row['SpecialtyNotes']) . "')";
        echo $query . "</br>";
        $conReferrals->query($query);
        $count++;
    }
}


/** IMPORTING USERS */

$query = "SELECT * FROM tblUsers";
$result = mssql_query($query);
$count = 1;
while ($row = mssql_fetch_array($result)){
    $password = $row['Password'];
    $userName = $row['Users'];
    $last = $row['LastName'];
    $first = $row['FirstName'];
    $group = 5;
    $rights = '';
    switch ($row['PermissionType']){
        case 'Admin':
            $rights = 1;
            break;

        case 'Read Only':
            $rights = 4;
            break;

        case 'User':
            $rights = 3;
    }

    $query = "INSERT INTO Referrals.Users (ID, GroupID, RightsID, UserName, FirstName, LastName, Password) VALUES ('" . $count . "', '" . $group . "', '" . $rights . "', '" . $userName . "', '" . $first . "', '" . $last . "', '" . $password . "')";
    $conReferrals->query($query);
    echo $query . "<br/><br/>";
    $count ++;
}
//
//echo "messages</br>";
//include "messaging.php";
//echo "Referrals</br>";
//include "Referrals.php";




///** IMPORTING Patient list */
//$query = "SELECT * FROM tblReferrals";
//$result = mssql_query($query);
//if (!mssql_select_db('sw_charts', $con)){
//    echo "this sucks";
//}
//$count = 1;
//while ($row = mssql_fetch_array($result)){
//    echo $row[1] . " : " . $row[3] . " : " . $row['Notes'] . "</br>";
//    $first = $row['FirstName'];
//    $last = $row['LastName'];
//    $dob = strtotime($row['DOB']);
//    $query = "SELECT * FROM Gen_Demo WHERE first_name='" . $first . "' AND last_name='" . $last . "' AND birthdate='" . date("Ymd", $dob) ."'";
//    $re = mssql_query($query);
//    $val = mssql_fetch_array($re);
////    echo $val . "</br>";
//    if ($val){
//        $query = "INSERT INTO Referrals.PatientData(ID, SW_ID, Message_alert_to_group, Note, Phone_number, temp, pipek) VALUES ('" . $count . "', '" . $val[0]. "',NULL,'','0', NULL, NULL)";
////        echo $query . "</br>";
//        $conReferrals->query($query);
//    } else {
//        echo $first . " : " . $last . "<br/>";
//    }
//    $count ++;
//}

//header("location:/Migrate/Referrals.php");
