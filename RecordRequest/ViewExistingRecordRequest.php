<?php
include("../fetchPatientData/patientInfo.php");
session_start();
if (sizeof($_SESSION) == 0){
    header('location:../index.html');
}
$DOB = $_SESSION['patientDOB'];


$con = mssql_connect('sunserver', 'siminternal', 'Watergate2015');
$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
if (!mssql_select_db('sw_charts', $con)) {
    die('Unable to select database!');
}
$query = "SELECT * FROM Referrals.RecordRequest WHERE ID='" . $_GET['typeID'] ."'";
$tempRE = $conReferrals->query($query);
$row = $tempRE->fetch_row();

//echo var_dump($_SESSION);
//
//$patientInfo->SelectPatient($row[1]);
//$patientName = $patientInfo->GetFullName();
//$DOB = $patientInfo->GetDOB();
$_SESSION['currentPatient'] = $row[1];


$query = "SELECT * FROM Referrals.RecordRequest WHERE ID=" . $_GET['typeID'];
$result = $conReferrals->query($query);
$row = $result->fetch_row();

$query = 'SELECT * FROM Referrals.Provider';
$result = $conReferrals->query($query);
$providerList .= "";
$provider = $row[8];
$MD = $row[9];
switch ($row[2]){

    case 1:
        $providerList .= '<select name="Requester">';
        $providerList .= '<option selected="selected" value="1">Doctors Office</option>';
        $providerList .= '<option value="2">Attorney</option>';
        $providerList .= '<option value="3">Patient</option>';
        $providerList .= '<option value="4">SSI</option>';
        $providerList .= '<option value="5">Life / Health Insurance</option>';
        $providerList .= '</select>';
        break;

    case 2:
        $providerList .= '<select name="Requester">';
        $providerList .= '<option value="1">Doctors Office</option>';
        $providerList .= '<option selected="selected" value="2">Attorney</option>';
        $providerList .= '<option value="3">Patient</option>';
        $providerList .= '<option value="4">SSI</option>';
        $providerList .= '<option value="5">Life / Health Insurance</option>';
        $providerList .= '</select>';
        break;

    case 3:
        $providerList .= '<select name="Requester">';
        $providerList .= '<option value="1">Doctors Office</option>';
        $providerList .= '<option value="2">Attorney</option>';
        $providerList .= '<option selected="selected" value="3">Patient</option>';
        $providerList .= '<option value="4">SSI</option>';
        $providerList .= '<option value="5">Life / Health Insurance</option>';
        $providerList .= '</select>';
        break;

    case 4:
        $providerList .= '<select name="Requester">';
        $providerList .= '<option value="1">Doctors Office</option>';
        $providerList .= '<option value="2">Attorney</option>';
        $providerList .= '<option value="3">Patient</option>';
        $providerList .= '<option selected="selected" value="4">SSI</option>';
        $providerList .= '<option value="5">Life / Health Insurance</option>';
        $providerList .= '</select>';
        break;

    case 5:
        $providerList .= '<select name="Requester">';
        $providerList .= '<option value="1">Doctors Office</option>';
        $providerList .= '<option value="2">Attorney</option>';
        $providerList .= '<option value="3">Patient</option>';
        $providerList .= '<option value="4">SSI</option>';
        $providerList .= '<option selected="selected" value="5">Life / Health Insurance</option>';
        $providerList .= '</select>';
        break;

}
$auth = "";
switch ($row[4]){
    case 1:
        $auth .= '<select name="authorization">';
        $auth .= '<option selected="selected" value="1">Yes</option>';
        $auth .= '<option value="0">No</option>';

        $auth .= '</select>';
        break;

    case 0:
        $auth .= '<select name="authorization">';
        $auth .= '<option value="1">Yes</option>';
        $auth .= '<option selected="selected" value="0">No</option>';
        $auth .= '</select>';
        break;

}

$query = 'SELECT * FROM Referrals.RecordStatus';
$result = $conReferrals->query($query);
$status = "<select name='selected'>";
while ($row1 = $result->fetch_row()){
    if ($row[3] == $row1[0]){
        $status .= '<option selected="selected" value="' . $row1[0] . '">' . $row1[1] . '</option>';

    } else {
        $status .= '<option value="' . $row1[0] . '">' . $row1[1] . '</option>';
    }
}

$status .= "</select>";
$reason = $row[5];


//TODO date time
//echo $row[6];
$dateTime = $row[6];

$patientInfo = new Patient();
$patientInfo->SelectPatient($_SESSION['currentPatient']);
$_SESSION['swID'] = $patientInfo->getSwId();

if ($_GET['goback']){
    $_SESSION['previous'] = 'location:/Reports/FrontPage/Records.php?query=' . $_GET['goback'];
} else {
    $_SESSION['previous'] = "location:/patientInfo/Patient.php?last=" . $patientInfo->GetLastName() . "&date=" . $patientInfo->GetDOB();
//echo $_SESSION['previous'];
}
$conReferrals->close();
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
        <?php include "../RecordRequest/Notes.php"?>
        <td style=" width: 25%; border-radius: 10px;background-color:#FFFFFF">
            <div style="overflow-y: scroll; height:650px">
                <table width="100%" cellspacing="10px" cellpadding="5px" >
                    <tbody>
                    <tr>
                        <td style="font-size: 20px; font-weight: bold" width="50%">
                            Record Request
                        </td>

                    </tr>
                    <tr>
                        <table cellpadding="15px" cellspacing="15px" width="100%" >
                            <tbody>
                            <form action="updateRecordRequest.php">
                                <tr>
                                    <td width="50%">
                                        3rd Party Requester <?php echo $providerList?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%">
                                        Date Requested: <?php echo $dateTime?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Reason <input type="text" name="Reason" value="<?php echo $reason?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Status:<?php echo $status?>
                                        <input type="hidden" name="ID" value="<?php echo  $_GET['typeID']?>">

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Check required: <?php echo $auth?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Last Provider: <input type="text" name="provider" value="<?php echo $provider?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Last MD: <input type="text" name="MD" value="<?php echo $MD?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="submit" value="Update record request">
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
                                            <textarea rows="4" name="message" style="border-radius: 10px; resize: none; width: 100%; overflow: auto"></textarea>
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
                            <form action='/patientInfo/pushNewMessage.php'>
                                <table width="100%" cellpadding="0px" cellspacing="0px" style="border-radius: 10px">
                                    <tbody>
                                    <tr>
                                        <td colspan="5" >
                                            <textarea rows="4" name="message" style="border-radius: 10px; resize: none; width: 100%; overflow: auto"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="submit" name="button" class="btnOthers">
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                            </form>
                        </td>
                        <td>
                            <form action='NewNote.php'>
                                <table width="100%" cellpadding="0px" cellspacing="0px" style="border-radius: 10px">
                                    <tbody>
                                    <tr>
                                        <td colspan="5" >
                                            <textarea rows="4" name="message" style="border-radius: 10px; resize: none; width: 100%; overflow: auto"></textarea>
                                            <input type="hidden" name="typeID" value="<?php echo $_GET['typeID']?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="submit" name="button" class="btnOthers">
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