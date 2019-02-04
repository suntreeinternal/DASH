<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 10/29/2018
 * Time: 12:14 PM
 */
session_start();
$con = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');

$query = "SELECT * FROM Referrals.Users ORDER BY FirstName ASC, LastName ASC";
$employee = "";
$result = $con->query($query);
    while ($row = $result->fetch_row()){
        $employee = $employee . '<option value="' . $row[0] . '">' . $row[4] . " " . $row[5] . '</option>';
    }

?>

<html>
<style>
    #main table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #main td, th {
        border: 1px solid #dddddd;*/
    border: 1px solid #a9a9a9;

        text-align: left;
        padding: 8px;
    }

    #main tr:nth-child(even) {
        background-color: #dddddd;

    }
    #main tr:nth-child(odd) {
        background-color: #FFFFFF;

    }

</style>
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

<script>
    function getData() {
        var name = document.forms['selectEmployee']['employee'].value;
        var start = document.forms['selectEmployee']['start'].value;
        var end = document.forms['selectEmployee']['end'].value;
        // alert(name + ", " + start + ", " + end);
        var xmlhttp = new XMLHttpRequest();
        if (end != "" && start != "" && name !="") {

            xmlhttp.onreadystatechange = function () {
                document.getElementById('txtHint').innerHTML = this.responseText;
            };
            xmlhttp.open("GET", "EmployeeReportTable.php?name=" + name + "&start=" + start + "&end=" + end, true);
            xmlhttp.send();
        }
        return 0;

    }
</script>

<form name="selectEmployee" onclick="getData()"> <!--action="EmployeeReportTable.php">-->
    <table width="100%" style="padding: 10px;">
        <tbody>
        <tr>
            <td width="20%">
                Select Employee: <select name="employee"><?php echo $employee?></select>
            </td>
            <td width="15%">
                Select Start Date: <input name="start" type="date" value="<?php
                $today = date('Y-m-d');
                $newDate = date('Y-m-d', strtotime('-1 months', strtotime($today)));
                echo $newDate?>">

            </td>
            <td width="15%">
                Select End Date: <input name="end" type="date" value="<?php echo date('Y-m-d')?>">

            </td>

            <td width="45">

            </td>
        </tr>
        </tbody>
    </table>
</form>
<span id="txtHint"></span>

</body>

