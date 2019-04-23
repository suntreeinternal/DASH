<?php
session_start();
if (sizeof($_SESSION) == 0){
    header('location:../index.html');
}
$patientName = $_SESSION['patientName'];
$DOB = $_SESSION['patientDOB'];
$phoneNumber = '';

//echo var_dump($_SESSION);


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
                                        Reason <input name="Reason" type="text" placeholder="Add Reason">
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
                                        Note:<br/> <textarea name="note" rows="5" cols="40"></textarea>
                                    </td>
                                <tr>
                                    <td colspan="2">
                                        <table style="border-collapse: collapse; border-spacing: 0">
                                            <tbody>
                                                <tr>
                                                    <th>Prescription</th>
                                                    <th>Dose</th>
                                                    <th>Quantity</th>
                                                    <th>Refills</th>
                                                    <th>Directions</th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="text" name="prescription1">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="mg1">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="Quantity1">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="Dir1">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="Dir21">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="text" name="prescription2">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="mg2">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="Quantity2">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="Dir2">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="Dir22">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="text" name="prescription3">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="mg3">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="Quantity3">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="Dir3">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="Dir23">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="text" name="prescription4">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="mg4">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="Quantity4">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="Dir4">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="Dir24">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="text" name="prescription5">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="mg5">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="Quantity5">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="Dir5">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="Dir25">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="text" name="prescription6">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="mg6">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="Quantity6">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="Dir6">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="Dir26">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="text" name="prescription7">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="mg7">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="Quantity7">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="Dir7">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="Dir27">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="text" name="prescription8">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="mg8">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="Quantity8">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="Dir8">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="Dir28">
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
                            <form action='/patientInfo/pushNewMessage.php'>
                                <table width="100%" cellpadding="0px" cellspacing="0px" style="border-radius: 10px">
                                    <tbody>
                                    <tr>
                                        <td colspan="5" >
                                            <textarea rows="2" name="message" style="border-radius: 10px; resize: none; width: 100%; overflow: auto"></textarea>
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