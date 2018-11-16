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
        <td colspan="4">
            <table style="font-size: 20px" width="100%">
                <tbody>
                <tr>
                    <td width="20%">
                        <?php echo $patientName?>
                    </td>
                    <td width="20%">
                        DOB: <?php echo  date("m-d-Y", strtotime($DOB))?>
                    </td>
                    <td width="20%">
                        <div id="id01">
                            <form action="/patientInfo/updatePatient.php" method="get">
                                Phone Number: <input id="phone" type="tel" value="<?php echo $phoneNumber?>" name="phone">
                                <button type="submit" id="update">Update</button>
                            </form>
                        </div>
                    </td>
                    <td width="30%" align="right">
                        <div class="dropdown">
                            <button class="dropbtn">Encounters</button>
                            <div class="dropdown-content">
                                <?php echo $encounters?>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="dropbtn">Other Attachments</button>
                            <div class="dropdown-content">
                                <a href="uploadFile.php">Upload attachment</a>
                            </div>
                        </div>
                    </td>

                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr valign="top">
        <td height="700px" style="width: 25%; border-radius: 10px;background-color:#FFFFFF" >
            <div style="overflow-y: scroll; height:650px">
                <table width="100%" cellspacing="10px" cellpadding="5px" >
                    <tbody>
                    <tr>
                        <td style="font-size: 20px; font-weight: bold" width="50%">
                            Phone Records
                        </td>
                    </tr>
                    <?php
                    $query = 'SELECT * FROM PatientPhoneMessages WHERE PatientID=\'' . $_SESSION['currentPatient'] . '\' ORDER BY ID DESC' ;
                    $result = $conReferrals->query($query);
                    while ($row = $result->fetch_row()){
                        $messageGroup = $row[5];
                        switch ($messageGroup){
                            case 'Admin':
                                echo "<tr style=\"background-color: #0066ff\">";
                                break;

                            case 'Reception':
                                echo "<tr style=\"background-color: #F4D03F\">";
                                break;

                            case 'Provider':
                                echo "<tr style=\"background-color: #E74C3C\">";
                                break;

                            case 'Referrals':
                                echo "<tr style=\"background-color: #BB8FCE\">";
                                break;

                            case 'MA':
                                echo "<tr style=\"background-color: #45B39D\">";
                                break;

                        }
                        echo "
                                <td style=\"border-radius: 7px\">
                                    " . $row[2] . " " . $row[3] . " <br/> " . $row[4] . "
                                </td>
                            </tr>
                        ";
                    }
                    ?>

                    </tbody>
                </table>
            </div>
        </td>
        <td style=" width: 25%; border-radius: 10px;background-color:#FFFFFF">
            <div style="overflow-y: scroll; height:650px">
                <table width="100%" cellspacing="10px" cellpadding="5px" >
                    <tbody>
                    <tr>
                        <td style="font-size: 20px; font-weight: bold" width="50%">
                            Messages
                        </td>
                        <td align="right">
                            <?php
                            switch ($alert){
                                case(2):
                                    echo "Reception";
                                    break;
                                case(3):
                                    echo "Provider";
                                    break;
                                case(4):
                                    echo "Referrals";
                                    break;
                                case(5):
                                    echo "MA";
                                    break;

                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                    $query = 'SELECT * FROM MessageAboutPatient WHERE PatientID=\'' . $_SESSION['currentPatient'] . '\' ORDER BY ID DESC' ;
                    $result = $conReferrals->query($query);
                    while ($row = $result->fetch_row()){
                        $messageGroup = $row[5];
                        switch ($messageGroup){
                            case 'Admin':
                                echo "<tr style=\"background-color: #0066ff\">";
                                break;

                            case 'Reception':
                                echo "<tr style=\"background-color: #F4D03F\">";
                                break;

                            case 'Provider':
                                echo "<tr style=\"background-color: #E74C3C\">";
                                break;

                            case 'Referrals':
                                echo "<tr style=\"background-color: #BB8FCE\">";
                                break;

                            case 'MA':
                                echo "<tr style=\"background-color: #45B39D\">";
                                break;

                        }
                        echo "
                                <td style=\"border-radius: 7px\" colspan='2'>
                                    " . $row[2] . " " . $row[3] . " <br/> " . $row[4] . "
                                </td>
                            </tr>
                        ";
                    }
                    ?>

                    </tbody>
                </table>
            </div>
        </td>
        <td style=" width: 50%; border-radius: 10px;background-color:#FFFFFF">
            <div style="overflow-y: scroll; height:650px">
                <table width="100%" cellspacing="10px" cellpadding="5px" >
                    <tbody>
                        <tr>
                            <td style="font-size: 20px; font-weight: bold" width="50%">
                                Referral
                            </td>

                        </tr>
                        <tr>
                            <table cellpadding="15px" cellspacing="15px" width="100%" >
                                <tbody>
                                    <form action="newReferral.php">
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
                                                Reason <input name="Reason" type="text">
                                            </td>
                                            <td>
                                                Status: <select name="status">
                                                            <option value="1">Pending Soap</option>
                                                            <option value="2">Pending Insurance Authorization</option>
                                                            <option value="3">Pending Specialist Review</option>
                                                            <option value="4">Pending Appointment Review</option>
                                                            <option value="5">Pending Couldn't  be Reached by Specialist</option>
                                                            <option value="6">Pending Declined bt Specialist</option>
                                                            <option value="7">Pending Insurance doesn't cover</option>
                                                            <option value="8">Patient Declined Appointment</option>
                                                            <option value="9">Provider to Referral</option>
                                                            <option value="10">Completed</option>
                                                            <option value="11">Unsuccessful contact</option>
                                                        </select>
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
                                                Specialty
                                            </td>
                                            <td>
                                                Specialist
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="submit" value="Submit new referral">
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
                    </tbody>
                </table>
            </div>
        </td>
    </tbody>
</table>

</body>

</html>