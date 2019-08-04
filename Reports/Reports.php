<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 11/27/2018
 * Time: 8:43 AM
 */

session_start();
if (sizeof($_SESSION) == 0){
    header('location:../index.html');
}
$request = $_GET['request'];

$con = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
$query = 'SELECT COUNT(*) FROM TempPatient';
$result = $con->query($query);
$row = $result->fetch_row();
$pendingSoap = $row[0];

$query = 'SELECT COUNT(*) FROM PatientData WHERE Message_alert_to_group=2';
$result = $con->query($query);
$row = $result->fetch_row();
$reception = $row[0];

$query = 'SELECT COUNT(*) FROM PatientData WHERE Message_alert_to_group=5';
$result = $con->query($query);
$row = $result->fetch_row();
$ma = $row[0];

$query = 'SELECT COUNT(*) FROM PatientData WHERE Message_alert_to_group=4';
$result = $con->query($query);
$row = $result->fetch_row();
$referrals = $row[0];

$query = 'SELECT COUNT(*) FROM PatientData WHERE Message_alert_to_group=3';
$result = $con->query($query);
$row = $result->fetch_row();
$provider = $row[0];

?>
<html>
<head>
    <link rel="stylesheet" href="../Menu/menu.css">
    <title>DASH: <?php echo $_SESSION['name']?></title>
    <style>

        .table1 tr:nth-child(even){
            background-color: #f2f2f2;
        }
        .table1 th {
            cursor: pointer;
        }
    .notification {
        background-color: #555;
        color: white;
        text-decoration: none;
        padding: 15px 26px;
        position: relative;
        display: inline-block;
        border-radius: 2px;
        width: 120px;
    }

    .notification:hover {
        background: red;
    }

    .notification .badge {
        position: absolute;
        top: -10px;
        right: -10px;
        padding: 5px 10px;
        border-radius: 50%;
        background-color: red;
        color: white;
    }
    </style>
</head>

<body style="background:darkgray;">
<?php include "../Menu/menu.php"?>
<table width="100%" class="table1" border="0px" id="myTable">
    <tbody>
    <tr>
        <th width="16%" onclick="sortTable(0)">
            Patient
        </th>
        <th width="16%" onclick="sortTable(1)">
            Phone number
        </th>
        <th width="16%" onclick="sortTable(2)">
            Provider
        </th>
        <th width="16%" onclick="sortTable(3)">
            Specialist
        </th>
        <th width="16%" onclick="sortTable(4)">
            Specialty
        </th>
        <th width="20%" onclick="sortTable(5)">
            Status
        </th>

    </tr>
    <tr>
        <td width="16%" onclick="sortTable(0)">
            atient
        </td>
        <td width="16%" onclick="sortTable(1)">
            hone number
        </td>
        <td width="16%" onclick="sortTable(2)">
            rovider
        </td>
        <td width="16%" onclick="sortTable(3)">
            ialist
        </td>
        <td width="16%" onclick="sortTable(4)">
            lty
        </td>
        <td width="20%" onclick="sortTable(5)">
            Sus
        </td>

    </tr>
    <tr>
        <td width="16%" onclick="sortTable(0)">
            Patient
        </td>
        <td width="16%" onclick="sortTable(1)">
            Phone number
        </td>
        <td width="16%" onclick="sortTable(2)">
            Provider
        </td>
        <td width="16%" onclick="sortTable(3)">
            Specialist
        </td>
        <td width="16%" onclick="sortTable(4)">
            Specialty
        </td>
        <td width="20%" onclick="sortTable(5)">
            Status
        </td>

    </tr>
    <tr>
        <td width="16%" onclick="sortTable(0)">
            ent
        </td>
        <td width="16%" onclick="sortTable(1)">
            one number
        </td>
        <td width="16%" onclick="sortTable(2)">
            rovider
        </td>
        <td width="16%" onclick="sortTable(3)">
            ecialist
        </td>
        <td width="16%" onclick="sortTable(4)">
            cialty
        </td>
        <td width="20%" onclick="sortTable(5)">
            Satus
        </td>

    </tr>
    <tr>
        <td width="16%" onclick="sortTable(0)">
            Ptient
        </td>
        <td width="16%" onclick="sortTable(1)">
            Phne number
        </td>
        <td width="16%" onclick="sortTable(2)">
            Povider
        </td>
        <td width="16%" onclick="sortTable(3)">
            Secialist
        </td>
        <td width="16%" onclick="sortTable(4)">
            pecialty
        </td>
        <td width="20%" onclick="sortTable(5)">
            Status
        </td>

    </tr>

    </tbody>
</table>
<script>
    function sortTable(n) {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById("myTable");
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