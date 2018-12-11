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
                                Status
                            </th>
                            <th width="33%">
                                Provider
                            </th>
                            <th width="34%">
                                Date
                            </th>
                        </tr>
                        <?php
                        $query = 'SELECT * FROM Referrals.Referrals WHERE PatientID=\'' . $patientID . '\' AND Status <> \'10\'';

                        //                                        $query = ' . $patientID . '\' AND Status <> \'10\'';
                        $result = $conReferrals->query($query);
                        while ($row = $result->fetch_row()){
                            echo "<tr onclick=\"window.location='../Referral/CurrentReferral.php?ReferralID=" . $row[0] ."';\"><td>";

                            $tempStatus = $row[3];
                            switch ($tempStatus){
                                case '0':
                                    echo "Pending Demo";
                                    break;

                                case '1':
                                    echo "Pending Soap";
                                    break;
                                case '2':
                                    echo "Pending Insurance Authorization";
                                    break;
                                case '3':
                                    echo "Pending Specialist Review";
                                    break;
                                case '4':
                                    echo "Pending Appointment From Specialist";
                                    break;
                                case '5':
                                    echo "Pending Couldn't  be Reached by Specialist";
                                    break;
                                case '6':
                                    echo "Pending Declined by Specialist";
                                    break;
                                case '7':
                                    echo "Pending Insurance doesn't cover";
                                    break;
                                case '8':
                                    echo "Patient Declined Appointment";
                                    break;
                                case '9':
                                    echo "Provider to Referral";
                                    break;
                                case '10':
                                    echo "Completed";
                                    break;
                                case '11':
                                    echo "Unsuccessful contact";
                                    break;
                            }
                            echo "</td><td>";
                            $query = 'SELECT * FROM Referrals.Provider WHERE ID=\'' . $row[1] . '\'';
                            $temp = $conReferrals->query($query);
                            $temp1 = $temp->fetch_row();
                            echo $temp1[1];
                            echo "</td><td>";
                            echo DateTime::createFromFormat("Y-m-d H:i:s", $row[7])->format("m/d/Y");
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
