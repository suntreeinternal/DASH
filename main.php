<?php
    session_start();
    if (sizeof($_SESSION) == 0){
        header('location:../index.html');
    }
    if($_SESSION['loggedIn'] == false){
        header('location=../index.html');
    }

    $totalMa = 0;
    $totalReception = 0;
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

    $query = 'SELECT COUNT(*) FROM MessageAboutPatient WHERE AlertToGroup=1';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $reception = $row[0];

    $query = 'SELECT COUNT(*) FROM PatientPhoneMessages WHERE AlertToGroup=1';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $reception += $row[0];
    $totalReception += $reception;

    $query = 'SELECT COUNT(*) FROM MessageAboutPatient WHERE AlertToGroup=0';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $ma = $row[0];
    $query = 'SELECT COUNT(*) FROM PatientPhoneMessages WHERE AlertToGroup=0';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $ma += $row[0];
    $totalMa += $ma;

    $query = 'SELECT COUNT(*) FROM MessageAboutPatient WHERE AlertToGroup=2';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $referrals = $row[0];
    $query = 'SELECT COUNT(*) FROM PatientPhoneMessages WHERE AlertToGroup=2';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $referrals += $row[0];

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

    $query = 'SELECT COUNT(*) FROM Referrals.RecordRequest WHERE Status=0';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $waitingCK = $row[0];

    $query = 'SELECT COUNT(*) FROM Referrals.RecordRequest WHERE Status=1';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $needApproval = $row[0];

    $query = 'SELECT COUNT(*) FROM Referrals.RecordRequest WHERE Status=2';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $approvedRecord = $row[0];

    $query = 'SELECT COUNT(*) FROM Referrals.RecordRequest WHERE Status=3';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $sentRecord = $row[0];

    $query = 'SELECT COUNT(*) FROM Referrals.RecordRequest WHERE Status=4';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $seeMe = $row[0];

    $query = 'SELECT COUNT(*) FROM Referrals.Rx WHERE Status=1';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $rxToMa = $row[0];
    $totalMa += $row[0];

    $query = 'SELECT COUNT(*) FROM Referrals.Rx WHERE Status=2';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $rxToReception = $row[0];
    $totalReception += $row[0];

    $query = 'SELECT COUNT(*) FROM Referrals.Rx WHERE Status=5';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $pharmacyCalled = $row[0];

    $query = 'SELECT COUNT(*) FROM Referrals.Rx WHERE Status=4';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $rxToEscribe = $row[0];


    $query = 'SELECT COUNT(*) FROM Referrals.MedsAuth WHERE Status=0';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $newMed = $row[0];

    $query = 'SELECT COUNT(*) FROM Referrals.MedsAuth WHERE Status=1';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $pendingMed = $row[0];

    $query = 'SELECT COUNT(*) FROM Referrals.MedsAuth WHERE Status=2';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $denialMed = $row[0];

    $query = 'SELECT COUNT(*) FROM Referrals.MedsAuth WHERE Status=4';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $approvedMed = $row[0];

    $query = 'SELECT COUNT(*) FROM Referrals.MedsAuth WHERE Status=3';
    $result = $con->query($query);
    $row = $result->fetch_row();
    $otherMed = $row[0];




    $phoneStats = '';
    $query = 'SELECT * FROM Referrals.Provider WHERE Active=1';
    $result = $con->query($query);
    while ($row = $result->fetch_row()){
        $val = $row[0]+4;
        $query = 'SELECT COUNT(*) FROM Referrals.PatientPhoneMessages WHERE AlertToGroup=' . $val;
        $resultCount = $con->query($query);
        $valCount = $resultCount->fetch_row();
        $query = 'SELECT * FROM Referrals.PatientPhoneMessages WHERE AlertToGroup=' .$val;
        $phoneStats .= '<tr><td><a style="background-color:'. $row[4] . ' ; color:' . $row[5] . '" href="../Reports/FrontPage/PhoneReport.php?query=' . $query . '" class="notification"><span>' . $row[2] . '</span><span class="badge">' . $valCount[0] . '</span></a></td></tr>';
    }

    $RxStats = '';
    $query = 'SELECT * FROM Referrals.Provider WHERE Active=1';
    $result = $con->query($query);
    while ($row = $result->fetch_row()){
        $val = $row[0]+4;
        $query = 'SELECT COUNT(*) FROM Referrals.Rx WHERE ProviderID=' . $row[0] . ' AND Status=2' ;
        $resultCount = $con->query($query);
        $valCount = $resultCount->fetch_row();
        $query = 'SELECT * FROM Referrals.Rx WHERE ProviderID=' . $row[0] . ' AND Status=2';
        $RxStats .= '<tr><td><a style="background-color:'. $row[4] . '; color:' . $row[5] . '" href="../Reports/FrontPage/Rx.php?query=' . $query . '" class="notification"><span>' . $row[2] . '</span><span class="badge">' . $valCount[0] . '</span></a></td></tr>';
    }

    //TODO Rethink the way that messages work.

?>
<html>
    <head>
        <link rel="stylesheet" href="Menu/menu.css">
        <title>DASH: <?php echo $_SESSION['name']?></title>
        <style>

            .ma {
                border-radius:50%;
                background-color: #adf052;
                color: #000000;
                text-decoration: none;
                display: inline-block;
                width: 200px;
                height: 200px;
                position: relative;
                text-align: center;
                vertical-align: middle;
            }

            .ma:hover {
                background: #baff54;
            }


            .reception {
                background-color: #2f4f4f;
                color: #FFFFFF;
                text-align: center;
                vertical-align: middle;
                text-decoration: none;
                display: inline-block;
                border-radius: 50%;
                width: 200px;
                height: 200px;
            }

            .reception:hover {
                background: #507c7c;
            }

        .notification {
            background-color: #2c8951;
            color: #001b00;
            text-decoration: none;
            padding: 15px 26px;
            position: relative;
            display: inline-block;
            border-radius: 2px;
            width: 120px;
        }

        .notification:hover {
            background: #2d985f;
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

    <body style="background:#C0C0C0;">
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
                <td align="right">
                    <?php echo $_SESSION['group']?>
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
                <h2>Message Stats</h2>
            </th>
            <th>
                <h2>Rx Stats</h2>
            </th>
        </tr>
            <tr>
                <td rowspan="2" valign="top" align="center">
                    <table cellspacing="15px">
                        <tbody>
                            <tr>
                                <td>
                                    <a href="../Reports/FrontPage/Report.php?query=temp" class="notification">
                                        <span>New Patient</span>
                                        <span class="badge"><?php echo $newPatient?></span>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="../Reports/FrontPage/Report.php?query=SELECT * FROM Referrals.Referrals WHERE LastSent IS NULL" class="notification">
                                        <span>New</span>
                                        <span class="badge"><?php echo $newReferral?></span>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="../Reports/FrontPage/Report.php?query=SELECT * FROM Referrals.Referrals WHERE Status='4'" class="notification">
                                        <span>Pending Appointment from Specialist</span>
                                        <span class="badge"><?php echo $pendingApt?></span>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="../Reports/FrontPage/Report.php?query=SELECT * FROM Referrals.Referrals WHERE Status='1'" class="notification">
                                        <span>Pending Soap</span>
                                        <span class="badge"><?php echo $pendingSoap ?></span>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="../Reports/FrontPage/Report.php?query=SELECT * FROM Referrals.Referrals WHERE Status='0'" class="notification">
                                        <span>Pending Demo</span>
                                        <span class="badge"><?php echo $pendingDemo ?></span>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="../Reports/FrontPage/Report.php?query=SELECT * FROM Referrals.Referrals WHERE Authorization='4'" class="notification">
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
                                <a href="../Reports/FrontPage/Records.php?query=SELECT * FROM Referrals.RecordRequest WHERE Status='0'" class="notification">
                                    <span>Waiting for CK</span>
                                    <span class="badge"><?php echo $waitingCK ?></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="../Reports/FrontPage/Records.php?query=SELECT * FROM Referrals.RecordRequest WHERE Status='1'" class="notification">
                                    <span>Need Approval</span>
                                    <span class="badge"><?php echo $needApproval?></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="../Reports/FrontPage/Records.php?query=SELECT * FROM Referrals.RecordRequest WHERE Status='2'" class="notification">
                                    <span>Approved</span>
                                    <span class="badge"><?php echo $approvedRecord?></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="../Reports/FrontPage/Records.php?query=SELECT * FROM Referrals.RecordRequest WHERE Status='3'" class="notification">
                                    <span>Sent</span>
                                    <span class="badge"><?php echo $sentRecord?></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="../Reports/FrontPage/Records.php?query=SELECT * FROM Referrals.RecordRequest WHERE Status='4'" class="notification">
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
                                <a href="../Reports/FrontPage/Messages.php?query=SELECT * FROM Referrals.PatientData WHERE U='4'" class="notification">
                                    <span>Reception</span>
                                    <span class="badge"><?php echo $reception?></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="../Reports/FrontPage/Records.php?query=SELECT * FROM Referrals.RecordRequest WHERE Status='4'" class="notification">
                                    <span>MA</span>
                                    <span class="badge"><?php echo $ma?></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="../Reports/FrontPage/Records.php?query=SELECT * FROM Referrals.RecordRequest WHERE Status='4'" class="notification">
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
                                <a href="../Reports/FrontPage/Rx.php?query=SELECT * FROM Referrals.Rx WHERE Status='1'" class="notification">
                                    <span>Rx to MA</span>
                                    <span class="badge"><?php echo $rxToMa ?></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="../Reports/FrontPage/Rx.php?query=SELECT * FROM Referrals.Rx WHERE Status='3'" class="notification">
                                    <span>Rx to Reception</span>
                                    <span class="badge"><?php echo $rxToReception?></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="../Reports/FrontPage/Rx.php?query=SELECT * FROM Referrals.Rx WHERE Status='5'" class="notification">
                                    <span>Pharmacy Called</span>
                                    <span class="badge"><?php echo $pharmacyCalled?></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="../Reports/FrontPage/Rx.php?query=SELECT * FROM Referrals.Rx WHERE Status='4'" class="notification">
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
                                <a href="../Reports/FrontPage/MedsAuth.php?query=SELECT * FROM Referrals.MedsAuth WHERE Status='0'" class="notification">
                                    <span >New</span>
                                    <span class="badge"><?php echo $newMed?></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="../Reports/FrontPage/MedsAuth.php?query=SELECT * FROM Referrals.MedsAuth WHERE Status='1'" class="notification">
                                    <span>Pending</span>
                                    <span class="badge"><?php echo $pendingMed?></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="../Reports/FrontPage/MedsAuth.php?query=SELECT * FROM Referrals.MedsAuth WHERE Status='2'" class="notification">
                                    <span>Denial</span>
                                    <span class="badge"><?php echo $denialMed?></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="../Reports/FrontPage/MedsAuth.php?query=SELECT * FROM Referrals.MedsAuth WHERE Status='4'" class="notification">
                                    <span>Approved</span>
                                    <span class="badge"><?php echo $approvedMed?></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="../Reports/FrontPage/MedsAuth.php?query=SELECT * FROM Referrals.MedsAuth WHERE Status='3'" class="notification">
                                    <span>Other</span>
                                    <span class="badge"><?php echo $otherMed?></span>
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
                <td rowspan="2" valign="top" align="center">
                    <table cellspacing="15px">
                        <tbody>
                        <?php echo $phoneStats?>
                        </tbody>
                    </table>
                </td>
                <td rowspan="2" valign="top" align="center">
                    <table cellspacing="15px">
                        <tbody>
                        <?php echo $RxStats?>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4" valign="top" align="center">
                    <table cellspacing="20" style="font-size: 30px;">
                        <tbody>
                            <tr>
                                <td>
                                    <a href="../Reports/FrontPage/MaReport.php" class="ma">
                                       <br/> MA<br/><br/><?php echo $totalMa?>
                                    </a>
                                </td>
                                <td width="50px"></td>
                                <td>
                                    <a href="../Reports/FrontPage/ReceptionReport.php" class="reception">
                                        <br>Reception<br/><br/><?php echo $totalReception?>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>