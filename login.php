
<?php
    session_start();
    include ("AuditLog.php");
//echo phpinfo();
    $msg = '';
    $_SESSION['username'] = 'siminternal';
    $_SESSION['password'] = 'Watergate2015';
    $user = $_GET['login'];
    $con = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
    if($con->connect_error){
        $_SESSION['loggedIn'] = false;
    } else {

//        echo 'Welcome ';
        $query = 'SELECT * FROM Referrals.Users WHERE UserName=\'' . $user . '\'';
        $result = $con->query($query);
        $row = $result->fetch_row();
        if ($row == NULL){
            header('location:/index.html');
        } else {
            if ($_GET['password'] == $row[6]){
                $_SESSION['userID'] = $row[0];
                $_SESSION['user'] = $row[3];
                $_SESSION['name'] = $row[4] . " " . $row[5];
                $temp = $row[2];
                $query = 'SELECT *FROM Referrals.UserGroups WHERE ID=' . $row[1];
                $result = $con->query($query);
                $row = $result->fetch_row();
                $_SESSION['valid'] = true;
                $_SESSION['loggedIn'] = true;
                $_SESSION['group']  = $row[1];
//                echo $temp;
                $query = 'SELECT *FROM Referrals.UserRights WHERE ID=' . $temp;
                $result = $con->query($query);
                $row = $result->fetch_row();

                $_SESSION['rights'] = $row[1];

                $string = $_SESSION['name'] . " Logged in";
                echo '1';

                $audit = new AuditLog;
                $audit->SetChange($string);

//                echo var_dump($_SESSION);

                $toSend = 'location:/main.php';
                header($toSend);

            } else {
                header('location:/index.html');
            }
        }
    }
