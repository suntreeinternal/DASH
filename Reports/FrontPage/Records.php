<?php

/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 12/11/2018
 * Time: 7:30 AM
 */
session_start();
$con = mssql_connect('sunserver', 'siminternal', 'Watergate2015');
$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
if (!mssql_select_db('sw_charts', $con)) {
    die('Unable to select database!');
}
?>


<html>
<head>
    <link rel="stylesheet" href="../../Menu/menu.css">
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
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #dddddd;*/
        border: 1px solid #a9a9a9;

            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;

        }
        tr:nth-child(odd) {
            background-color: #FFFFFF;

        }
    </style>
</head>

<body style="background:darkgray;">
<?php include "../../Menu/menu.php"?>
<table id="All" width="100%">
    <tbody>
    <tr>
        <th onclick="sortTable(0)" width="12%">Patient Name</th>
        <th onclick="sortTable(1)" width="12%">Status</th>
        <th onclick="sortTable(2)" width="10%">Date Requested</th>
        <th onclick="sortTable(3)" width="10%">Phone number</th>
        <th onclick="sortTable(4)" width="18%">Requesting Party</th>
        <th onclick="sortTable(5)" width="50%">Authorization</th>
    </tr>
    <?php
    $query = $_GET['query'];
    if ($query != "temp"){
        $result = $conReferrals->query($query);
        while ($row = $result->fetch_row()) {
            $dob = null;
            $query = 'SELECT * FROM Referrals.PatientData WHERE ID="' . $row[1] . '"';
            $temp = $conReferrals->query($query);
            $tr = $temp->fetch_row();
            $id = "" . $tr[1];
            $phone = $tr[4];
            $_SESSION['currentPatient'] = $tr[0];


            if (strpos($id, "-") == 8) {
                $con = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
                $query = 'SELECT * FROM dbo.Gen_Demo WHERE Patient_ID=\'' . $tr[1] . '\'';
                $temp = mssql_query($query);
                $tr = mssql_fetch_array($temp);
                $_SESSION['patientName'] = $tr[2] . " " . $tr[1];
                $_SESSION['patientDOB'] = $tr[21];
                echo "<tr onclick=\"window.location='/RecordRequest/ViewExistingRecordRequest.php?last=" . $tr['last_name'] . "&date=" . $tr['birthdate'] . "';\"><td>";

                echo $tr[2] . " " . $tr[1];
                $dob = $tr[21];
            } else {
                $query = 'SELECT * FROM Referrals.TempPatient WHERE ID="' . $tr[1] . '"';
                $temp = $conReferrals->query($query);
                $tr = $temp->fetch_row();
                $_SESSION['patientName'] = $tr[1] . " " . $tr[2];
                $_SESSION['patientDOB'] = $tr[3];
                echo "<tr onclick=\"window.location='/patientInfo/Patient.php?last=" . $tr[2] . "&date=" . $tr[3] . "';\"><td>";
                echo $tr[1] . " " . $tr[2];
                $dob = $tr[3];
            }
            echo "</td><td>";
            $query = "SELECT * FROM Referrals.RecordStatus WHERE id=" . $row[3];
            $getStatus = $conReferrals->query($query);
            $status = $getStatus->fetch_row();
            echo $status[1];
            echo "</td><td>";
            $date = date_create($row[8]);
            echo date_format($date, "m/d/Y");
            echo "</td><td>";
            if (ctype_digit($phone) && strlen($phone) == 10) {

                $phone = "(" . substr($phone, 0, 3) . ') ' . substr($phone, 3, 3) . '-' . substr($phone, 6);
            } else {

                if (ctype_digit($phone) && strlen($phone) == 7) {
                    $phone = substr($phone, 0, 3) . '-' . substr($phone, 3, 4);
                }
            }

            echo $phone;

            echo "</td><td>";

            switch ($row[3]) {
                case 0:
                    echo "Doctors Office";
                    break;

                case 1:
                    echo "Attorney";
                    break;

                case 2:
                    echo "Patient";
                    break;

                case 3:
                    echo "SSI";
                    break;

                case 4:
                    echo "Life / Health Insurance";
                    break;
            }

            echo "</td><td>";

                switch ($row[4]) {
                    case 1:
                        echo "Yes";
                        break;

                    case 2:
                        echo "No";
                        break;

                    case 3:
                        echo "N/A";
                        break;

                    case 4:
                        echo "Unknown";
                        break;
                }

            echo "</td></tr>";
        }
    }
    ?>
    </tbody>
</table>
</body>
</html>