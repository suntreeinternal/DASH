<?php

session_start();
//echo phpinfo();
if (sizeof($_SESSION) == 0){
    header('location:../index.html');
}
//echo var_dump($_GET);

//connection for sqli
$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');

$toTable = "";



$query = 'SELECT * FROM Referrals.TempPatient WHERE LastName="' . $_GET['last'] . '" AND Pipek="1"';
$resultMySql = $conReferrals->query($query);

while ($rows = $resultMySql->fetch_row()){
    $lastNameFixed = '';
    if (strrpos($rows[2], ' ')){
        echo $rows['last_name'];
        $lastNameFixed = str_replace(" ", "%20", $rows[2]);
    } else {
        $lastNameFixed = $rows[2];
    }
//    echo var_dump($rows);
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