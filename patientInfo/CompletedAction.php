<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 11/30/2018
 * Time: 12:26 PM
 */

session_start();
?>

<td height="700px" style="width: 25%; border-radius: 10px;background-color:#FFFFFF">
    <table width="100%">
        <tbody>
        <tr>
            <td style="font-size: 20px; font-weight: bold">
                Completed Action Items
            </td>
        </tr>
        <tr>
            <td>
                <div style="overflow-y: scroll; height:650px">
                    <table width="100%" class="datatable" cellpadding="10px">
                        <tbody>
                        <tr valign="center">
                            <th width="33%">
                                Item
                            </th>
                            <th width="33%">
                                Provider
                            </th>
                            <th width="34%">
                                Date
                            </th>
                        </tr>

                        <?php
                        $array = array();
                        $query = 'SELECT * FROM Referrals.Referrals WHERE PatientID=\'' . $patientID . '\' AND Status = \'4\'';
                        $result = $conReferrals->query($query);
                        while ($row = $result->fetch_row()){
//                            echo "<tr onclick=\"window.location='../Referral/CurrentReferral.php?typeID=" . $row[0] ."&type=1';\"><td>";
//                            echo "Referral";
//                            echo "</td><td>";
                            $query = 'SELECT * FROM Referrals.Specialty WHERE ID=\'' . $row[8] . '\'';
                            $temp = $conReferrals->query($query);
                            $temp1 = $temp->fetch_row();
//                            echo $temp1[1];
//                            echo "</td><td>";
//                            echo DateTime::createFromFormat("Y-m-d H:i:s", $row[7])->format("m/d/Y");
//                            echo "</td></tr>";
                            $toAdd = array(DateTime::createFromFormat("Y-m-d H:i:s", $row[7])->format("Ymd"), DateTime::createFromFormat("Y-m-d H:i:s", $row[7])->format("m/d/Y"),'Referral', $temp1[1], "<tr onclick=\"window.location='../Referral/CurrentReferral.php?typeID=" . $row[0] ."&type=1';\"><td>");
                            array_push($array,$toAdd);
                        }
//                        var_dump($array);
                        $query = 'SELECT * FROM Referrals.RecordRequest WHERE PatientID=\'' . $patientID . '\' AND Status = \'3\'';
                        $result = $conReferrals->query($query);
                        while ($row = $result->fetch_row()){
//                            echo "<tr onclick=\"window.location='../RecordRequest/ViewExistingRecordRequest.php?typeID=" . $row[0] ."&type=2';\"><td>";
//                            echo "Records Request";
//                            echo "</td><td>";
//                            echo "</td><td>";
//                            echo DateTime::createFromFormat("Y-m-d H:i:s", $row[6])->format("m/d/Y");
//                            echo "</td></tr>";
                            $toAdd = array(DateTime::createFromFormat("Y-m-d H:i:s", $row[6])->format("Ymd"),DateTime::createFromFormat("Y-m-d H:i:s", $row[6])->format("m/d/Y"),'Records Request', '', "<tr onclick=\"window.location='../RecordRequest/ViewExistingRecordRequest.php?typeID=" . $row[0] ."&type=2';\"><td>");
                            array_push($array,$toAdd);

                        }

                        //TODO change link
                        $query = 'SELECT * FROM Referrals.MedsAuth WHERE PatientID=\'' . $patientID . '\' AND Status = \'4\'';
                        $result = $conReferrals->query($query);
                        while ($row = $result->fetch_row()){
//                            echo "<tr onclick=\"window.location='../MedsAuth/current.php?typeID=" . $row[0] ."&type=3';\"><td>";
//                            echo "Meds Auth";
//                            echo "</td><td>";
                            $query = 'SELECT * FROM Referrals.Provider WHERE ID=\'' . $row[2] . '\'';
                            $temp = $conReferrals->query($query);
                            $temp1 = $temp->fetch_row();
//                            echo $temp1[1];
//                            echo "</td><td>";
//                            echo DateTime::createFromFormat("Y-m-d H:i:s", $row[3])->format("m/d/Y");
//                            echo "</td></tr>";
                            $toAdd = array(DateTime::createFromFormat("Y-m-d H:i:s", $row[3])->format("Ymd"),DateTime::createFromFormat("Y-m-d H:i:s", $row[3])->format("m/d/Y"), 'Meds Auth', $temp1[1], "<tr onclick=\"window.location='../MedsAuth/current.php?typeID=" . $row[0] ."&type=3';\"><td>");
                            array_push($array,$toAdd);
                        }
                        $query = 'SELECT * FROM Referrals.Rx WHERE PatientID=\'' . $patientID . '\' AND Status = \'6\'';

                        $result = $conReferrals->query($query);
                        while ($row = $result->fetch_row()){

//                            echo "<tr onclick=\"window.location='../Rx/PreviousRx.php?typeID=" . $row[0] ."&type=2';\"><td>";
//                            echo 'Rx';

//                            echo "</td><td>";
                            $query = 'SELECT * FROM Referrals.Provider WHERE ID=\'' . $row[4] . '\'';
                            $temp = $conReferrals->query($query);
                            $temp1 = $temp->fetch_row();
//                            echo $temp1[1];
//                            echo "</td><td>";
//                            echo DateTime::createFromFormat("Y-m-d H:i:s", $row[2])->format("m/d/Y");
//                            echo "</td></tr>";
                            $toAdd = array(DateTime::createFromFormat("Y-m-d H:i:s", $row[2])->format("Ymd"),DateTime::createFromFormat("Y-m-d H:i:s", $row[2])->format("m/d/Y"),'Rx', $temp1[1], "<tr onclick=\"window.location='../Rx/PreviousRx.php?typeID=" . $row[0] ."&type=2';\"><td>");
                            array_push($array,$toAdd);
                        }
                        array_multisort($array, SORT_DESC);
//                        var_dump($array);

                        foreach ($array as $item) {
//                            var_dump($item);
                            echo $item[4];
                            echo $item[2];
                            echo "</td><td>";
                            $query = 'SELECT * FROM Referrals.Provider WHERE ID=\'' . $row[4] . '\'';
                            $temp = $conReferrals->query($query);
                            $temp1 = $temp->fetch_row();
                            echo $item[3];
                            echo "</td><td>";
                            echo $item[1];
                            echo "</td></tr>";
                        }
                        ?>
                        <script>
                            sortByDate()
                        </script>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</td>
