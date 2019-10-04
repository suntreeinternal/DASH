<?php
    session_start();
    include_once "../fetchPatientData/patientInfo.php";
//    echo var_dump(get_included_files());
    $patientInfo = new Patient();

    if (sizeof($_SESSION) == 0){
        header('location:../index.html');
    }
    $patientName = $_SESSION['patientName'];
    $DOB = $_SESSION['patientDOB'];
    $phoneNumber = '';
    $reason = "";

    $currentReferral = '';

    $con = mssql_connect('sunserver', 'siminternal', 'Watergate2015');
    $conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
    if (!mssql_select_db('sw_charts', $con)) {
        die('Unable to select database!');
    }
    $query = 'SELECT * FROM Referrals.Referrals WHERE ID=\'' . $_GET['typeID'] . '\'';
    $result = $conReferrals->query($query);
    $row = $result->fetch_row();

//    var_dump($row);

    $currentReferral = $row[1] . $row[2] . $row[3] . $row[4] . $row[5] . $row[6] . $row[7] . $row[8] . $row[9];

    $dateTime =$row[7];
    $reason = $row[6];
    $createdBy = $row[11];
    if ($row[10]){
        $ReferralSentDate = date_create($row[10]);
        $ReferralSentDate = date_format($ReferralSentDate, "m/d/Y H:i:s");
    } else {
        $ReferralSentDate = "Has not been sent yet";
    }
    $phoneNumber = $row[4];

    $alert = $row[2];
    $referralID = $row[0];
    $priority = $row[4];
    $currentStatus = $row[3];
    $currentProvider = $row[1];
    $currentSpeacalist = $row[9];
    $currentSpecality = $row[8];
    $updated = $row[12];
    $contacted = $row[13];
    $dateContacted = $row[14];
    if(ctype_digit($phoneNumber) && strlen($phoneNumber) == 10) {
        $phoneNumber = substr($phoneNumber, 0, 3) .'-'. substr($phoneNumber, 3, 3) .'-'. substr($phoneNumber, 6);
    } else {
        if(ctype_digit($phoneNumber) && strlen($phoneNumber) == 7) {
            $phoneNumber = substr($phoneNumber, 0, 3) .'-'. substr($phoneNumber, 3, 4);
        }
    }

    $priorityPrint = '';
    if ($priority == 1){

       $priorityPrint ='Priority: <select name="priority">
                                            <option selected="selected" value="1">ASAP</option>
                                            <option value="3">Routine</option>
                                        </select>';
    } elseif ($priority == 3){
        $priorityPrint ='Priority: <select name="priority">
                                            <option value="1">ASAP</option>
                                            <option selected="selected" value="3">Routine</option>
                                        </select>';
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


    //Loading Specalist
    $query = 'SELECT * FROM Referrals.Specialty';
    $result = $conReferrals->query($query);
    $specalty = "";
    if ($currentSpecality == -1){
        $specalty = $specalty . '<option selected="selected" value="-1"></option>';
    }
    while ($test = $result->fetch_row()){
        if($test[0] == $currentSpecality){
            $specalty = $specalty . '<option selected="selected" value="'. $test[0] .'">'. $test[1] .'</option>';
        } else {
            $specalty = $specalty . '<option value="' . $test[0] . '">' . $test[1] . '</option>';
        }
    }




    $query = 'SELECT * FROM Referrals.Specialist WHERE SpecialtyID=' . $currentSpecality;
    $result = $conReferrals->query($query);
    $speacalist = "";
    if ($currentSpeacalist == -1){
        $speacalist = $speacalist . '<option selected="selected" value="-1"></option>';
    } else {
        $speacalist = $speacalist . '<option value="-1"></option>';
    }
$speacalistTable = "<table id='specialistInfo' border='1' style='border-collapse: collapse' width='100%'><tbody><tr><th>Location</th><th>Fax</th><th>Phone</th><th>Notes</th></tr>";
    while ($row = $result->fetch_row()){
        if($row[0] == $currentSpeacalist){
            $test = "";
            $speacalist = $speacalist . '<option selected="selected" value="'. $row[0] .'">'. $row[2] . '</option>';
//            $speacalistTable = "<table id='specialistInfo' border='1' style='border-collapse: collapse' width='100%'><tbody><tr><th>Location</th><th>Fax</th><th>Phone</th><th>Notes</th></tr>";
            $speacalistTable .= "<tr><td>". $row[3] ."</td>";
            $speacalistTable .= "<td>". $row[5] ."</td>";
            $speacalistTable .= "<td>". $row[4] ."</td>";
            $speacalistTable .= "<td>". $row[6] ."</td></tr>";
//            $speacalistTable .= "</tbody></table>";
        } else {
            $speacalist = $speacalist . '<option value="' . $row[0] . '">' . $row[2] . ', ' . $row[3] . ', ' . $row[5] . '</option>';
        }
    }
$speacalistTable .= "</tbody></table>";


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

    $query = "SELECT * FROM Referrals.PagesBeingViewed WHERE itemId='" . $_GET['ReferralID'] ."'AND type='1'";

    $patientInfo->SelectPatient($_SESSION['currentPatient']);
    $_SESSION['swID'] = $patientInfo->getSwId();

    $_SESSION['previous'] = "location:/patientInfo/Patient.php?last=" . $patientInfo->GetLastName() . "&date=" . $patientInfo->GetDOB();


    //    $patientInfo->shutdown();

    $result = $conReferrals->query($query);
    $viewingName = '';
    $row = $result->fetch_row();
    if ($row) {
        $viewingName = $row[1];
        if ($viewingName == $_SESSION['user']){
            $viewingName = '';
        }
    } else {
        $query = "INSERT INTO Referrals.PagesBeingViewed(userName, ItemId, type) VALUES ('" . $_SESSION['user'] . "','" . $_GET['ReferralID'] . "','1')";
        $result = $conReferrals->query($query);
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
    <script type="text/javascript">
        function thisFunction(){
            var x = new XMLHttpRequest();
            x.open("GET","leavePage.php?id=<?php echo $_GET['ReferralID'] ?>",true);
            x.send();
            return false;
        }
    </script>

</head>

<?php
    echo '<body style="background:darkgray;">';
?>
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
        <?php
        echo '<td style=" width: 25%; border-radius: 10px;background-color:#FFFFFF">';


        ?>
            <div style="height:650px">
                <table width="100%" cellspacing="10px" cellpadding="5px" >
                    <tbody>
                    <tr>
                        <td style="font-size: 20px; font-weight: bold" width="50%">
                            Referral  &nbsp &nbsp &nbsp &nbsp &nbsp <!--Being viewed by: <?php echo $viewingName;?>-->
                        </td>
                    </tr>
                    <tr>
                        <table cellpadding="5px" cellspacing="15px" width="100%" >
                            <tbody>
                            <form action="updateReferral.php" id="referral">
                                <tr>
                                    <td width="50%">
                                        Date created: <?php echo $dateTime?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Created by: <?php echo $createdBy?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Updated by: <?php echo $updated?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Patient Notified: Office
                                        <?php
                                            if ($contacted == 1){
                                                echo '<input type="checkbox" checked name="Contacted">';
                                            } else {
                                                echo '<input type="checkbox" name="Contacted">';
                                            }
                                        ?>
                                        Phone
                                        <?php
                                        if ($contacted == 2){
                                            echo '<input type="checkbox" checked name="phone">';
                                        } else {
                                            echo '<input type="checkbox" name="phone">';
                                        }
                                        ?>
                                        <input type="button" onclick="f()" value="Print">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Date Notified: <input type="text" name="notifiedDate" value="<?php echo $dateContacted?>">
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
                                        <input type="hidden" name="test" value="<?php echo $currentReferral?>">
                                        <input type="hidden" name="refID" value="<?php echo $_GET['typeID']?>" >
                                        <input type="hidden" name="goback" value="<?php echo $_GET['goback']?>">

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo $priorityPrint?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Specialty: <select name="Specialty" onchange="getData()">
                                            <?php echo $specalty?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Specialist: <select name="Specalist" id="specalist" onclick="updateData()">
                                            <?php echo $speacalist?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo $speacalistTable?>
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


                        <?php
                            if ($_SESSION['group'] == "Referrals"){
                                echo "<a href=\"../Referral/SendReferral.php?ReferralID='" . $referralID . "'\">
                                        <button> Referral Sent:". $ReferralSentDate . "</button>
                                        </a><br/><br/>";
                            }
                        ?>

                        <a href="../Referral/recieved.php?ReferralID='<?php echo $referralID ?>'">
                            <button> Referral Received</button>
                        </a>
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
                                            <input type="submit" name="button" value="Add new message" class="btnOthers">
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                            </form>
                        </td>
                        <td>
                            <form action='/patientInfo/NewNote.php'>
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
    </tr>
    </tbody>
</table>
<script>
    function f() {
        // var val = document.getElementById("Specialty").value;
        window.open('<?php echo "ReferralPrintOut.php?patient=" . $patientInfo->GetFullName() . "&specality=" . $currentSpecality . "', '_blank'"?>);
    }
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

    function updateData() {
        var name = document.forms['referral']['Specalist'].value;
        var xmlhttp = new XMLHttpRequest();
        if (name != "") {
            xmlhttp.onreadystatechange = function () {
                document.getElementById('specialistInfo').innerHTML = this.responseText;
            };
            xmlhttp.open("GET", "loadSpecalistData.php?id=" + name, true);
            xmlhttp.send();
        }
        return 0;
    }
</script>
</body>

</html>