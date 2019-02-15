<?php
    include("../fetchPatientData/patientInfo.php");

    $patientInfo = new Patient;


    session_start();
    if (sizeof($_SESSION) == 0){
        header('location:../index.html');
    }
    $patientName = $_SESSION['patientName'];
    $DOB = $_SESSION['patientDOB'];
    $phoneNumber = '';
    $reason = "";



    $con = mssql_connect('sunserver', 'siminternal', 'Watergate2015');
    $conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
    if (!mssql_select_db('sw_charts', $con)) {
        die('Unable to select database!');
    }
    $query = 'SELECT * FROM Referrals.Referrals WHERE ID=\'' . $_GET['ReferralID'] . '\'';
    $result = $conReferrals->query($query);
    $row = $result->fetch_row();

    $dateTime =$row[7];
    $reason = $row[6];
    $ReferralSentDate = date_create($row[10]);
    $phoneNumber = $row[4];

    $alert = $row[2];
    $referralID = $row[0];
    $priority = $row[4];
    $currentStatus = $row[3];
    $currentProvider = $row[1];
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
        if ($row[0] == $currentProvider){
            $providerList = $providerList . '<option selected="selected" value="' . $row[0] . '">' . $row[1] . '</option>';

        } else {
            $providerList = $providerList . '<option value="' . $row[0] . '">' . $row[1] . '</option>';

        }

    }
    $providerList = $providerList . '</select>';

    $query = 'SELECT * FROM Referrals.Specialty';
    $result = $conReferrals->query($query);
    $specalty = "";
    while ($row = $result->fetch_row()){
        $specalty = $specalty . '<option value="' . $row[0] . '">' . $row[1] . '</option>';
    }

    $query = 'SELECT * FROM Referrals.Status';
    $result = $conReferrals->query($query);
    $status = '<select name="status">';

    while ($row = $result->fetch_row()){
        if ($row[0] == $currentStatus){
            $status = $status . '<option selected="selected" value="'. $row[0] .'">'. $row[1] .'</option>';
        } else {
            $status = $status . '<option value="'. $row[0] .'">'. $row[1] .'</option>';
        }
    }
     $status = $status . '</select>';

    $patientInfo->SelectPatient($_SESSION['currentPatient']);
    $_SESSION['previous'] = "location:/patientInfo/Patient.php?last=" . $patientInfo->GetLastName() . "&date=" . $patientInfo->GetDOB();
        

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
            right: 0;
        }

        .dropdown-content a {
            color: black;
            padding: 5px 5px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown:hover .dropbtn {
            background-color: #3e8e41;
        }

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
        <?php include "../patientInfo/Notes.php"?>
        <td style=" width: 25%; border-radius: 10px;background-color:#FFFFFF">
            <div style="height:650px">
                <table width="100%" cellspacing="10px" cellpadding="5px" >
                    <tbody>
                    <tr>
                        <td style="font-size: 20px; font-weight: bold" width="50%">
                            Referral
                        </td>
                    </tr>
                    <tr>
                        <table cellpadding="5px" cellspacing="15px" width="100%" >
                            <tbody>
                            <form action="updateReferral.php">
                                <tr>
                                    <td width="50%">
                                        Date created: <?php echo $dateTime?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%">
                                        Provider <?php echo $providerList?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Reason <input name="Reason" value="<?php echo $reason?>" type="text">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Status: <?php echo $status?>
                                        <input type="hidden" name="dateTime" value="<?php echo $dateTime?>">

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Authorization: <select name="authorization">
                                            <option value="1">Yes</option>
                                            <option value="2">No</option>
                                            <option value="3">N/A</option>
                                            <option selected="selected" value="4">Unknown</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Priority: <select name="priority">
                                            <option selected="selected" value="1">ASAP</option>
                                            <option value="2">Complete Date</option>
                                            <option value="3">Routine</option>
                                            <option value="4">Patient Referral</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Specialty: <select name="Specialty">
                                            <?php echo $specalty?>
                                        </select>
                                        <input type="hidden" name="refID" value="<?php echo $_GET['ReferralID']?>" >
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Specialist
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="submit" value="Update referral">

                                    </td>
                                </tr>
                                <tr>
                                    <td>

                                    </td>
                                </tr>
                            </form>
                            </tbody>
                        </table>
                        <a href="../Referral/SendReferral.php?ReferralID='<?php echo $referralID ?>'">
                            <button> Referral Sent: <?php echo date_format($ReferralSentDate, "m/d/Y H:i:s") ?></button>
                        </a>
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
                            <form action='/pushNewMessage.php'>
                                <table width="100%" cellpadding="0px" cellspacing="0px" style="border-radius: 10px">
                                    <tbody>
                                    <tr>
                                        <td colspan="5" >
                                            <textarea rows="2" name="message" style="border-radius: 10px; resize: none; width: 100%; overflow: auto"></textarea>
                                        </td>
                                    </tr>
                                    <tr valign="center" aria-rowspan="5px">
                                        <td valign="center">
                                            <input type="submit" name="button" value="MA" class="btnMa">
                                        </td>
                                        <td>
                                            <input type="submit" name="button" value="Reception" class="btnRec">
                                        </td>
                                        <td>
                                            <input type="submit" name="button" value="Referrals" class="btnRef">
                                        </td>
                                        <td>
                                            <input type="submit" name="button" value="Provider" class="btnPro">
                                        </td>
                                        <td>
                                            <input type="submit" name="button" value="Clear" class="btnOthers">
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                            </form>
                        </td>
                        <td>
                            <form action='/pushNewNote.php'>
                                <table width="100%" cellpadding="0px" cellspacing="0px" style="border-radius: 10px">
                                    <tbody>
                                    <tr>
                                        <td colspan="5" >
                                            <textarea rows="2" name="message" style="border-radius: 10px; resize: none; width: 100%; overflow: auto"></textarea>
                                        </td>
                                    </tr>
                                    <tr valign="center" aria-rowspan="5px">
                                        <td valign="center">
                                            <input type="submit" name="button" value="Add Note" class="btnOthers">
                                            <input type="hidden" name="refNum" value="<?php echo $referralID?>">
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