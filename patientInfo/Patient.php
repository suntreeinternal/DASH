<?php
    session_start();
    if (sizeof($_SESSION) == 0){
        header('location:../index.html');
    }
    $patientName = '';
    $DOB = '';
    $phoneNumber = '';
    $last = $_GET['last'];
    $date = $_GET['date'];
    $date = str_ireplace('-', '',$date);

    $con = mssql_connect('sunserver', 'siminternal', 'Watergate2015');
    if (!mssql_select_db('sw_charts', $con)) {
        die('Unable to select database!');
    }
    $query = 'SELECT * FROM dbo.Gen_Demo WHERE last_name=\''. $last .'\' AND birthdate=\''. $date . '\'';
    $result = mssql_query($query);
    $row = mssql_fetch_array($result);
    if (sizeof($row) == 1){
        header('location:/NewPatient/NewPatient.php?last=' . $last . '&date=' . $_GET['date']);
        die();
    }
    $patientID = $row[0];
    $patientName = ($row[2] . " " . $row[1]);
    $DOB = $row['birthdate'];
    $phoneNumber = $row['work_phone'];

    $query = 'SELECT * FROM dbo.Encounters WHERE Patient_ID=\'' . $patientID . '\' ORDER BY visit_date DESC ';
    $result = mssql_query($query);
    $encounters = '<option>Encounters</option>';
    while($row = mssql_fetch_array($result)){
        $encounters .= '<a href="../SoapNote.php?ID=' . $row[0] . '" target=\"_blank\">' . str_ireplace(':00:000', '',$row['visit_date']) . '</a>';
    }

    $con = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
    if($con->connect_error){
        header('location:/index.html');
    } else {
        $query = 'SELECT * FROM Referrals.PatientData WHERE SW_ID=\'' . $patientID . '\'';
        $result = $con->query($query);
        $row = $result->fetch_row();

        if (sizeof($row) == 0){
            $query = 'INSERT INTO PatientData (SW_ID, Message_alert_to_group, Note, Phone_number) VALUES (\'' . $patientID . '\',0,\'\',0)';
            $result = $con->query($query);
        } else {
            $phoneNumber = $row[4];

            if(ctype_digit($phoneNumber) && strlen($phoneNumber) == 10) {
                $phoneNumber = substr($phoneNumber, 0, 3) .'-'. substr($phoneNumber, 3, 3) .'-'. substr($phoneNumber, 6);
            } else {
                if(ctype_digit($phoneNumber) && strlen($phoneNumber) == 7) {
                    $phoneNumber = substr($phoneNumber, 0, 3) .'-'. substr($phoneNumber, 3, 4);
                }
            }
        }
    }
    $_SESSION['currentPatient'] = $patientID;
    ?>


<html>
<head>
    <link rel="stylesheet" href="../Menu/menu.css">
    <style>
        .dropbtn {
            background-color: #4CAF50;
            color: white;
            padding: 16px;
            font-size: 16px;
            border: none;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f1f1f1;
            min-width: 200px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 5px 5px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {background-color: #ddd;}

        .dropdown:hover .dropdown-content {display: block;}

        .dropdown:hover .dropbtn {background-color: #3e8e41;}
    </style>
</head>

<body style="background:darkgray;">
<?php include "../Menu/menu.php"?>
<table style="width: 100%" cellspacing="15" cellpadding="10">
    <tbody>
    <tr>
        <td colspan="4">
            <table style="font-size: 20px" width="100%">
                <tbody>
                <tr>
                    <td width="20%">
                        <?php echo $patientName?>
                    </td>
                    <td width="20%">
                        DOB: <?php echo  date("m-d-Y", strtotime($DOB))?>
                    </td>
                    <td width="20%">
                        <div id="id01">
                            <form action="updatePatient.php" method="get">
                                Phone Number: <input id="phone" type="tel" value="<?php echo $phoneNumber?>" name="phone">
                                <button type="submit" id="update">Update</button>
                            </form>
                        </div>
                    </td>
                    <td width="30%" align="right">
                        <div class="dropdown">
                            <button class="dropbtn">Encounters</button>
                            <div class="dropdown-content">
                                <?php echo $encounters?>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="dropbtn">Other Attachments</button>
                            <div class="dropdown-content">
                            </div>
                        </div>
                    </td>

                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr valign="top">
        <td height="500" style="width: 25%; border-radius: 10px;background-color:#FFFFFF" >
            <div style="overflow-y: auto">
            <table>
                <tbody>
                <tr>
                    <td style="font-size: 20px; font-weight: bold">
                        Phone Records
                    </td>
                </tr>
                </tbody>
            </table>
            </div>
        </td>
        <td style=" width: 25%; border-radius: 10px;background-color:#FFFFFF">
            <div style="overflow-y: scroll; height: 500px">
            <table width="100%">
                <tbody>

                </tbody>
            </table>
            </div>
        </td>
        <td height="500" style="width: 25%; border-radius: 10px;background-color:#FFFFFF">
            <table>
                <tbody>
                <tr>
                    <td style="font-size: 20px; font-weight: bold">
                        Pending Action Items
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
        <td height="500" style="width: 25%; border-radius: 10px;background-color:#FFFFFF">
            <table>
                <tbody>
                <tr>
                    <td style="font-size: 20px; font-weight: bold">
                        Completed Action Items
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td>

        </td>
        <td>
            <form>
                <table width="100%" cellpadding="0px" cellspacing="0px" style="border-radius: 10px">
                    <tbody>
                    <tr>
                        <td colspan="4" >
                            <textarea rows="2" style="border-radius: 10px; resize: none; width: 100%; overflow: auto"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="button" value="MA" style="border-top: 1px solid #96d1f8;
   background: #65a9d7;
   background: -webkit-gradient(linear, left top, left bottom, from(#3e779d), to(#65a9d7));
   background: -webkit-linear-gradient(top, #3e779d, #65a9d7);
   background: -moz-linear-gradient(top, #3e779d, #65a9d7);
   background: -ms-linear-gradient(top, #3e779d, #65a9d7);
   background: -o-linear-gradient(top, #3e779d, #65a9d7);
   padding: 5.5px 11px;
   -webkit-border-radius: 20px;
   -moz-border-radius: 20px;
   border-radius: 20px;
   -webkit-box-shadow: rgba(0,0,0,1) 0 1px 0;
   -moz-box-shadow: rgba(0,0,0,1) 0 1px 0;
   box-shadow: rgba(0,0,0,1) 0 1px 0;
   text-shadow: rgba(0,0,0,.4) 0 1px 0;
   color: white;
   font-size: 14px;
   font-family: Georgia, serif;
   text-decoration: none;
   vertical-align: middle;
   width: 100%">
                        </td>
                        <td>
                            <input type="button" value="Reception" style="border-top: 1px solid #96d1f8;
   background: #65a9d7;
   background: -webkit-gradient(linear, left top, left bottom, from(#3e779d), to(#65a9d7));
   background: -webkit-linear-gradient(top, #3e779d, #65a9d7);
   background: -moz-linear-gradient(top, #3e779d, #65a9d7);
   background: -ms-linear-gradient(top, #3e779d, #65a9d7);
   background: -o-linear-gradient(top, #3e779d, #65a9d7);
   padding: 5.5px 11px;
   -webkit-border-radius: 20px;
   -moz-border-radius: 20px;
   border-radius: 20px;
   -webkit-box-shadow: rgba(0,0,0,1) 0 1px 0;
   -moz-box-shadow: rgba(0,0,0,1) 0 1px 0;
   box-shadow: rgba(0,0,0,1) 0 1px 0;
   text-shadow: rgba(0,0,0,.4) 0 1px 0;
   color: white;
   font-size: 14px;
   font-family: Georgia, serif;
   text-decoration: none;
   vertical-align: middle;
   width: 100%">
                        </td>
                        <td>
                            <input type="button" value="Referrals" style="border-top: 1px solid #96d1f8;
   background: #65a9d7;
   background: -webkit-gradient(linear, left top, left bottom, from(#3e779d), to(#65a9d7));
   background: -webkit-linear-gradient(top, #3e779d, #65a9d7);
   background: -moz-linear-gradient(top, #3e779d, #65a9d7);
   background: -ms-linear-gradient(top, #3e779d, #65a9d7);
   background: -o-linear-gradient(top, #3e779d, #65a9d7);
   padding: 5.5px 11px;
   -webkit-border-radius: 20px;
   -moz-border-radius: 20px;
   border-radius: 20px;
   -webkit-box-shadow: rgba(0,0,0,1) 0 1px 0;
   -moz-box-shadow: rgba(0,0,0,1) 0 1px 0;
   box-shadow: rgba(0,0,0,1) 0 1px 0;
   text-shadow: rgba(0,0,0,.4) 0 1px 0;
   color: white;
   font-size: 14px;
   font-family: Georgia, serif;
   text-decoration: none;
   vertical-align: middle;
   width: 100%">
                        </td>
                        <td>
                            <input type="button" value="Provider" style="border-top: 1px solid #96d1f8;
   background: #65a9d7;
   background: -webkit-gradient(linear, left top, left bottom, from(#3e779d), to(#65a9d7));
   background: -webkit-linear-gradient(top, #3e779d, #65a9d7);
   background: -moz-linear-gradient(top, #3e779d, #65a9d7);
   background: -ms-linear-gradient(top, #3e779d, #65a9d7);
   background: -o-linear-gradient(top, #3e779d, #65a9d7);
   padding: 5.5px 11px;
   -webkit-border-radius: 20px;
   -moz-border-radius: 20px;
   border-radius: 20px;
   -webkit-box-shadow: rgba(0,0,0,1) 0 1px 0;
   -moz-box-shadow: rgba(0,0,0,1) 0 1px 0;
   box-shadow: rgba(0,0,0,1) 0 1px 0;
   text-shadow: rgba(0,0,0,.4) 0 1px 0;
   color: white;
   font-size: 14px;
   font-family: Georgia, serif;
   text-decoration: none;
   vertical-align: middle;
   width: 100%">
                        </td>
                    </tr>
                    </tbody>
                </table>

            </form>
        </td>
    </tr>
    </tbody>
</table>


</body>

</html>