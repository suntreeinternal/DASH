<?php
    session_start();
    if (sizeof($_SESSION) == 0){
        header('location:../index.html');
    }
    if($_SESSION['loggedIn'] == false){
        header('location=../index.html');
    }
    $ahPhone = 0;
    $shPhone = 0;
    $ecPhone = 0;
    $ghPhone = 0;
    $abPhone = 0;
    $skPhone = 0;
    $jsPhone = 0;
    $dtPhone = 0;
    $ssPhone = 0;
    $ncPhone = 0;
    $ahRX = 0;
    $shRX = 0;
    $ecRX = 0;
    $ghRX = 0;
    $abRX = 0;
    $skRX = 0;
    $jsRX = 0;
    $dtRX = 0;
    $ssRX = 0;
    $ncRX = 0;
    $ASAP = 0;
    $newPatient = 0;
    $newReferral = 0;
    $pendingApt = 0;
    $pendingSoap = 0;
    $pendingDemo = 0;
    $waitingCK = 0;
    $needApproval = 0;
    $approvedRecord = 0;
    $sentRecord = 0;
    $seeMe = 0;
    $ma = 0;
    $reception = 0;
    $provider = 0;
    $records = 0;
    $rxToMa = 0;
    $rxToReception = 0;
    $pharmacyCalled = 0;
    $rxToEscribe = 0;
    $newMed = 0;
    $pendingMed = 0;
    $denialMed = 0;
    $approvedMed = 0;
    $otherMed = 0;


    $con = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');


    $query = 'SELECT COUNT(*) FROM Referrals.Referrals WHERE Status=1';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $pendingSoap = $row[0];

    $query = 'SELECT COUNT(*) FROM Referrals.Referrals WHERE LastSent IS NULL';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $newReferral = $row[0];

    $query = 'SELECT COUNT(*) FROM Referrals.Referrals WHERE Priority=0';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $ASAP += $row[0];

    $query = 'SELECT COUNT(*) FROM PatientData WHERE Message_alert_to_group=2';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $reception = $row[0];

    $query = 'SELECT COUNT(*) FROM PatientData WHERE Message_alert_to_group=5';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $ma = $row[0];

    $query = 'SELECT COUNT(*) FROM PatientData WHERE Message_alert_to_group=4';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $referrals = $row[0];

    $query = 'SELECT COUNT(*) FROM PatientData WHERE Message_alert_to_group=3';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $provider = $row[0];

    $query = 'SELECT COUNT(*) FROM Referrals.Referrals WHERE Status=0';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $pendingDemo = $row[0];

    $query = 'SELECT COUNT(*) FROM Referrals.Referrals WHERE Status=4';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $pendingApt = $row[0];

    $query = 'SELECT COUNT(*) FROM Referrals.TempPatient';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $newPatient = $row[0];

    $query = 'SELECT COUNT(*) FROM Referrals.Referrals WHERE Authorization=4';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $pendingAuth = $row[0];

    $phoneStats = '';
    $query = 'SELECT * FROM Referrals.Provider WHERE Active=1';
    $result = $con->query($query);
    while ($row = $result->fetch_row()){
//        echo var_dump($row);
        $val = $row[0]+4;
        $query = 'SELECT COUNT(*) FROM Referrals.PatientPhoneMessages WHERE AlertToGroup=' . $val;
        $resultCount = $con->query($query);
        $valCount = $resultCount->fetch_row();
        $query = 'SELECT * FROM Referrals.PatientPhoneMessages WHERE AlertToGroup=' .$val;
        $phoneStats .= '<tr><td><a style="background-color:'. $row[4] . ' ; color:' . $row[5] . '" href="../Reports/frontPagePhoneReport.php?query=' . $query . '" class="notification"><span>' . $row[2] . '</span><span class="badge">' . $valCount[0] . '</span></a></td></tr>';
    }

    $RxStats = '';
    $query = 'SELECT * FROM Referrals.Provider WHERE Active=1';
    $result = $con->query($query);
    while ($row = $result->fetch_row()){

        $RxStats .= '<tr><td><a style="background-color:'. $row[4] . ' ; color:' . $row[5] . '" href="#" class="notification"><span>' . $row[2] . '</span><span class="badge">' . $ahRX . '</span></a></td></tr>';
    }

?>
<html>
    <head>
        <link rel="stylesheet" href="Menu/menu.css">
        <title>DASH: <?php echo $_SESSION['name']?></title>
        <style>
        .notification {
            background-color: #2A7C46;
            color: #001b00;
            text-decoration: none;
            padding: 15px 26px;
            position: relative;
            display: inline-block;
            border-radius: 2px;
            width: 120px;
        }

        .notification:hover {
            background: #b65b5b;
        }

        .notification .badge {
            position: absolute;
            top: -10px;
            right: -10px;
            padding: 5px 10px;
            border-radius: 50%;
            background-color: red;
            color: white;
        }
        </style>
    </head>

    <body style="background:#7cba92;">
    <?php include "Menu/menu.php"?>
    <table cellpadding="15px" width="100%">
        <tbody>
            <tr valign="center">
                <td>
                    <form action="\patientInfo\Patient.php">
                        Last name
                        <input type="text" name="last" style="width: 180px; height: 30px">
                        Birth Date
                        <input type="date" name="date" style="width: 180px; height: 30px">
                        <input type="submit" value="Search" style="width: 100px; height: 30px">
                    </form>
                </td>
            </tr>
        </tbody>
    </table>
    <table width="100%">
        <tbody>
        <tr>
            <th>
               <h2>Referrals</h2>
            </th>
            <th>
                <h2>Records</h2>
            </th>
            <th>
                <h2>Messages</h2>
            </th>
            <th>
                <h2>Rx</h2>
            </th>
            <th>
                <h2>Meds Auth</h2>
            </th>
            <th>
                <h2>Phone Stats</h2>
            </th>
            <th>
                <h2>Rx Stats</h2>
            </th>
        </tr>
            <tr>
                <td valign="top" align="center">
                    <table cellspacing="15px">
                        <tbody>
                            <tr>
                                <td>
                                    <a href="../Reports/FrontPageReport.php?querey=SOMETHING" class="notification" >
                                        <span>Messages</span>
                                        <span class="badge"><?php echo $ASAP?></span>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="../Reports/FrontPageReport.php?query=temp" class="notification">
                                        <span>New Patient</span>
                                        <span class="badge"><?php echo $newPatient?></span>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="../Reports/FrontPageReport.php?query=SELECT * FROM Referrals.Referrals WHERE LastSent IS NULL" class="notification">
                                        <span>New</span>
                                        <span class="badge"><?php echo $newReferral?></span>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="../Reports/FrontPageReport.php?query=SELECT * FROM Referrals.Referrals WHERE Status='4'" class="notification">
                                        <span>Pending Appointment from Specialist</span>
                                        <span class="badge"><?php echo $pendingApt?></span>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="../Reports/FrontPageReport.php?query=SELECT * FROM Referrals.Referrals WHERE Status='1'" class="notification">
                                        <span>Pending Soap</span>
                                        <span class="badge"><?php echo $pendingSoap ?></span>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="../Reports/FrontPageReport.php?query=SELECT * FROM Referrals.Referrals WHERE Status='0'" class="notification">
                                        <span>Pending Demo</span>
                                        <span class="badge"><?php echo $pendingDemo ?></span>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="../Reports/FrontPageReport.php?query=SELECT * FROM Referrals.Referrals WHERE Authorization='4'" class="notification">
                                        <span>Pending Authorization</span>
                                        <span class="badge"><?php echo $pendingAuth ?></span>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td valign="top" align="center">
                    <table cellspacing="15px">
                        <tbody>
                        <tr>
                            <td>
                                <a href="#" class="notification">
                                    <span>Waiting for CK</span>
                                    <span class="badge"><?php echo $waitingCK ?></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="#" class="notification">
                                    <span>Need Approval</span>
                                    <span class="badge"><?php echo $needApproval?></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="#" class="notification">
                                    <span>Approved</span>
                                    <span class="badge"><?php echo $approvedRecord?></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="#" class="notification">
                                    <span>Sent</span>
                                    <span class="badge"><?php echo $sentRecord?></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="#" class="notification">
                                    <span>See Me</span>
                                    <span class="badge"><?php echo $seeMe?></span>
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
                <td valign="top" align="center">
                    <table cellspacing="15px">
                        <tbody>
                        <tr>
                            <td>
                                <a href="#" class="notification">
                                    <span>Provider</span>
                                    <span class="badge"><?php echo $provider?></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="#" class="notification">
                                    <span>Reception</span>
                                    <span class="badge"><?php echo $reception?></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="#" class="notification">
                                    <span>MA</span>
                                    <span class="badge"><?php echo $ma?></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="#" class="notification">
                                    <span>Referrals</span>
                                    <span class="badge"><?php echo $referrals?></span>
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
                <td valign="top" align="center">
                    <table cellspacing="15px">
                        <tbody>
                        <tr>
                            <td>
                                <a href="#" class="notification">
                                    <span>Rx to MA</span>
                                    <span class="badge"><?php echo $rxToMa ?></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="#" class="notification">
                                    <span>Rx to Reception</span>
                                    <span class="badge"><?php echo $rxToReception?></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="#" class="notification">
                                    <span>Pharmacy Called</span>
                                    <span class="badge"><?php echo $pharmacyCalled?></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="#" class="notification">
                                    <span>Rx to eScribe</span>
                                    <span class="badge"><?php echo $rxToEscribe?></span>
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
                <td valign="top" align="center">
                    <table cellspacing="15px">
                        <tbody>
                        <tr>
                            <td>
                                <a href="#" class="notification">
                                    <span >New</span>
                                    <span class="badge"><?php echo $newMed?></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="#" class="notification">
                                    <span>Pending</span>
                                    <span class="badge"><?php echo $pendingMed?></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="#" class="notification">
                                    <span>Denial</span>
                                    <span class="badge"><?php echo $denialMed?></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="#" class="notification">
                                    <span>Approved</span>
                                    <span class="badge"><?php echo $approvedMed?></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="#" class="notification">
                                    <span>Other</span>
                                    <span class="badge"><?php echo $otherMed?></span>
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
                <td valign="top" align="center">
                    <table cellspacing="15px">
                        <tbody>
                        <?php echo $phoneStats?>
                        </tbody>
                    </table>
                </td>
                <td valign="top" align="center">
                    <table cellspacing="15px">
                        <tbody>
                        <?php echo $RxStats?>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>