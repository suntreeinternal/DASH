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
    if ($date == ''){
        header('location:/patientInfo/selectPatient.php?last=' . $last );
        die();
    }
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
    $query = 'SELECT * FROM Referrals.TempPatient WHERE LOWER(LastName)=\''. strtolower($last) . '\' AND BirthDate=\''. $date . '\'';
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

            $query = "SELECT * FROM Referrals.PatientData WHERE SW_ID='" . $SwID . "'";
            $swValFromSw = $conReferrals->query($query);
            if ($swValFromSw->num_rows > 1) {
                $firstOne = $swValFromSw->fetch_row();
                $mainVal = $firstOne[0];
                while ($others = $swValFromSw->fetch_row()){
                    $query = "UPDATE Referrals.Rx SET PatientID=" . $mainVal . " WHERE PatientID=" . $others[0];
                    $conReferrals->query($query);
                    $query = "UPDATE Referrals.Referrals SET PatientID=" . $mainVal . " WHERE PatientID=" . $others[0];
                    $conReferrals->query($query);
                    $query = "UPDATE Referrals.RecordRequest SET PatientID=" . $mainVal . " WHERE PatientID=" . $others[0];
                    $conReferrals->query($query);
                    $query = "UPDATE Referrals.MedsAuth SET PatientID=" . $mainVal . " WHERE PatientID=" . $others[0];
                    $conReferrals->query($query);
                    $query = "UPDATE Referrals.PatientPhoneMessages SET PatientID=" . $mainVal . " WHERE PatientID=" . $others[0];
                    $conReferrals->query($query);
                    $query = "DELETE FROM Referrals.PatientData WHERE ID=" . $others[0];
                    $conReferrals->query($query);

                }
            }

            $patientName = ($row[2] . " " . $row[1]);
            $first = $row[2];
            $last = $row[1];
            $DOB = $row['birthdate'];
            $phoneNumber = $row['work_phone'];
            $query = 'SELECT * FROM Referrals.PatientData WHERE SW_ID=\'' . $SwID . '\'';
            $result = $conReferrals->query($query);
            $row = $result->fetch_row();



            if (sizeof($row) == 0){
                //TODO add to Change log

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

            $query = 'SELECT * FROM dbo.Encounters WHERE Patient_ID=\'' . $SwID . '\' ORDER BY visit_date DESC ';
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
        $pipek = $row[4];
        $DOB = $row[3];
        $query = 'SELECT * FROM dbo.Gen_Demo WHERE last_name=\''. $last .'\' AND birthdate=\''. $date . '\'';
        $result = mssql_query($query);
        $row = mssql_fetch_array($result);

//        echo sizeof($row);

//        var_dump($row);

        if (sizeof($row) == 1) {

            //not in sw yet
            $query = 'SELECT * FROM Referrals.PatientData WHERE SW_ID=\'' . $patientID . '\'';
            $result = $conReferrals->query($query);
            $row = $result->fetch_row();
            $patientID = $row[0];
//            var_dump($patientID);

            if (sizeof($row) == 0){
                //TODO add to Change log

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

            while ($row = mssql_fetch_array()){
                echo var_dump($row) . " <br/><br/>";
            }
            if ($pipek == 1){
                echo '<br/>test';

            } else {

                header("location:/patientInfo/mergePatient.php?last=" . $last . "&date=" . $date);
            }
            die();
        }
    }
//    echo $patientID;
    $_SESSION['currentPatient'] = $patientID;
    $_SESSION['patientName'] = $patientName;
    $_SESSION['patientDOB'] = $DOB;
    $_SESSION['swID'] = $SwID;
//    echo $_SESSION['swID'];


?>


<html>
<!--<meta http-equiv="refresh" content="10" />-->
<head>
    <script>
        function convertDate(d) {
            var p = d.split("/");
            return +(p[2]+p[1]+p[0]);
        }

        function sortByDate() {
            var tbody = document.querySelector("#results tbody");
            // get trs as array for ease of use
            var rows = [].slice.call(tbody.querySelectorAll("tr"));

            rows.sort(function(a,b) {
                return convertDate(a.cells[2].innerHTML) - convertDate(b.cells[2].innerHTML);
            });

            rows.forEach(function(v) {
                tbody.appendChild(v); // note that .appendChild() *moves* elements
            });
        }
    </script>
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
            right: 0;
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
        <?php include "patientInfoHeader.php"?>
    </tr>
    <tr valign="top">
        <?php include "PhoneRecord.php"?>
        <?php include "Messages.php"?>
        <?php include "pendingAction.php"?>
        <?php include "CompletedAction.php"?>
    </tr>
    <tr>
        <td>
            <form action='/pushNewPhoneMessage.php'>
                <table width="100%" cellpadding="0px" cellspacing="0px" style="border-radius: 10px">
                    <tbody>
                    <tr>
                        <td colspan="5" >
                            <textarea rows="6" name="message" style="border-radius: 10px; resize: none; width: 100%; overflow: auto"></textarea>
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
                            <textarea rows="6" name="message" style="border-radius: 10px; resize: none; width: 100%; overflow: auto"></textarea>
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
                        <td>
                            <input type="submit" name="button" value="Meds Auth" class="btnOthers">
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
<script type="text/javascript">
    window.onbeforeunload = StartInterval;
    function StartInterval() {
        setInterval("StartTime();", 1000);
    }
    function StartTime() {
        document.getElementById('testPhone').innerHTML = Date();
    }
</script>
</html>