<?php
session_start();
if (sizeof($_SESSION) == 0){
    header('location:../index.html');
}
$patientName = $_SESSION['patientName'];
$DOB = $_SESSION['patientDOB'];
$phoneNumber = '';




$con = mssql_connect('sunserver', 'siminternal', 'Watergate2015');
$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
if (!mssql_select_db('sw_charts', $con)) {
    die('Unable to select database!');
}

$query = 'SELECT * FROM Referrals.PatientData WHERE ID=\'' . $_SESSION['currentPatient'] . '\'';
$result = $conReferrals->query($query);
$row = $result->fetch_row();
$phoneNumber = $row[4];
$alert = $row[2];

if(ctype_digit($phoneNumber) && strlen($phoneNumber) == 10) {
    $phoneNumber = substr($phoneNumber, 0, 3) .'-'. substr($phoneNumber, 3, 3) .'-'. substr($phoneNumber, 6);
} else {
    if(ctype_digit($phoneNumber) && strlen($phoneNumber) == 7) {
        $phoneNumber = substr($phoneNumber, 0, 3) .'-'. substr($phoneNumber, 3, 4);
    }
}

$query = 'SELECT * FROM Referrals.Provider';
$result = $conReferrals->query($query);
$providerList = '<select name="provider">';
while ($row = $result->fetch_row()){
    $providerList = $providerList . '<option value="' . $row[0] . '">' . $row[1] . '</option>';
}
$providerList = $providerList . '</select>';

$query = 'SELECT * FROM Referrals.Specialty';
$result = $conReferrals->query($query);
$specalty = "";
while ($row = $result->fetch_row()){
    $specalty = $specalty . '<option value="' . $row[0] . '">' . $row[1] . '</option>';
}



$dateTime = date("Y-m-d h:i:sa");
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
<?php include "../Menu/menu.php"?>

</body>

</html>