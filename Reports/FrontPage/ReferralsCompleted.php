<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 12/11/2018
 * Time: 7:30 AM
 */
session_start();
if (sizeof($_SESSION) == 0){
    header('location:../index.html');
}
$con = mssql_connect('sunserver', 'siminternal', 'Watergate2015');
$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
if (!mssql_select_db('sw_charts', $con)) {
    die('Unable to select database!');
}


if ($_GET == NULL){
    
}
$query = 'SELECT * FROM Referrals.Specialty ORDER BY Specialty ASC ';
$result = $conReferrals->query($query);
$specalty = "";
$specalty = $specalty . '<option value="-1">' . '</option>';
$row = $result->fetch_row();
$specalty = $specalty . '<option value="' . $row[0] . '">' . $row[1] . '</option>';
$SelectedSpecality = $row[0];
while ($row = $result->fetch_row()){
    $specalty = $specalty . '<option value="' . $row[0] . '">' . $row[1] . '</option>';
}

$query = 'SELECT * FROM Referrals.Specialist ORDER BY Specialist.DrName ASC ';
$result = $conReferrals->query($query);
$specalist = "";
$specalist = $specalist . '<option value="-1">' . '</option>';
$row = $result->fetch_row();
$specalist = $specalist . '<option value="' . $row[0] . '">' . $row[2] . '</option>';
$SelectedSpecality = $row[0];
while ($row = $result->fetch_row()){
    $specalist = $specalist . '<option value="' . $row[0] . '">' . $row[2] . '</option>';
}

if ($_GET["first"] && $_GET["last"]){
    $query = "SELECT * FROM dbo.Gen_Demo WHERE last_name='" . $_GET['last'] . "' AND first_name='" . $_GET['first'] . "'";
} elseif ($_GET["first"]){
    $query = "SELECT * FROM dbo.Gen_Demo WHERE first_name='" . $_GET['first'] . "'";
} elseif ($_GET["last"]) {
    $query = "SELECT * FROM dbo.Gen_Demo WHERE last_name='" . $_GET['last'] . "'";
}

$result = mssql_query($query);
while ($row = mssql_fetch_array($result)){
    $row['last_name'];
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
<table width="100%" >
    <tbody>
        <tr style="background-color: #a9a9a9; border-color: #a9a9a9">
            <form action="ReferralsCompleted.php" id="referral">
                <td width="15%" style="border-color: #a9a9a9"> First Name: <input type="text" name="first"></td>
                <td width="15%" style="border-color: #a9a9a9">Last Name: <input type="text" name="last"></td>
                <td width="15%" style="border-color: #a9a9a9">Reason: <input type="text" name="reason"></td>
                <td width="15%" style="border-color: #a9a9a9">Specialty: <select name="Specialty"><?php echo $specalty?></select></td></td>
                <td width="20%" style="border-color: #a9a9a9">Specialist: <select name="Specalist"><?php echo $specalist?></select></td></td>
                <td width="15%" style="border-color: #a9a9a9">Date Sent: <input type="date" name="date"></td>
                <td width="5%" style="border-color: #a9a9a9"><input type="submit" value="Search" on></td>
            </form>
        </tr>
    </tbody>
</table>
<table id="All" width="100%">
    <tbody>
    <tr>
        <th onclick="sortTable(0)" width="14%">Patient Name</th>
        <th onclick="sortTable(1)" width="8%">DOB</th>
        <th onclick="sortTable(2)" width="12%">Reason</th>
        <th onclick="sortTable(3)" width="14%">Specialist</th>
        <th onclick="sortTable(4)" width="10%">Phone number</th>
        <th onclick="sortTable(5)" width="12%">Specialty</th>
        <th onclick="sortTable(6)" width="8%">Priority</th>
        <th onclick="sortTable(7)" width="12%">Date Sent</th>
        <th onclick="sortTable(8)" width="10%">Date Contacted</th>
    </tr>
    <tr>

    </tr>
    </tbody>
</table>

</body>
</html>