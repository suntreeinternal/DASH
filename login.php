
<?php
    session_start();
    $msg = '';

    $user = $_GET['login'];
            $con = new mysqli('localhost', $user, $_GET['password'], 'Referrals');
            if($con->connect_error){
                header('location:/index.html');
                $_SESSION['loggedIn'] = false;
            } else {

                echo 'Welcome ';
                $query = 'SELECT * FROM Referrals.Users WHERE UserName=\'' . $user . '\'';
                $result = $con->query($query);
                $row = $result->fetch_row();
                $_SESSION['userID'] = $row[0];
                $_SESSION['name'] = $row[4] . " " . $row[5];
                $query = 'SELECT *FROM Referrals.UserGroups WHERE ID=' . $row[1];
                $result = $con->query($query);
                $row = $result->fetch_row();
                echo $row[1];
                $_SESSION['valid'] = true;
                $_SESSION['username'] = $user;
                $_SESSION['password'] = $_GET['password'];
                $_SESSION['loggedIn'] = true;
                $_SESSION['group']  = $row[1];

                $toSend = 'location:/main.php';
                header($toSend);

            }
