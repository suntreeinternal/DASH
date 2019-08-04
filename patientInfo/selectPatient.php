<?php

//TODO add error if patient is lost in soapware
//TODO lots of testing to make sure that patient is added the correct way.
session_start();
//echo phpinfo();
if (sizeof($_SESSION) == 0){
    header('location:../index.html');
}
$patientName = '';
$DOB = '';
$phoneNumber = '';
$last = $_GET['last'];
$date = $_GET['date'];

//connection for mssql
$con = mssql_connect('sunserver', 'siminternal', 'Watergate2015');

//connection for sqli
$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');

if($con->connect_error){
    header('location:/index.html');
}

if (!mssql_select_db('sw_charts', $con)) {
    die('Unable to select database! ');
}


$query = "SELECT * FROM dbo.Gen_Demo WHERE last_name LIKE '%" . $_GET['last'] . "%' ORDER BY first_name ASC";
$result = mssql_query($query);

$toTable = "";
while($row = mssql_fetch_array($result)){
    if (substr($row['last_name'], '0','1') == "*"){
//        echo strrpos($row['last_name'], '*') . "<br>";
    } else {
        $lastNameFixed = '';
        if (strrpos($row['last_name'], ' ')){
    //        echo $row['last_name'];
            $lastNameFixed = str_replace(" ", "%20", $row['last_name']);
        } else {
            $lastNameFixed = $row['last_name'];
        }
        $toTable .= "<tr onclick=window.location='../patientInfo/Patient.php?last=" . $lastNameFixed . "&date=" . $row['birthdate'] . "'>";
        $toTable .= "<td>" . $row['first_name'] . "</td>";
        $toTable .= "<td>" . $row['last_name'] . "</td>";
        $toTable .= "<td>" . date("m-d-Y", strtotime($row['birthdate'])) . "</td>";
        $toTable .= "<td>" . $row['sex'] . "</td>";
        $toTable .= "<td> No </td>";
        $toTable .= "</a></tr>";
    }
}

$query = 'SELECT * FROM Referrals.TempPatient WHERE LOWER(LastName)="' . strtolower($_GET['last']) . '" AND Pipek IS NULL';
$resultMySql = $conReferrals->query($query);

while ($rows = $resultMySql->fetch_row()){
    $lastNameFixed = '';

    if (strrpos($row[2], ' ')){
        echo $row[2];
        $lastNameFixed = str_replace(" ", "%20", $row[2]);
    } else {
        $lastNameFixed = $row[2];
    }
//    echo var_dump($rows);
    echo $lastNameFixed;

    $toTable .= "<tr onclick=window.location='../patientInfo/Patient.php?last=" . $lastNameFixed . "&date=" . $rows[3] . "'>";
    $toTable .= "<td>" . $rows[1] . "</td>";
    $toTable .= "<td>" . $rows[2] . "</td>";
    $toTable .= "<td>" . date("m-d-Y", strtotime($rows[3])) . "</td>";
    $toTable .= "<td> Unknown </td>";
    $toTable .= "<td> Yes </td>";
    $toTable .= "</a></tr>";
}

?>


<html>
<head>
    <link rel="stylesheet" href="../Menu/menu.css">
    <style>

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

        .datatable table {
            border-collapse: collapse;
            width: 100%;
            border: 0px;

        }

        .datatable th, td {
            text-align: left;
            padding-top: 8px;
            padding-bottom: 8px;

        }

        .datatable tr:nth-child(even) {
            background-color: #f2f2f2;
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

<table style="width: 100%">
    <tbody>
        <tr>
            <th width="15%">First Name</th>
            <th width="15%">Last Name</th>
            <th width="15%">Date of Birth</th>
            <th width="25%">Gender</th>
            <th width="30%">Temp Patient</th>
        </tr>
        <?php echo $toTable?>
    </tbody>

</table>


</body>

</html>