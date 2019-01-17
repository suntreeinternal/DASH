<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 11/30/2018
 * Time: 12:25 PM
 */
    session_start();
?>

<td height="700px" style="width: 25%; border-radius: 10px;background-color:#FFFFFF">

    <table width="100%">
        <tbody>
        <tr>
            <td style="font-size: 20px; font-weight: bold">
                Pending Action Items
            </td>
        </tr>
        <tr>
            <td>
                <div style="overflow-y: scroll; height:650px">
                    <table width="100%" class="datatable"  cellpadding="10px">
                        <tbody>
                        <tr valign="center">
                            <th width="33%">
                                Item
                            </th>
                            <th width="33%">
                                Status
                            </th>
                            <th width="34%">
                                Date
                            </th>
                        </tr>
                        <?php
                        $query = 'SELECT * FROM Referrals.Referrals WHERE PatientID=\'' . $patientID . '\' AND Status <> \'10\'';

                        $result = $conReferrals->query($query);
                        while ($row = $result->fetch_row()){
                            echo "<tr onclick=\"window.location='../Referral/CurrentReferral.php?ReferralID=" . $row[0] ."';\"><td>";
                            $query = "SELECT * FROM Status WHERE id='" . $row[3] . "'";
                            $tempResult = $conReferrals->query($query);
                            $tr = $tempResult->fetch_row();
                            echo 'Referral';
                            echo "</td><td>";
                            $query = 'SELECT * FROM Referrals.Provider WHERE ID=\'' . $row[1] . '\'';
                            $temp = $conReferrals->query($query);
                            $temp1 = $temp->fetch_row();
                            echo $tr[1];                            echo "</td><td>";
                            echo DateTime::createFromFormat("Y-m-d H:i:s", $row[7])->format("m/d/Y");
                            echo "</td></tr>";
                        }
                        //input open Rx
                        $query = 'SELECT * FROM Referrals.Rx WHERE PatientID=\'' . $patientID . '\' AND Status <> \'5\'';

                        $result = $conReferrals->query($query);
                        while ($row = $result->fetch_row()){

                            echo "<tr onclick=\"window.location='../Rx/PreviousRx.php?RxId=" . $row[0] ."';\"><td>";
                            //TODO Fix this part
                            echo 'Rx';

                            echo "</td><td>";
                            $query = 'SELECT * FROM Referrals.Provider WHERE ID=\'' . $row[4] . '\'';
                            $temp = $conReferrals->query($query);
                            $temp1 = $temp->fetch_row();
                            echo $temp1[1];
                            echo "</td><td>";
                            echo DateTime::createFromFormat("Y-m-d H:i:s", $row[2])->format("m/d/Y");
                            echo "</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</td>
