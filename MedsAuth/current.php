<?php
session_start();
include("../fetchPatientData/patientInfo.php");


if (sizeof($_SESSION) == 0){
    header('location:../index.html');
}
$DOB = $_SESSION['patientDOB'];
$phoneNumber = '';

$con = mssql_connect('sunserver', 'siminternal', 'Watergate2015');
$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
if (!mssql_select_db('sw_charts', $con)) {
    die('Unable to select database!');
}


$query = "SELECT * FROM Referrals.MedsAuth WHERE ID=" . $_GET['typeID'];
$result = $conReferrals->query($query);
$getMeds = $result->fetch_row();
//var_dump($getMeds);
$_SESSION['currentPatient'] = $getMeds[1];


$query = 'SELECT * FROM Referrals.Provider';

$result = $conReferrals->query($query);
$providerList = '<select name="provider">';
while ($row = $result->fetch_row()){
    if ($getMeds[2] == $row[0]){
        $providerList = $providerList . '<option selected="selected" value="' . $row[0] . '">' . $row[1] . '</option>';
    } else {
        $providerList = $providerList . '<option value="' . $row[0] . '">' . $row[1] . '</option>';
    }
}
$providerList = $providerList . '</select>';

$date = $getMeds[3];
$pharmacy = $getMeds[4];
$pharmacyPhone = $getMeds[5];
$status = $getMeds[6];

$statusText = '';
switch ($status){
    case 0:
        $statusText = '<select name="status">
                        <option selected="selected" value="0">New</option>
                        <option value="1">Pending</option>
                        <option value="2">Denial</option>
                        <option value="3">Other</option>
                        <option value="4">Patient Notified</option>
                    </select>';
        break;
    case 1:
        $statusText = '<select name="status">
                        <option value="0">New</option>
                        <option selected="selected" value="1">Pending</option>
                        <option value="2">Denial</option>
                        <option value="3">Other</option>
                        <option value="4">Patient Notified</option>
                    </select>';
        break;
    case 2:
        $statusText = '<select name="status">
                        <option value="0">New</option>
                        <option value="1">Pending</option>
                        <option selected="selected" value="2">Denial</option>
                        <option value="3">Other</option>
                        <option value="4">Patient Notified</option>
                    </select>';
        break;
    case 3:
        $statusText = '<select name="status">
                        <option value="0">New</option>
                        <option value="1">Pending</option>
                        <option value="2">Denial</option>
                        <option selected="selected" value="3">Other</option>
                        <option value="4">Patient Notified</option>
                    </select>';
        break;
    case 4:
        $statusText = '<select name="status">
                        <option value="0">New</option>
                        <option value="1">Pending</option>
                        <option value="2">Denial</option>
                        <option value="3">Other</option>
                        <option selected="selected" value="4">Patient Notified</option>
                    </select>';
        break;
}

$patientInfo = new Patient();
$patientInfo->SelectPatient($_SESSION['currentPatient']);
$_SESSION['swID'] = $patientInfo->getSwId();

$_SESSION['previous'] = "location:/patientInfo/Patient.php?last=" . $patientInfo->GetLastName() . "&date=" . $patientInfo->GetDOB();

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
<table style="width: 100%" cellspacing="15" cellpadding="10">
    <tbody>
    <tr>
        <?php include "../patientInfo/patientInfoHeader.php"?>
    </tr>
    <tr valign="top">
        <?php include "../patientInfo/PhoneRecord.php"?>
        <?php include "../patientInfo/Messages.php"?>

        <td style=" width: 50%; border-radius: 10px;background-color:#FFFFFF">
            <div style="overflow-y: scroll; height:650px">
                <table width="100%" cellspacing="10px" cellpadding="5px" >
                    <tbody>
                    <tr>
                        <td style="font-size: 20px; font-weight: bold" width="50%">
                            Medicine Authorized
                        </td>

                    </tr>
                    <tr>
                        <table cellpadding="15px" cellspacing="15px" width="100%" >
                            <tbody>
                            <form action="update.php">
                                <tr>
                                    <td width="50%">
                                        Provider <?php echo $providerList?>
                                    </td>
                                    <td width="50%">
                                        Date created: <?php echo $dateTime?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Pharmacy Name <input name="Pharmacy" type="text" value="<?php echo $pharmacy?>">
                                    </td>
                                    <td>
                                        Pharmacy Phone Number: <input name="PhoneNum" type="text" value="<?php echo $pharmacyPhone?>">
                                        <input type="hidden" name="typeID" value="<?php echo $_GET['typeID']?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Status: <?php echo $statusText?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="submit" value="Submit new meds auth">
                                    </td>
                                </tr>
                            </form>
                            </tbody>
                        </table>
                    </tr>
                    <tr>
                        <td>
                            <form action='/pushNewPhoneMessage.php'>
                                <table width="100%" cellpadding="0px" cellspacing="0px" style="border-radius: 10px">
                                    <tbody>
                                    <tr>
                                        <td colspan="5" >
                                            <textarea rows="2" name="message" style="border-radius: 10px; resize: none; width: 100%; overflow: auto"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="submit" name="button" value="Add new phone conversation" class="btnOthers">
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                            </form>
                        </td>
                        <td>
                            <form action='/patientInfo/newMessage.php'>
                                <table width="100%" cellpadding="0px" cellspacing="0px" style="border-radius: 10px">
                                    <tbody>
                                    <tr>
                                        <td colspan="5" >
                                            <textarea rows="2" name="message" style="border-radius: 10px; resize: none; width: 100%; overflow: auto"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="submit" name="button" value="Add new message" class="btnOthers">
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                            </form>
                        </td>
                    </tbody>
                </table>
            </div>
        </td>
    </tbody>
</table>

</body>

</html>