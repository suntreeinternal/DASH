<?php
session_start();

if ($_SESSION['group'] == 'Admin'){
    echo" <div id='cssmenu'>
    <ul>
        <li><a href=\"..\main.php\"><span>Home</span></a></li>
        <li><a href='..\Admin\Admin.php'><span>Admin Maintenance</span></a></li>
        <li><a href='..\Reports\ReferralsReport.php'><span>Referral Reports</span></a></li>
        <li><a href='..\Reports\RecordReport.php'><span>Record Reports</span></a></li>
        <li><a href='..\Reports\PhoneReport.php'><span>Phone Reports</span></a></li>
        <li><a href='..\Reports\RxReport.php'><span>Rx Reports</span></a></li>
        <li><a href='..\Admin\EmployeeReport.php'><span>Employee Report</span></a></li>  
        <li><a href='..\Logout.php'><span>Logout</span></a> </li>
        <li style='float: right'><a><span>" . $_SESSION['name'] . "</span></a></li>

    </ul>
</div>";
} else {
    echo" <div id='cssmenu'>
    <ul>
        <li><a href=\"..\main.php\"><span>Home</span></a></li>
        <li><a href='..\Admin\Admin.php'><span>Admin Maintenance</span></a></li>
        <li><a href='..\Reports\ReferralsReport.php'><span>Referral Reports</span></a></li>
        <li><a href='..\Reports\RecordReport.php'><span>Record Reports</span></a></li>
        <li><a href='..\Reports\PhoneReport.php'><span>Phone Reports</span></a></li>
        <li><a href='..\Reports\RxReport.php'><span>Rx Reports</span></a></li>
        <li><a href='..\Logout.php'><span>Logout</span></a> </li>
        <li style='background-color: #E74C3C'><span>TEST</span></li>
    </ul>
</div>";
}

