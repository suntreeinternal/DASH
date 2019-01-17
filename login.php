
<?php
    session_start();
    $msg = '';
    $_SESSION['username'] = 'DashLoginUser';
    $_SESSION['password'] = 'Cr4sietd!';
    $user = $_GET['login'];
    $con = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
    if($con->connect_error){
        $_SESSION['loggedIn'] = false;
    } else {

        echo 'Welcome ';
        $query = 'SELECT * FROM Referrals.Users WHERE UserName=\'' . $user . '\'';
        $result = $con->query($query);
        $row = $result->fetch_row();
        if ($row == NULL){
            header('location:/index.html');
        } else {
            if ($_GET['password'] == $row[6]){
                $_SESSION['userID'] = $row[0];
                $_SESSION['name'] = $row[4] . " " . $row[5];
                $query = 'SELECT *FROM Referrals.UserGroups WHERE ID=' . $row[1];
                $result = $con->query($query);
                $row = $result->fetch_row();
                $_SESSION['valid'] = true;
                $_SESSION['loggedIn'] = true;
                $_SESSION['group']  = $row[1];
                $query = "INSERT INTO Referrals.ChangeLog (UserID, ChangeSummery, DateTime) VALUES ('" . $_SESSION['userID'] . "', ' " . $_SESSION['name'] . " Logged in', '"
                    . date("Y-m-d h:i:sa") . "')";
                $result = $con->query($query);
                echo var_dump($_SESSION);
                $toSend = 'location:/main.php';
                header($toSend);

            } else {
                header('location:/index.html');
            }
        }
    }
