<?php
session_start();

$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
$query = "SELECT * FROM Referrals.Users WHERE UserName='" . $_SESSION['user'] . "'";
$result = $conReferrals->query($query);
$row = $result->fetch_row();

$query = "SELECT * FROM Referrals.Specialty WHERE ID=" . $_GET['specality'];
$result = $conReferrals->query($query);
$row2 = $result->fetch_row();

?>


<html>
    <head>
        <link rel="stylesheet" href="../Menu/menu.css">
        <style>

        </style>
    </head>

    <body>
    <table>
            <tbody>
                <tr>
                    <table style="border-bottom: black 2px solid">
                        <tbody>
                            <tr>
                                <td>
                                    <img src="Images/sim.jpg" style="width: 125px;height: 125px">
                                </td>
                                <td>
                                    <img src="Images/ica.jpg" style="width: 125px;height: 125px">
                                </td>
                                <td>
                                    <img src="Images/ical.jpg" style="width: 125px;height: 125px">
                                </td>
                                <td>
                                    <img src="Images/ican.jpg" style="width: 125px;height: 125px">
                                </td>
                                <td>
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <span style="font-size: 10px; font-family: arial">
                                                        <strong>
                                                            ABE HARDOON, M.D.
                                                        </strong>
                                                    </span>
                                                    <br/>
                                                    <span style="font-size: 8px; font-family: arial; font-style: italic">
                                                        Board Certified-Internal Medicine
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span style="font-size: 10px; font-family: arial">
                                                        <strong>
                                                            SCOTT HARDOON, M.D.
                                                        </strong>
                                                    </span>
                                                    <br/>
                                                    <span style="font-size: 8px; font-family: arial; font-style: italic">
                                                        Board Certified-Internal Medicine
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span style="font-size: 10px; font-family: arial">
                                                        <strong>
                                                            EDWIN CHAN, M.D.
                                                        </strong>
                                                    </span>
                                                    <br/>
                                                    <span style="font-size: 8px; font-family: arial; font-style: italic">
                                                        Board Certified Family Medicine
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span style="font-size: 10px; font-family: arial">
                                                        <strong>
                                                            GARY HARDOON, M.D.
                                                        </strong>
                                                    </span>
                                                    <br/>
                                                    <span style="font-size: 8px; font-family: arial; font-style: italic">
                                                        Board Certified-Internal Medicine
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span style="font-size: 10px; font-family: arial">
                                                        <strong>
                                                            AMI BHATT, M.D.
                                                        </strong>
                                                    </span>
                                                    <br/>
                                                    <span style="font-size: 8px; font-family: arial; font-style: italic">
                                                        Board Certified Family Medicine
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span style="font-size: 10px; font-family: arial">
                                                       <strong>
                                                           SANDRA KEEFE, M.D.
                                                       </strong>
                                                   </span>
                                                    <br/>
                                                    <span style="font-size: 8px; font-family: arial; font-style: italic">
                                                        Board Certified Family Medicine
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span style="font-size: 10px; font-family: arial">
                                                        <strong>
                                                            JENNIFER SCHNEIDER, ARNP
                                                        </strong>
                                                    </span>
                                                    <br/>
                                                    <span style="font-size: 8px; font-family: arial; font-style: italic">
                                                        Advanced Registered Nurse Practitioner
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span style="font-size: 10px; font-family: arial">
                                                        <strong>
                                                            SHANNON SPREITZER, ARNP
                                                        </strong>
                                                    </span>
                                                    <br/>
                                                    <span style="font-size: 8px; font-family: arial; font-style: italic">
                                                        Advanced Registered Nurse Practitioner
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span style="font-size: 10px; font-family: arial">
                                                        <strong>
                                                            NIKKA COHAN, ARNP
                                                        </strong>
                                                    </span>
                                                    <br/>
                                                    <span style="font-size: 8px; font-family: arial; font-style: italic">
                                                        Advanced Registered Nurse Practitioner
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span style="font-size: 10px; font-family: arial">
                                                        <strong>
                                                            ANGELA ARMELLINI, ARNP
                                                        </strong>
                                                    </span>
                                                    <br/>
                                                    <span style="font-size: 8px; font-family: arial; font-style: italic">
                                                        Advanced Registered Nurse Practitioner
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </tr>
            </tbody>
        </table>
        <span style="font-size: 16px; font-family: Arial">
            <br/>
            <br/>
            <strong><?php echo date("Y/m/d h:ia")?></strong><br/>
            <br/>
            <br/>
            <strong><?php echo $_GET['patient']?></strong><br/>
            <br/>
            <br/>
            <br/>
            A referral has been entered on your behalf by <strong><?php echo $row[4] . " " . substr($row[5], 0, 1) . ".";?></strong> on <strong><?php echo date("Y/m/d")?></strong>.<br/>
            <br/>
            If you don’t hear from a /an <strong><?php echo $row2[1]?></strong> within the next 7 business days, please contact our referral department at 321-259-9500 x6.  At that time, we will be able to provide you with the status and the specific doctor you have been referred to.<br/>
            <br/>
            Of course, if you should need anything else in the meantime, please feel free to contact our office.<br/>
            <br/>
            <br/>
            <br/>
            Thank You,<br/>
            <br/>
            <br/>
                        <br/>
Suntree Internal Medicine<br/>
Referrals Department<br/>
321-259-9500 x6<br/>
<br/>
        </span>
        <div style="position: absolute; bottom: 10px">
        <table style="font-size: 12px">
            <tbody>
            <tr>
                <td width="10%"></td>
                <td width="60%">6619 N. Wickham Road</td>
                <td width="15%">Office: 321-259-9500</td>
                <td width="5%"></td>
            </tr>
            <tr>
                <td></td>
                <td>Melbourne, FL 32940</td>
                <td>Fax:  321-253-1777</td>
                <td></td>
            </tr>
            </tbody>
        </table>
        </div>
    </>



</html>