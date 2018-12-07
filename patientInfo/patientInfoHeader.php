<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 11/30/2018
 * Time: 12:28 PM
 */
session_start();

$query = 'SELECT * FROM dbo.Encounters WHERE Patient_ID=\'' . $_SESSION['swID'] . '\' ORDER BY visit_date DESC ';
$result = mssql_query($query);
$encounters = "";
while ($row = mssql_fetch_array($result)) {
    $encounters .= '<a href="../SoapNote.php?ID=' . $row[0] . '" target=\"_blank\">' . str_ireplace(':00:000', '', $row['visit_date']) . '</a>';
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
                <div class="dropdown-content">
                    <a href="uploadFile.php">Upload attachment</a>
                </div>
            </div>
        </td>

    </tr>
    </tbody>
</table>
</td>
