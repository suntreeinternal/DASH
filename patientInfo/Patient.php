<?php

    //TODO add error if patient is lost in soapware
    //TODO lots of testing to make sure that patient is added the correct way.
    session_start();
    if (sizeof($_SESSION) == 0){
        header('location:../index.html');
    }
    $patientName = '';
    $DOB = '';
    $phoneNumber = '';
    $last = $_GET['last'];
    $date = $_GET['date'];
    $_SESSION['previous'] = "patientInfo/Patient.php?last=" . $last . "&date=" . $date;
    $date = str_ireplace('-', '',$date);

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
    $_SESSION['previous'] = 'location:/patientInfo/Patient.php?last=' . $last . '&date=' . $_GET['date'];

/**
 * check to see if the patient exists as a temp patient
 */
    $query = 'SELECT * FROM Referrals.TempPatient WHERE LastName=\''. $last . '\' AND BirthDate=\''. $date . '\'';
    $result = $conReferrals->query($query);
    $row = $result->fetch_row();
    if (sizeof($row) == 0) {
        //not temp
        //check to see if patient exists in SW
        $query = 'SELECT * FROM dbo.Gen_Demo WHERE last_name=\''. $last .'\' AND birthdate=\''. $date . '\'';
        $result = mssql_query($query);
        $row = mssql_fetch_array($result);
        if (sizeof($row) == 1) {

            //not in SW or Temp
            header('location:/NewPatient/NewPatient.php?last=' . $last . '&date=' . $_GET['date']);
            die();
        } else {

            //yes is in sw not temp
            $SwID = $row[0];
            $patientName = ($row[2] . " " . $row[1]);
            $DOB = $row['birthdate'];
            $phoneNumber = $row['work_phone'];
            $query = 'SELECT * FROM Referrals.PatientData WHERE SW_ID=\'' . $SwID . '\'';
            $result = $conReferrals->query($query);
            $row = $result->fetch_row();


            if (sizeof($row) == 0){

                $query = 'INSERT INTO PatientData (SW_ID, Message_alert_to_group, Note, Phone_number) VALUES (\'' . $SwID . '\',0,\'\',0)';
                $result = $conReferrals->query($query);

                $query = 'SELECT * FROM Referrals.PatientData WHERE SW_ID=\'' . $SwID . '\'';

                $result = $conReferrals->query($query);
                $row = $result->fetch_row();
                $patientID = $row[0];
            } else {

                $phoneNumber = $row[4];
                $alert = $row[2];
                $patientID = $row[0];

                if(ctype_digit($phoneNumber) && strlen($phoneNumber) == 10) {

                    $phoneNumber = substr($phoneNumber, 0, 3) .'-'. substr($phoneNumber, 3, 3) .'-'. substr($phoneNumber, 6);
                } else {

                    if(ctype_digit($phoneNumber) && strlen($phoneNumber) == 7) {
                        $phoneNumber = substr($phoneNumber, 0, 3) .'-'. substr($phoneNumber, 3, 4);
                    }
                }
            }

            $query = 'SELECT * FROM dbo.Encounters WHERE Patient_ID=\'' . $patientID . '\' ORDER BY visit_date DESC ';
            $result = mssql_query($query);
            $encounters = "";
            while ($row = mssql_fetch_array($result)) {
                $encounters .= '<a href="../SoapNote.php?ID=' . $row[0] . '" target=\"_blank\">' . str_ireplace(':00:000', '', $row['visit_date']) . '</a>';
            }
        }
    } else {
        $patientID = $row[0];

        //yes is a temp patient
        $patientName = $row[1] . " " . $row[2];
        $DOB = $row[3];
        $query = 'SELECT * FROM dbo.Gen_Demo WHERE last_name=\''. $last .'\' AND birthdate=\''. $date . '\'';
        $result = mssql_query($query);
        $row = mssql_fetch_array($result);

        if (sizeof($row) == 1) {

            //not in sw yet
            $query = 'SELECT * FROM Referrals.PatientData WHERE SW_ID=\'' . $patientID . '\'';
            $result = $conReferrals->query($query);
            $row = $result->fetch_row();
            $patientID = $row[0];



            if (sizeof($row) == 0){

                $query = 'INSERT INTO PatientData (SW_ID, Message_alert_to_group, Note, Phone_number) VALUES (\'' . $patientID . '\',0,\'\',0)';
                $result = $conReferrals->query($query);
            } else {

                $phoneNumber = $row[4];
                $alert = $row[2];

                if(ctype_digit($phoneNumber) && strlen($phoneNumber) == 10) {
                    $phoneNumber = substr($phoneNumber, 0, 3) .'-'. substr($phoneNumber, 3, 3) .'-'. substr($phoneNumber, 6);
                } else {
                    if(ctype_digit($phoneNumber) && strlen($phoneNumber) == 7) {
                        $phoneNumber = substr($phoneNumber, 0, 3) .'-'. substr($phoneNumber, 3, 4);
                    }
                }
            }
        } else {

            // in sw
            header("location:/patientInfo/mergePatient.php?last=" . $last . "&date=" . $date );
            die();
        }
    }
//    echo $patientID;
    $_SESSION['currentPatient'] = $patientID;
    $_SESSION['patientName'] = $patientName;
    $_SESSION['patientDOB'] = $DOB;
    ?>


<html>
<head>
    <link rel="stylesheet" href="../Menu/menu.css">
    <style>
        .datatable table {
            border-collapse: collapse;
            width: 100%;
            /*border: 0px;*/
        }

        .datatable th, td {
            /*text-align: left;*/
            /*padding-top: 8px;*/
            /*padding-bottom: 8px;*/
        }

        .datatable tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .dropbtn {
            background-color: #4CAF50;
            color: white;
            /*padding: 16px;*/
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
                            <form action="updatePatient.php" method="get">
                                Phone Number: <input id="phone" type="tel" value="<?php echo $phoneNumber?>" name="phone">
                                <button type="submit" id="update">Update</button>
                            </form>
                        </div>
                    </td>
                    <td width="30%" align="right">
                        <div class="dropdown" >
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
                <table width="100%" cellspacing="10px" >
                    <tbody>
                    <tr>
                        <td style="font-size: 20px; font-weight: bold" width="50%">
                            Phone Records
                        </td>
                    </tr>
                    <?php
                    $query = 'SELECT * FROM PatientPhoneMessages WHERE PatientID=\'' . $patientID. '\' ORDER BY ID DESC' ;
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
                    $query = 'SELECT * FROM MessageAboutPatient WHERE PatientID=\'' . $patientID . '\' ORDER BY ID DESC' ;
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
        <td height="700px" style="width: 25%; border-radius: 10px;background-color:#FFFFFF">

            <table width="100%">
                <tbody>
                    <tr>
                        <td style="font-size: 20px; font-weight: bold">
                            Pending Action Items
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div style="overflow-y: scroll; height:650px">
                                <table width="100%" class="datatable"  cellpadding="10px">
                                    <tbody>
                                        <tr valign="center">
                                            <th width="33%">
                                                Status
                                            </th>
                                            <th width="33%">
                                                Provider
                                            </th>
                                            <th width="34%">
                                                Date
                                            </th>
                                        </tr>
                                        <?php
                                        $query = 'SELECT * FROM Referrals.Referrals WHERE PatientID=\'' . $patientID . '\' AND Status <> \'10\'';

//                                        $query = ' . $patientID . '\' AND Status <> \'10\'';
                                        $result = $conReferrals->query($query);
                                        while ($row = $result->fetch_row()){
                                            echo "<tr><td>";
                                                $tempStatus = $row[3];
                                                switch ($tempStatus){
                                                    case '1':
                                                        echo "Pending Soap";
                                                        break;
                                                    case '2':
                                                        echo "Pending Insurance Authorization";
                                                        break;
                                                    case '3':
                                                        echo "Pending Specialist Review";
                                                        break;
                                                    case '4':
                                                        echo "Pending Appointment Review";
                                                        break;
                                                    case '5':
                                                        echo "Pending Couldn't  be Reached by Specialist";
                                                        break;
                                                    case '6':
                                                        echo "Pending Declined by Specialist";
                                                        break;
                                                    case '7':
                                                        echo "Pending Insurance doesn't cover";
                                                        break;
                                                    case '8':
                                                        echo "Patient Declined Appointment";
                                                        break;
                                                    case '9':
                                                        echo "Provider to Referral";
                                                        break;
                                                    case '10':
                                                        echo "Completed";
                                                        break;
                                                    case '11':
                                                        echo "Unsuccessful contact";
                                                        break;
                                                }
                                            echo "</td><td>";
                                            $query = 'SELECT * FROM Referrals.Provider WHERE ID=\'' . $row[1] . '\'';
                                            $temp = $conReferrals->query($query);
                                            $temp1 = $temp->fetch_row();
                                            echo $temp1[1];
                                            echo "</td><td>";
                                            echo DateTime::createFromFormat("Y-m-d H:i:s", $row[7])->format("m/d/Y");
                                            echo "</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
        <td height="700px" style="width: 25%; border-radius: 10px;background-color:#FFFFFF">
            <table width="100%">
                <tbody>
                    <tr>
                        <td style="font-size: 20px; font-weight: bold">
                            Completed Action Items
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div style="overflow-y: scroll; height:650px">
                                <table width="100%" class="datatable" cellpadding="10px">
                                    <tbody>
                                    <tr valign="center">
                                        <th width="33%">
                                            Item
                                        </th>
                                        <th width="33%">
                                            Provider
                                        </th>
                                        <th width="34%">
                                            Date
                                        </th>
                                    </tr>
                                    <?php
                                    $query = 'SELECT * FROM Referrals.Referrals WHERE PatientID=\'' . $patientID . '\' AND Status = \'10\'';
                                    $result = $conReferrals->query($query);
                                    while ($row = $result->fetch_row()){
                                        echo "<tr><td>";
                                        echo "Referral";
                                        echo "</td><td>";
                                        $query = 'SELECT * FROM Referrals.Provider WHERE ID=\'' . $row[1] . '\'';
                                        $temp = $conReferrals->query($query);
                                        $temp1 = $temp->fetch_row();
                                        echo $temp1[1];
                                        echo "</td><td>";
                                        echo DateTime::createFromFormat("Y-m-d H:i:s", $row[7])->format("m/d/Y");
                                        echo "</td></tr>";
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
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
        <td valign="top">
            <form action='/selectButton.php'>
                <table width="100%" cellpadding="0px" cellspacing="0px" style="border-radius: 10px">
                    <tbody>
                    <tr>
                        <td>
                            <input type="submit" name="button" value="New Referral" class="btnOthers">
                        </td>
                        <td>
                            <input type="submit" name="button" value="New Rx" class="btnOthers">
                        </td>
                        <td>
                            <input type="submit" name="button" value="New Record" class="btnOthers">
                        </td>
                    </tr>
                    </tbody>
                </table>

            </form>
        </td>
    </tr>
    </tbody>
</table>


</body>

</html>