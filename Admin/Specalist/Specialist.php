<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 12/3/2018
 * Time: 1:46 PM
 */

session_start();
?>

<td width="25%" bgcolor="white" valign="top" style="border-radius: 15px; height: 400px">
    <table width="100%">
        <tbody>
        <tr>
            <td width="50%">
                <div style="font-size: 25px">Specialist</div>
            </td>
            <td width="50%" align="right">
                <form action="Specalist/newSpecalist.php">
                    <input type="submit" name="Add Specialist" value="Add Specialist">
                </form>
<!--                <input type="button" value="Edit Specialist">-->
            </td>
        </tr>
        <tr>
            <td colspan="2" style="overflow-y: scroll">
                <br/>
                <table width="100%" id="customers">
                    <tbody">
                    <tr>
                        <th>Name</th>
                    </tr>
                    <?php
                    $con = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
                    if($con->connect_error){
                        header('location:/index.html');
                    } else {
                        $query = 'SELECT * FROM Referrals.Specialist';
                    }
                    $result = $con->query($query);
                    while ($row = $result->fetch_row()){
                        echo "<tr onclick=\"window.location='Specalist/editSpecialist.php?id=" . $row[0] . "'\">
                                <td>" . $row[2] . "</td>
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
