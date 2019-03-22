<?php
session_start();
//echo var_dump($_SESSION);
include ("../fetchPatientData/patientInfo.php");

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

$query = 'SELECT * FROM Rx WHERE ID=' . $_GET['RxId'];
$result = $conReferrals->query($query);
$RxInfo = $result->fetch_row();

$query = 'SELECT * FROM Referrals.Provider';
$result = $conReferrals->query($query);
$providerList = '<select name="provider">';
while ($row = $result->fetch_row()){
    if($row[0] == $RxInfo[4]){
        $providerList = $providerList . '<option selected value="' . $row[0] . '">' . $row[1] . '</option>';
    } else {
        $providerList = $providerList . '<option value="' . $row[0] . '">' . $row[1] . '</option>';
    }
}
$providerList = $providerList . '</select>';

$date = new DateTime($RxInfo[2]);
$dateTime = $date->format("m-d-Y h:i:sa");

$patientInfo = new Patient();
$patientInfo->SelectPatient($_SESSION['currentPatient']);

//$dateTime = date("m-d-Y h:i:sa", $RxInfo[2]);
$_SESSION['previous'] = 'location:/patientInfo/Patient.php?last=' . $patientInfo->GetLastName() . '&date=' . $patientInfo->GetDOB();

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
            <div style="height:650px">
                <table width="100%" cellspacing="10px" cellpadding="5px" >
                    <tbody>
                    <tr>
                        <td style="font-size: 20px; font-weight: bold" width="50%">
                            Rx
                        </td>

                    </tr>
                    <tr>
                        <table cellpadding="15px" cellspacing="15px" width="100%" >
                            <tbody>
                            <form action="subbmitRx.php">
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
                                        Reason <input name="Reason" type="text" value="<?php echo $RxInfo[32]?>"
                                    </td>
                                    <td>
                                        Status: <select name="status">
                                            <option value="1">Rx to MA</option>
                                            <option value="2">Rx to Provider</option>
                                            <option value="3">Rx to Reception</option>
                                            <option value="4">Rx to eScribe</option>
                                            <option value="5">Pharmacy Called</option>
                                            <option value="6">Patient Notified</option>
                                        </select>
                                        <input type="hidden" name="dateTime" value="<?php echo $dateTime?>">
                                        <input type="hidden" name="patientID" value="<?php echo $_SESSION['currentPatient']?>">

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Authorization: <select name="authorization">
                                            <option selected="selected" value="0">No verdict</option>
                                            <option value="1">Yes</option>
                                            <option value="2">No</option>
                                            <option value="3">Needs to be seen</option>
                                            <option value="4">See me</option>
                                        </select>
                                    </td>
                                    <td>
                                        Note: <input type="text" name="Note" value="<?php echo $RxInfo[5]?> ">
                                    </td>
                                <tr>
                                    <td colspan="2">
                                        <table width="100%" style="border-collapse: collapse; border-spacing: 0">
                                            <tbody >
                                            <tr>
                                                <th width="25%">Prescription</th>
                                                <th width="10%">Mg</th>
                                                <th>Quantity</th>
                                                <th>Directions</th>
                                                <th>Directions 2</th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="text" name="prescription1" value="<?php echo $RxInfo[7]?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="mg1" value="<?php echo $RxInfo[8]?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="Quantity1" value="<?php echo $RxInfo[9]?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="Dir1" value="<?php echo $RxInfo[10]?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="Dir21" value="<?php echo $RxInfo[11]?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="text" name="prescription2" value="<?php echo $RxInfo[12]?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="mg2" value="<?php echo $RxInfo[13]?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="Quantity2" value="<?php echo $RxInfo[14]?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="Dir2" value="<?php echo $RxInfo[15]?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="Dir22" value="<?php echo $RxInfo[16]?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="text" name="prescription3" value="<?php echo $RxInfo[17]?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="mg3" value="<?php echo $RxInfo[18]?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="Quantity3" value="<?php echo $RxInfo[19]?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="Dir3" value="<?php echo $RxInfo[20]?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="Dir23" value="<?php echo $RxInfo[21]?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="text" name="prescription4" value="<?php echo $RxInfo[22]?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="mg4" value="<?php echo $RxInfo[23]?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="Quantity4" value="<?php echo $RxInfo[24]?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="Dir4" value="<?php echo $RxInfo[25]?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="Dir24" value="<?php echo $RxInfo[26]?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="text" name="prescription5" value="<?php echo $RxInfo[27]?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="mg5" value="<?php echo $RxInfo[28]?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="Quantity5" value="<?php echo $RxInfo[29]?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="Dir5" value="<?php echo $RxInfo[30]?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="Dir25" value="<?php echo $RxInfo[31]?>">
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>

                                    </td>

                                </tr>
                                <tr>
                                    <td>
                                        <input type="submit" value="Submit new Rx">
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