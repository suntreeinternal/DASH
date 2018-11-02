<?php
    //TODO make certain items visible to only some groups-
    session_start();
?>

<html style='height: 100%'>
  <head>
        <link rel="stylesheet" href="Menu/menu.css">
        <title>DASH: <?php echo $_SESSION['name']?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        </head>
  <style>
        .modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 400px;
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
        #customers {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #customers td, #customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #customers tr:nth-child(even){background-color: #f2f2f2;}

        #customers tr:hover {background-color: #ddd;}

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #4CAF50;
            color: white;
        }
        </style>
  <body style="background:darkgray; height: 100%">
<?php include "Menu/menu.php";?>
<table style="height: 400px" width="100%" cellpadding="10px" cellspacing="5px" >
    <tbody>
        <tr>
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
                                                       <h3>User Rights</h3>
                                                    </td>
                                                    <td>
                                                       <h3>User Group</h3>
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
                                                echo "<tr>
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
            <td width="25%" bgcolor="white" valign="top" style="border-radius: 15px">
                <table width="100%">
                    <tbody>
                    <tr>
                        <td width="50%">
                            <div style="font-size: 25px">Providers</div>
                        </td>
                        <td width="50%" align="right">
                            <button>Add Provider</button>
                            <input type="button" value="Edit Provider">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="overflow-y: scroll">
                            <br/>
                            <table width="100%" id="customers">
                                <tbody">
                                <tr>
                                    <th>Provider</th>
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
            <td width="25%" bgcolor="white" valign="top" style="border-radius: 15px; height: 400px">
                <table width="100%">
                    <tbody>
                    <tr>
                        <td width="50%">
                            <div style="font-size: 25px">Specialist</div>
                        </td>
                        <td width="50%" align="right">
                            <button>Add Specialist</button>
                            <input type="button" value="Edit Specialist">
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
                                    echo "<tr>
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
            <td width="25%" bgcolor="white" valign="top" style="border-radius: 15px">
                <table width="100%">
                    <tbody>
                    <tr>
                        <td width="50%">
                            <div style="font-size: 25px">Specialty</div>
                        </td>
                        <td width="50%" align="right">
                            <button>Add Specialty</button>
                            <input type="button" value="Edit Specialty">
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
        </tr>
    </tbody>
</table>

</body>
</html>