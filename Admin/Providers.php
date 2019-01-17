<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 12/3/2018
 * Time: 1:46 PM
 */

session_start();
?>
<td width="25%" bgcolor="white" valign="top" style="border-radius: 15px">
    <table width="100%">
        <tbody>
        <tr>
            <td width="50%">
                <div style="font-size: 25px">Providers</div>
            </td>
            <td width="50%" align="right">
                <form method="post" action="AddProviderForm.php">
                    <input type="submit" value="Add Provider">
                </form>

            </td>
        </tr>
        <tr>
            <td colspan="2" style="overflow-y: scroll">
                <br/>
                <table width="100%" id="customers">
                    <tbody">
                    <tr style="width: 85%;">
                        <th>Provider</th>
                        <th>Active</th>
                    </tr>

                    <?php
                    $con = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
                    if($con->connect_error){
                        header('location:/index.html');
                    } else {
                        $query = 'SELECT * FROM Referrals.Provider';
                    }
                    $result = $con->query($query);
                    while ($row = $result->fetch_row()){
                        $val  = 'Yes';
                        if ($row[3] == 0){
                            $val = 'No';
                        }
//                        echo var_dump($row);
                        echo "<tr onclick=\"window.location='../Admin/CheckIfMessages.php?providerID=" . $row[0] . "'\"><td>" . $row[1] . "</td><td>" . $val . "</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</td>
