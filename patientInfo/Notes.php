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
                    Referral Notes
                </td>
            </tr>
                <?php
            $query = 'SELECT * FROM Note WHERE ReferralID=\'' . $_GET['ReferralID'] . '\' ORDER BY ID DESC' ;
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
                        echo "<tr style=\"background-color: #E74C3C\">";
                        break;

                    case 'Referrals':
                        echo "<tr style=\"background-color: #BB8FCE\">";
                        break;

                    case 'MA':
                        echo "<tr style=\"background-color: #45B39D\">";
                        break;

                }
                echo "
                                <td style=\"border-radius: 7px\" colspan='2'>
                                    " . $row[2] . " " . $row[3] . " <br/> " . $row[4] . "
                                </td>
                            </tr>
                        ";
            }
                ?>

            </tbody>
        </table>
    </div>
</td>
