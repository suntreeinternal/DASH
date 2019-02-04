<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 12/3/2018
 * Time: 1:46 PM
 */
session_start();
?>

<td width="25%" bgcolor="white" valign="top" style="border-radius: 15px;">
    <table width="100%" >
        <tbody>
        <tr>
            <td width="50%">
                <div style="font-size: 25px">Users</div>
            </td>
            <td width="50%" align="right">
                <form method="get" action="addUserBest.php">
                    <button id="addUser" type="submit">Add User</button>
                </form>

            </td>
        </tr>
        <tr>
            <td colspan="2" style="overflow-y: scroll">
                <br/>
                <table width="100%" id="customers">
                    <tbody">
                    <tr>
                        <th>First</th>
                        <th>Last</th>
                        <th>User</th>
                    </tr>
                    <?php
                    $con = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
                    if($con->connect_error){
                        header('location:/index.html');
                    } else {
                        $query = 'SELECT * FROM Referrals.Users ORDER BY LastName ASC ';
                    }

                    $result = $con->query($query);
                    while ($row = $result->fetch_row()){
                        //TODO add to Change log
                        echo "<tr onclick=\"window.location='updateUser.php?user=" . $row[3] . "&first=" . $row[4] ."&last=" . $row[5] ."&pass=" . $row[6] . "'\">
                                                        <td>" . $row[4] . "</td> 
                                                        <td>" . $row[5] . "</td>
                                                        <td>" . $row[3] . "</td>
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