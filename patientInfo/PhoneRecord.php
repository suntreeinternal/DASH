<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 11/30/2018
 * Time: 12:19 PM
 */
    session_start();
?>
<td height="700px" style="width: 25%; border-radius: 10px;background-color:#FFFFFF" id="testPhone" >
            <div style="overflow-y: scroll; height:650px">
                <table width="100%" cellspacing="10px" cellpadding="5px" >
                    <tbody>
                    <tr>
                        <td style="font-size: 20px; font-weight: bold" width="50%">
                            Phone Records
                        </td>
                    </tr>
                    <?php
                    $query = 'SELECT * FROM PatientPhoneMessages WHERE PatientID=\'' . $_SESSION["currentPatient"]. '\' AND ParrentMessage=\'0\' ORDER BY ID DESC' ;
                    $result = $conReferrals->query($query);
                    while ($row = $result->fetch_row()){
                        echo "<tr><td><table style='background-color: rgb(87,87,87); border-radius: 10px; padding: 5px' width='100%' ><tbody>";
                        $messageGroup = $row[5];
                        switch ($messageGroup){
                            case 'Admin':
                                echo "<tr style=\"background-color: #0066ff\">";
                                break;

                            case 'Reception':
                                echo "<tr style=\"background-color: #F4D03F\">";
                                break;

                            case 'Provider':
                                echo "<tr style=\"background-color: #E74C3C; color: #FFFFFF\">";
                                break;

                            case 'Referrals':
                                echo "<tr style=\"background-color: #BB8FCE\">";
                                break;

                            case 'MA':
                                echo "<tr style=\"background-color: #45B39D\">";
                                break;
                        }


                        $date = date_create($row[3]);
                        if ($row[6] == null){
                            echo "
                                <td style=\"border-radius: 7px\" colspan='2' style=\"background-color: #45B39D\" onclick=\"window.location='/patientInfo/AddMessageToPhoneConversation.php?parent=" . $row[0] . "';\">
                                    " . $row[2] . " " . date_format($date, 'm/d/Y H:i:s') . " <br/> " . $row[4] . "
                                </td>
                            </tr>
                        ";
                        } else {
                            if ($row[6] < 5){
                                switch ($row[6]){
                                    case 0:
                                        $style = "background-color: #45B39D";
                                        break;

                                    case 1:
                                        $style = "background-color: #F4D03F";
                                        break;

                                    case 2:
                                        $style = "background-color: #BB8FCE";
                                        break;
                                }
                            } else {
                                $val = $row[6] - 4;
                                $query = "SELECT * FROM Referrals.Provider WHERE ID='" . $val . "'";
                                $result1 = $conReferrals->query($query);
                                $provider = $result1->fetch_row();
                                $style = "background-color: #" . $provider[4] . "; color: #" . $provider[5];
                            }
                            echo "
                                    <td style=\"border-radius: 7px\" width='80%' style=\"background-color: #45B39D\" onclick=\"window.location='/patientInfo/AddMessageToPhoneConversation.php?parent=" . $row[0] . "';\">
                                        " . $row[2] . " " . date_format($date, 'm/d/Y H:i:s') . " <br/> " . $row[4] . "
                                    </td>
                                    <td style=\"border-radius: 7px; " . $style . "\" width='20%' onclick=\"window . location = '../patientInfo/phoneMessageReplie.php?messageID=" . $row[0] . "';\">
                                        Mark Viewed
                                    </td>
                                </tr>
                            ";
                        }

                        $query = 'SELECT * FROM PatientPhoneMessages WHERE ParrentMessage=\'' . $row[0] . '\' ORDER BY ID ASC' ;
                        $findChild = $conReferrals->query($query);
                        while ($childRow = $findChild->fetch_row()) {
                            $messageGroup = $childRow[5];
                            switch ($messageGroup){
                                case 'Admin':
                                    echo "<tr style=\"background-color: #0066ff\">";
                                    break;

                                case 'Reception':
                                    echo "<tr style=\"background-color: #F4D03F\">";
                                    break;

                                case 'Provider':
                                    echo "<tr style=\"background-color: #E74C3C; color: #FFFFFF\">";
                                    break;

                                case 'Referrals':
                                    echo "<tr style=\"background-color: #BB8FCE\">";
                                    break;

                                case 'MA':
                                    echo "<tr style=\"background-color: #45B39D\">";
                                    break;
                            }
                            $date = date_create($childRow[3]);
                            if ($childRow[6] == null){
                                echo "
                                <td style=\"border-radius: 7px\" colspan='2' style=\"background-color: #45B39D\" onclick=\"window.location='/patientInfo/AddMessageToPhoneConversation.php?parent=" . $row[0] . "';\">
                                    " . $childRow[2] . " " . date_format($date, 'm/d/Y H:i:s') . " <br/> " . $childRow[4] . "
                                </td>
                            </tr>
                        ";
                            } else {
                                if ($childRow[6] < 5){
                                    switch ($childRow[6]){
                                        case 0:
                                            $style = "background-color: #45B39D";
                                            break;

                                        case 1:
                                            $style = "background-color: #F4D03F";
                                            break;

                                        case 2:
                                            $style = "background-color: #BB8FCE";
                                            break;
                                    }
                                } else {
                                    $val = $childRow[6] - 4;
                                    $query = "SELECT * FROM Referrals.Provider WHERE ID='" . $val . "'";
                                    $result1 = $conReferrals->query($query);
                                    $provider = $result1->fetch_row();
                                    $style = "background-color: #" . $provider[4] . "; color: #" . $provider[5];
                                }
                                echo "
                                    <td style=\"border-radius: 7px\" width='80%' style=\"background-color: #45B39D\" onclick=\"window.location='/patientInfo/AddMessageToPhoneConversation.php?parent=" . $row[0] . "';\">
                                        " . $childRow[2] . " " . date_format($date, 'm/d/Y H:i:s') . " <br/> " . $childRow[4] . "
                                    </td>
                                    <td style=\"border-radius: 7px; " . $style . "\" width='20%' onclick=\"window . location = '../patientInfo/phoneMessageReplie.php?messageID=" . $childRow[0] . "';\">
                                        Mark Viewed
                                    </td>
                                </tr>
                            ";
                            }
                        }
                        echo "</tbody></table></td></tr>";
                    }
                    ?>

                    </tbody>
                </table>
            </div>
        </td>