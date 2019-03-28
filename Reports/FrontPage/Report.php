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
        <th onclick="sortTable(0)" width="16%">Patient Name</th>
        <th onclick="sortTable(1)" width="10%">DOB</th>
        <th onclick="sortTable(2)" width="12%">Reason</th>
        <th onclick="sortTable(3)" width="14%">Specialist</th>
        <th onclick="sortTable(4)" width="12%">Phone number</th>
        <th onclick="sortTable(5)" width="12%">Specialty</th>
        <th onclick="sortTable(6)" width="10%">Priority</th>
        <th onclick="sortTable(7)" width="14%">Date Sent</th>

    </tr>
    <?php
    $query = $_GET['query'];
    if ($query != "temp"){
        $result = $conReferrals->query($query);
        while ($row = $result->fetch_row()) {
            $dob = null;
            echo "<tr onclick=\"window.location='/Reports/GetPatient.php?typeID=" . $row[0] . "&type=1';\"><td>";
            $query = 'SELECT * FROM Referrals.PatientData WHERE ID="' . $row[2] . '"';
            $temp = $conReferrals->query($query);
            $tr = $temp->fetch_row();
            $id = "" . $tr[1];
            $_SESSION['currentPatient'] = $tr[0];


            if (strpos($id, "-") == 8) {
                $con = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
                $query = 'SELECT * FROM dbo.Gen_Demo WHERE Patient_ID=\'' . $id . '\'';
                $temp = mssql_query($query);
                $tr = mssql_fetch_array($temp);
                echo $tr[2] . " " . $tr[1];
                $dob = $tr[21];
            } else {
                $query = 'SELECT * FROM Referrals.TempPatient WHERE ID="' . $tr[1] . '"';
                $temp = $conReferrals->query($query);
                $tr = $temp->fetch_row();
                echo $tr[1] . " " . $tr[2];
                $dob = $tr[3];
            }
            echo "</td><td>";
            $date = date_create($dob);
            echo date_format($date, "m/d/Y");
            echo "</td><td>";
            echo $row[6];
            echo "</td><td>";
            $query = 'SELECT * FROM Referrals.Specialist WHERE ID="' . $row[9] . '"';
            $temp = $conReferrals->query($query);
            $tr = $temp->fetch_row();
            echo $tr[2];
            echo "</td><td>";
            if (ctype_digit($tr[4]) && strlen($tr[4]) == 10) {

                $tr[4] = substr($tr[4], 0, 3) . '-' . substr($tr[4], 3, 3) . '-' . substr($tr[4], 6);
            } else {

                if (ctype_digit($tr[4]) && strlen($tr[4]) == 7) {
                    $tr[4] = substr($tr[4], 0, 3) . '-' . substr($tr[4], 3, 4);
                }
            }
            echo $tr[4];

            echo "</td><td>";
            $query = 'SELECT * FROM Referrals.Specialty WHERE ID="' . $row[8] . '"';
            $temp = $conReferrals->query($query);
            $tr = $temp->fetch_row();
            echo $tr[1];
            echo "</td><td>";
            switch ($row[4]) {
                case 1:
                    echo 'ASAP';
                    break;

                case 2:
                    echo 'Complete Date';
                    break;

                case 3:
                    echo 'Routine';
                    break;

                case 4:
                    echo 'Patient Referral';
                    break;
            }

            echo "</td><td>";
            if ($row[10] == ""){
                echo "Has not been sent yet";
            } else {
                $date = date_create($row[10]);
                echo date_format($date, "m/d/Y");
            }
            echo "</td></tr>";
        }
    } else {
        $result = $conReferrals->query("SELECT * FROM TempPatient");
        while ($row = $result->fetch_row()) {
            $tempResult = $conReferrals->query('SELECT * FROM PatientData WHERE SW_ID="' . $row[0] . '"');
            $temp = $tempResult->fetch_row();
            $tempResult = $conReferrals->query('SELECT * FROM Referrals.Referrals WHERE PatientID = "' . $temp[0] . '"');
            while ($referral = $tempResult->fetch_row()){
                $dob = null;
                echo "<tr onclick=\"window.location='/Reports/GetPatient.php?typeID=" . $referral[0] . "&type=1';\"><td>";
                $id = "" . $temp[1];
                $_SESSION['currentPatient'] = $temp[0];

                echo $row[1] . " " . $row[2];
                $dob = $row[3];

                echo "</td><td>";
                $date = date_create($dob);
                echo date_format($date, "m/d/Y");
                echo "</td><td>";
                echo $referral[6];
                echo "</td><td>";
                $query = 'SELECT * FROM Referrals.Specialist WHERE ID="' . $referral[9] . '"';
                $t = $conReferrals->query($query);
                $tr = $t->fetch_row();
                echo $tr[2];
                echo "</td><td>";
                if (ctype_digit($temp[4]) && strlen($temp[4]) == 10) {

                    $temp[4] = substr($temp[4], 0, 3) . '-' . substr($temp[4], 3, 3) . '-' . substr($temp[4], 6);
                } else {

                    if (ctype_digit($temp[4]) && strlen($temp[4]) == 7) {
                        $temp[4] = substr($temp[4], 0, 3) . '-' . substr($temp[4], 3, 4);
                    }
                }
                echo $temp[4];

                echo "</td><td>";
                $query = 'SELECT * FROM Referrals.Specialty WHERE ID="' . $referral[8] . '"';
                $t = $conReferrals->query($query);
                $tr = $t->fetch_row();
                echo $tr[1];
                echo "</td><td>";
                echo $row[4];
                echo "</td><td>";
                $date = date_create($referral[10]);
                echo date_format($date, "m/d/Y");
                echo "</td></tr>";
            }
        }
    }
    ?>
    </tbody>
</table>
<script>
    function sortTable(n) {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById("All");
        switching = true;
        // Set the sorting direction to ascending:
        dir = "asc";
        /* Make a loop that will continue until
        no switching has been done: */
        while (switching) {
            // Start by saying: no switching is done:
            switching = false;
            rows = table.rows;
            /* Loop through all table rows (except the
            first, which contains table headers): */
            for (i = 1; i < (rows.length - 1); i++) {
                // Start by saying there should be no switching:
                shouldSwitch = false;
                /* Get the two elements you want to compare,
                one from current row and one from the next: */
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];
                /* Check if the two rows should switch place,
                based on the direction, asc or desc: */
                if (dir == "asc") {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        // If so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                    }
                } else if (dir == "desc") {
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                        // If so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                    }
                }
            }
            if (shouldSwitch) {
                /* If a switch has been marked, make the switch
                and mark that a switch has been done: */
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                // Each time a switch is done, increase this count by 1:
                switchcount ++;
            } else {
                /* If no switching has been done AND the direction is "asc",
                set the direction to "desc" and run the while loop again. */
                if (switchcount == 0 && dir == "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
    }
</script>
</body>
</html>