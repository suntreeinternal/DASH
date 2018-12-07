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
                <button id="addUser">Add User</button>
                <div id="myModal" class="modal">

                    <!-- Modal content -->
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <form action="addUser.php" method="get">
                            <table width="100%">
                                <tbody>
                                <tr>
                                    <td width="50%">
                                        <input type="text" placeholder="First Name" name="first">
                                    </td>
                                    <td width="50%">
                                        <input type="text" placeholder="Last Name" name="last">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="text" placeholder="User Name" name="user">
                                    </td>
                                    <td>
                                        <input type="password" placeholder="Password" name="pass">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h3>User Group</h3>
                                    </td>
                                    <td>
                                        <h3>User Rights</h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Admin <input type="radio" name="group" value="adminGroup">
                                    </td>
                                    <td>
                                        Super Admin <input type="radio" name="rights" value="superAdminRights">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Reception <input type="radio" name="group" value="receptionGroup">
                                    </td>
                                    <td>
                                        Admin <input type="radio" name="rights" value="adminRights">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Provider <input type="radio" name="group" value="providerGroup">
                                    </td>
                                    <td>
                                        Records <input type="radio" name="rights" value="recordRights">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Referrals<input type="radio" name="group" value="referralsGroup">
                                    </td>
                                    <td>
                                        Read/Write <input type="radio" name="rights" value="rwRights">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        MA <input type="radio" name="group" value="maGroup">
                                    </td>
                                    <td>
                                        Read Only <input type="radio" name="rights" value="readRights">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Inactive <input type="radio" name="group" value="inactiveGroup">

                                    </td>
                                    <td>
                                        <input type="submit" value="Create New User">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>

                </div>

                <script>
                    // Get the modal
                    var modal = document.getElementById('myModal');

                    // Get the button that opens the modal
                    var btn = document.getElementById("addUser");

                    // Get the <span> element that closes the modal
                    var span = document.getElementsByClassName("close")[0];

                    // When the user clicks the button, open the modal
                    btn.onclick = function() {
                        modal.style.display = "block";
                    }

                    // When the user clicks on <span> (x), close the modal
                    span.onclick = function() {
                        modal.style.display = "none";
                    }

                    // When the user clicks anywhere outside of the modal, close it
                    window.onclick = function(event) {
                        if (event.target == modal) {
                            modal.style.display = "none";
                        }
                    }
                </script>

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
                        $query = 'SELECT * FROM Referrals.Users';
                    }
                    $result = $con->query($query);
                    while ($row = $result->fetch_row()){
                        echo "<tr onclick=\"window.location='updateUser.php?user=" . $row[3] . "&first=" . $row[4] ."&last=" . $row[5] ."'\">
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