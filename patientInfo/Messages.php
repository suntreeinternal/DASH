<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 11/30/2018
 * Time: 12:24 PM
 */
session_start();
?>
<td style=" width: 25%; border-radius: 10px;background-color:#FFFFFF">
    <div style="overflow-y: scroll; height:650px">
        <table width="100%" cellspacing="10px" cellpadding="5px" >
            <tbody>
            <tr>
                <td style="font-size: 20px; font-weight: bold" width="50%">
                    Notes about patient
                </td>
                <td align="right">
                    <?php
                    switch ($alert){
                        case(2):
                            echo "Reception";
                            break;
                        case(3):
                            echo "Provider";
                            break;
                        case(4):
                            echo "Referrals";
                            break;
                        case(5):
                            echo "MA";
                            break;

                    }
                    ?>
                </td>
            </tr>
            <?php
            $query = 'SELECT * FROM MessageAboutPatient WHERE PatientID=\'' . $_SESSION["currentPatient"]. '\' ORDER BY ID DESC' ;
            $result = $conReferrals->query($query);
            while ($row = $result->fetch_row()){
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
                        echo "<tr style=\"background-color: #45B39D; color: #FFFFFF\">";
                        break;

                }
                $date = date_create($row[3]);
                    echo "
                                <td style=\"border-radius: 7px\" colspan='2'>
                                    " . $row[2] . " " . date_format($date, 'm/d/Y H:i:s') . " <br/> " . $row[4] . "
                                </td>
                            </tr>
                        ";
            }
            ?>

            </tbody>
        </table>
    </div>
</td>
