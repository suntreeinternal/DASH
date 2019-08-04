<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 11/30/2018
 * Time: 12:28 PM
 */
session_start();
include_once "/var/www/simDash.com/html/fetchPatientData/patientInfo.php";
$con = mssql_connect('sunserver', 'siminternal', 'Watergate2015');
$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
if (!mssql_select_db('sw_charts', $con)) {
    die('Unable to select database!');
}

$patientInfo = new Patient();

$query = 'SELECT * FROM dbo.Encounters WHERE Patient_ID=\'' . $_SESSION['swID'] . '\' ORDER BY visit_date DESC ';
$result1 = mssql_query($query);
$encounters = "";

$query = 'SELECT * FROM Referrals.PatientData WHERE ID=\'' . $_SESSION['currentPatient'] . '\'';
$result = $conReferrals->query($query);
$testRow2 = $result->fetch_row();
$phoneNumber = $testRow2[4];
$alert = $testRow2[2];

$patientInfo->SelectPatient($_SESSION['currentPatient']);
$patientName = $patientInfo->GetFirstName() . " " . $patientInfo->GetLastName();

while ($row1 = mssql_fetch_array($result1)) {
    $encounters .= '<a href="../SoapNote.php?ID=' . $row1[0] . '&dob=' . $_SESSION['patientDOB'] . '&dos=' . $row1['visit_date'] .'&first=' . $patientInfo->GetFirstName() . '&last=' . $patientInfo->GetLastName() . '" target=\"_blank\">' . str_ireplace(':00:000', '', $row1['visit_date']) . '</a>';
}

if(ctype_digit($phoneNumber) && strlen($phoneNumber) == 10) {
    $phoneNumber = "(" . substr($phoneNumber, 0, 3) .') '. substr($phoneNumber, 3, 3) .'-'. substr($phoneNumber, 6);
} else {
    if(ctype_digit($phoneNumber) && strlen($phoneNumber) == 7) {
        $phoneNumber = substr($phoneNumber, 0, 3) .'-'. substr($phoneNumber, 3, 4);
    }
}

if (!$_GET['type']){
    $query = "SELECT * FROM Referrals.Uploads WHERE PatientID='" . $_SESSION['currentPatient'] ."' ORDER BY Id DESC";
    $result = $conReferrals->query($query);
    $files = "";
    while ($row = $result->fetch_row()){
        $files .= '<a href="../uploads/uploads/' . $row[8] . '/' . $row[9] . '/' . $row[5] . '" target=\"_blank\">' . $row[4] . '</a>';
    }
    $files .= '<a href="../patientInfo/uploadFile.php?typeID=' . $_GET['typeID'] . '&type=' . $_GET['type'] . '">Upload attachment</a>';
} else {
    $query = "SELECT * FROM Referrals.Uploads WHERE PatientID='" . $_SESSION['currentPatient'] . "' AND type=" . $_GET['type'] . " AND typeID=" . $_GET['typeID'] . " ORDER BY Id DESC";
    $result = $conReferrals->query($query);
    $files = "";
    while ($row = $result->fetch_row()) {
        $files .= '<a href="../uploads/uploads/' . $row[8] . '/' . $row[9] . '/' . $row[5] . '" target=\"_blank\">' . $row[4] . '</a>';
    }
    $files .= '<a href="../patientInfo/uploadFile.php?type='. $_GET['type'] . '&typeID=' . $_GET['typeID'] . '">Upload attachment</a>';
}
?>

<td colspan="4">
<table style="font-size: 20px" width="100%">
    <tbody>
    <tr>
        <td width="20%">
            <?php echo $patientName?>
        </td>
        <td width="20%">
            DOB: <?php echo  date("m-d-Y", strtotime($DOB))?>
        </td>
        <td width="20%">
                    <div id="id01">
                        <form action="../patientInfo/updatePatient.php" method="get">
                            Phone Number: <input id="phone" type="tel" value="<?php echo $phoneNumber?>" name="phone">
                    <button type="submit" id="update">Update</button>
                </form>
            </div>
        </td>
        <td width="30%" align="right">
            <div class="dropdown" >
                <button class="dropbtn">Encounters</button>
                <div class="dropdown-content">
                    <?php echo $encounters?>
                </div>
            </div>
            <div class="dropdown">
                <button class="dropbtn">Other Attachments</button>
                <div class= "dropdown-content">
                    <?php echo $files?>
                </div>
            </div>
        </td>

    </tr>
    </tbody>
</table>
</td>
