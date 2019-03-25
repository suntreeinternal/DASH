<?php
    session_start();
    if (sizeof($_SESSION) == 0){
        header('location:../index.html');
    }
    $patientName = $_SESSION['patientName'];
    $DOB = $_SESSION['patientDOB'];
    $phoneNumber = '';

    $currentReferral = '';


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
    $row = $result->fetch_row();
    $specalty = $specalty . '<option value="' . $row[0] . '">' . $row[1] . '</option>';
    $SelectedSpecality = $row[0];
    while ($row = $result->fetch_row()){
        $specalty = $specalty . '<option value="' . $row[0] . '">' . $row[1] . '</option>';
    }



    $dateTime = date("Y-m-d h:i:sa");
    $query = 'SELECT * FROM Referrals.Status';
    $result = $conReferrals->query($query);
    $status = '<select name="status">';

    while ($row = $result->fetch_row()){
        if ($row[0] == 5){
            $status = $status . '<option selected="selected" value="'. $row[0] .'">'. $row[1] .'</option>';
        } else {
            $status = $status . '<option value="'. $row[0] .'">'. $row[1] .'</option>';
        }
    }
    $status = $status . '</select>';

    $query = 'SELECT * FROM Referrals.Specialist WHERE SpecialtyID=' .  $SelectedSpecality;
    $result = $conReferrals->query($query);
    while ($row = $result->fetch_row()){
        $specalist = $specalist . '<option value="'. $row[0] .'">'. $row[2] .'</option>';
    }
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
                                Referral
                            </td>

                        </tr>
                        <tr>
                            <table cellpadding="15px" cellspacing="15px" width="100%" >
                                <tbody>
                                    <form action="newReferral.php" name="referral">
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
                                                Status: <?php echo $status?>
                                                <input type="hidden" name="dateTime" value="<?php echo $dateTime?>">

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Priority: <select name="priority">
                                                                <option selected="selected" value="1">ASAP</option>
                                                                <option value="3">Routine</option>
                                                            </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Specialty: <select name="Specialty" onchange="getData()">
                                                                <?php echo $specalty?>
                                                           </select>
                                            </td>
                                            <td>
                                                Specialist: <select name="Specalist" id="specalist">
                                                                <?php echo $specalist?>
                                                            </select>
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
<script>
    function getData() {
        var name = document.forms['referral']['Specialty'].value;
        var xmlhttp = new XMLHttpRequest();
        if (name !="") {

            xmlhttp.onreadystatechange = function () {
                document.getElementById('specalist').innerHTML = this.responseText;
            };
            xmlhttp.open("GET", "loadSpecalist.php?specality=" + name , true);
            xmlhttp.send();
        }
        return 0;

    }
</script>

</body>

</html>