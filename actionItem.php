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
$_SESSION['previous'] = "patientInfo/Patient.php?last=" . $last . "&date=" . $date;
$date = str_ireplace('-', '',$date);
$con = mssql_connect('sunserver', 'siminternal', 'Watergate2015');
$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
if (!mssql_select_db('sw_charts', $con)) {
    die('Unable to select database!');
}
$query = 'SELECT * FROM dbo.Gen_Demo WHERE last_name=\''. $last .'\' AND birthdate=\''. $date . '\'';
$result = mssql_query($query);
$row = mssql_fetch_array($result);
if (sizeof($row) == 1){
    $query = 'SELECT * FROM Referrals.TempPatient WHERE LastName=\''. $last . '\' AND BirthDate=\''. $date . '\'';
    $result = $conReferrals->query($query);
    $row = $result->fetch_row();
    if (sizeof($row) == 0) {
        header('location:/NewPatient/NewPatient.php?last=' . $last . '&date=' . $_GET['date']);
        die();
    } else {
        $patientID = $row[0];
        $patientName = $row[1] . " " . $row[2];
        $DOB = $row[3];
    }
} else {
    $patientID = $row[0];
    $patientName = ($row[2] . " " . $row[1]);
    $DOB = $row['birthdate'];
    $phoneNumber = $row['work_phone'];
    $query = 'SELECT * FROM dbo.Encounters WHERE Patient_ID=\'' . $patientID . '\' ORDER BY visit_date DESC ';
    $result = mssql_query($query);
    $encounters = "";
    while ($row = mssql_fetch_array($result)) {
        $encounters .= '<a href="../SoapNote.php?ID=' . $row[0] . '" target=\"_blank\">' . str_ireplace(':00:000', '', $row['visit_date']) . '</a>';
    }
}

if($con->connect_error){
    header('location:/index.html');
} else {
    $query = 'SELECT * FROM Referrals.PatientData WHERE SW_ID=\'' . $patientID . '\'';
    $result = $conReferrals->query($query);
    $row = $result->fetch_row();

    if (sizeof($row) == 0){
        $query = 'INSERT INTO PatientData (SW_ID, Message_alert_to_group, Note, Phone_number) VALUES (\'' . $patientID . '\',0,\'\',0)';
        $result = $conReferrals->query($query);
    } else {
        $phoneNumber = $row[4];
        $alert = $row[2];

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

        .btnRec {
            border-radius: 20px;
            width: 100%;
            height:30px;
            background-color: #F4D03F;
            color: black;
            font-size: 16px;
            border: none;
        }

        .btnRec:hover {
            background-color: #f1c40e;
        }

        .btnMa {
            border-radius: 20px;
            width: 100%;
            height:30px;
            background-color: #45B39D;
            color: black;
            font-size: 16px;
            border: none;
        }

        .btnMa:hover {
            background-color: #399381;
        }

        .btnRef {
            border-radius: 20px;
            width: 100%;
            height:30px;
            background-color: #BB8FCE;
            color: black;
            font-size: 16px;
            border: none;
        }

        .btnRef:hover {
            background-color: #a971c1;
        }

        .btnPro {
            border-radius: 20px;
            width: 100%;
            height:30px;
            background-color: #E74C3C;
            color: black;
            font-size: 16px;
            border: none;
        }

        .btnPro:hover {
            background-color: #e3301c;
        }

        .btnOthers {
            border-radius: 20px;
            width: 100%;
            height:30px;
            background-color: #4CAF50;
            color: black;
            font-size: 16px;
            border: none;
        }

        .btnOthers:hover {
            background-color: #3e8e41;
        }
    </style>
</head>

<body style="background:darkgray;">
<?php include "Menu/menu.php"?>
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
                                <a href="uploadFile.php">Upload attachment</a>
                            </div>
                        </div>
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