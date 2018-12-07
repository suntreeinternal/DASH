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
                <div style="font-size: 25px">Specialty</div>
            </td>
            <td width="50%" align="right">
                <button>Add Specialty</button>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="overflow-y: scroll">
                <br/>
                <table width="100%" id="customers">
                    <tbody">
                    <tr>
                        <th>Type</th>
                    </tr>
                    <?php
                    $con = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
                    if($con->connect_error){
                        header('location:/index.html');
                    } else {
                        $query = 'SELECT * FROM Referrals.Specialty';
                    }
                    $result = $con->query($query);
                    while ($row = $result->fetch_row()){
                        echo "<tr>
                                          <td>" . $row[1] . "</td> 
                                          </tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</td>
