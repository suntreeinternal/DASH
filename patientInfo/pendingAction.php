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
                    <table width="100%" class="datatable" id="thisTable" cellpadding="10px">
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
                        $query = 'SELECT * FROM Referrals.Referrals WHERE PatientID=\'' . $patientID . '\' AND Status <> \'4\'';

                        $result = $conReferrals->query($query);
                        while ($row = $result->fetch_row()){
                            echo "<tr onclick=\"window.location='../Referral/CurrentReferral.php?typeID=" . $row[0] ."&type=1';\"><td>";
                            $query = "SELECT * FROM Status WHERE id='" . $row[3] . "'";
                            $tempResult = $conReferrals->query($query);
                            $tr = $tempResult->fetch_row();
                            echo 'Referral';
                            echo "</td><td>";
//                            $query = 'SELECT * FROM Referrals.Provider WHERE ID=\'' . $row[1] . '\'';
//                            $temp = $conReferrals->query($query);
//                            $temp1 = $temp->fetch_row();
                            echo $tr[1];
                            echo "</td><td>";
                            echo DateTime::createFromFormat("Y-m-d H:i:s", $row[7])->format("m/d/Y");
                            echo "</td></tr>";
                        }
                        //input open Rx
                        $query = 'SELECT * FROM Referrals.Rx WHERE PatientID=\'' . $patientID . '\' AND Status <> \'6\'';

                        $result = $conReferrals->query($query);
                        while ($row = $result->fetch_row()){

                            echo "<tr onclick=\"window.location='../Rx/PreviousRx.php?typeID=" . $row[0] ."&type=2';\"><td>";
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

                        //input open Records
                        $query = 'SELECT * FROM Referrals.RecordRequest WHERE PatientID=\'' . $patientID . '\' AND Status <> \'3\'';

                        $result = $conReferrals->query($query);
                        while ($row = $result->fetch_row()){
                            echo "<tr onclick=\"window.location='../RecordRequest/ViewExistingRecordRequest.php?typeID=" . $row[0] ."&type=3';\"><td>";
                            //TODO Fix this part
                            echo 'Record Request';
                            echo "</td><td>";
                            $query = 'SELECT * FROM Referrals.RecordStatus WHERE id=\'' . $row[3] . '\'';
                            $temp = $conReferrals->query($query);
                            $temp1 = $temp->fetch_row();
                            echo $temp1[1];
                            echo "</td><td>";
                            echo DateTime::createFromFormat("Y-m-d H:i:s", $row[6])->format("m/d/Y");
                            echo "</td></tr>";
                        }
                        $query = 'SELECT * FROM Referrals.MedsAuth WHERE PatientID=\'' . $patientID . '\' AND Status <> \'4\'';

                        $result = $conReferrals->query($query);
                        while ($row = $result->fetch_row()){
                            echo "<tr onclick=\"window.location='../MedsAuth/current.php?typeID=" . $row[0] ."&type=4';\"><td>";
                            //TODO Fix this part
                            echo 'Meds Auth';
                            echo "</td><td>";

                            switch ($row[6]){
                                case 0:
                                    echo 'New';
                                    break;
                                case 1:
                                    echo 'Pending';
                                    break;
                                case 2:
                                    echo 'Denial';
                                    break;
                                case 3:
                                    echo 'Other';
                                    break;
                            }
                            echo "</td><td>";
                            echo DateTime::createFromFormat("Y-m-d H:i:s", $row[3])->format("m/d/Y");
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

    <script>
        function sortTable() {
            var table, rows, switching, i, x, y, shouldSwitch;
            table = document.getElementById("thisTable");
            switching = true;
            /*Make a loop that will continue until
            no switching has been done:*/
            while (switching) {
                //start by saying: no switching is done:
                switching = false;
                rows = table.rows;
                /*Loop through all table rows (except the
                first, which contains table headers):*/
                for (i = 1; i < (rows.length - 1); i++) {
                    //start by saying there should be no switching:
                    shouldSwitch = false;
                    /*Get the two elements you want to compare,
                    one from current row and one from the next:*/
                    x = rows[i].getElementsByTagName("TD")[0];
                    y = rows[i + 1].getElementsByTagName("TD")[0];
                    //check if the two rows should switch place:
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        //if so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                    }
                }
                if (shouldSwitch) {
                    /*If a switch has been marked, make the switch
                    and mark that a switch has been done:*/
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                }
            }
        }
    </script>

</td>
