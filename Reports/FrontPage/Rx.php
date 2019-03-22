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
    <link rel="stylesheet" href="/Menu/menu.css">
    <style>
        .dropdown-content a {
            color: black;
            padding: 5px 5px;
            text-decoration: none;
            display: block;
        }
        .dropdown-content a:hover {background-color: #ddd;}

        .dropdown:hover .dropdown-content {display: block;}

        .dropdown:hover .dropbtn {background-color: #3e8e41;}

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

<?php include "/home/siminternal/html/Menu/menu.php"?>
<table id="All" width="100%">
    <tbody>
    <tr>
        <th onclick="sortTable(0)" width="12%">Patient Name</th>
        <th onclick="sortTable(1)" width="10%">Date Of Submitted</th>
        <th onclick="sortTable(2)" width="10%">Patient Phone number</th>
        <th onclick="sortTable(3)" width="8%">Provider</th>
        <th onclick="sortTable(4)" width="12%">Status</th>
        <th onclick="sortTable(5)" width="12%">Authorization</th>
        <th onclick="sortTable(6)" width="36%">Note</th>
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
                echo "<tr onclick=\"window.location='/Rx/PreviousRx.php?RxId=" . $row[0] . "';\"><td>";


                echo $tr[2] . " " . $tr[1];
                $dob = $tr[21];
            } else {
                $query = 'SELECT * FROM Referrals.TempPatient WHERE ID="' . $tr[1] . '"';
                $temp = $conReferrals->query($query);
                $tr = $temp->fetch_row();
                echo "<tr onclick=\"window.location='/Rx/PreviousRx.php?RxId=" . $row[0] . "';\"><td>";
                echo $tr[1] . " " . $tr[2];
                $dob = $tr[3];
                $_SESSION['patientName'] = $tr[1] . " " . $tr[2];
                $_SESSION['patientDOB'] = $tr[3];
            }
            echo "</td><td>";
            $date = date_create($row[2]);
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
            $query = "SELECT * FROM Provider WHERE ID=" . $row[4];
            $thisResult = $conReferrals->query($query);
            echo $thisResult->fetch_row()[2];
            echo "</td><td>";
            switch ($row[3]){
                case 1:
                    echo "Rx to MA";
                    break;

                case 2:
                    echo "Rx to Provider";

                    break;

                case 3:
                    echo "Rx to Reception";

                    break;

                case 4:
                    echo "Rx to eScribe";

                    break;

                case 5:
                    echo "Pharmacy Called";

                break;

                case 6:
                    echo "Patient Notified";

                    break;
            }
            echo "</td><td>";
            switch ($row[6]){
                case 0:
                    echo 'No Verdict';
                    break;

                case 1:
                    echo 'Yes';

                    break;

                case 2:
                    echo 'No';
                    break;

                case 3:
                    echo 'Needs To Be Seen';
                    break;

                case 4:
                    echo 'See Me';
                    break;

            }
            echo "</td><td>";
            echo $row[5];
            echo "</td></tr>";
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