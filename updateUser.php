<?php
session_start();

/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 11/2/2018
 * Time: 11:56 AM
 */

//TODO update user to all specifications
//TODO get current user data
//TODO Create change log.
$group = 0;
$access= 0;

$con = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
if($con->connect_error){
    header('location:/index.html');
} else {
    $query = 'SELECT * FROM Referrals.Users WHERE UserName="' . $_GET['user'] .'"';
}
$result = $con->query($query);
$row = $result->fetch_row();
$group = $row[1];
$access = $row[2];
?>

<!DOCTYPE html>
<html>
<head>
<!--    <link rel="stylesheet" href="updateForm.css">-->
    <title>DASH</title>
    <style>
        .login-page {
            width: 600px;
            padding: 8% 0 0;
            margin: auto;
        }
        .form {
            position: relative;
            z-index: 1;
            background: #FFFFFF;
            max-width: 600px;
            margin: 0 auto 100px;
            padding: 45px;
            text-align: center;
            box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
        }
        .form input {
            font-family: "Roboto", sans-serif;
            outline: 0;
            background: #f2f2f2;
            width: 100%;
            border: 0;
            margin: 0 0 15px;
            padding: 15px;
            box-sizing: border-box;
            font-size: 14px;
        }
        .form button {
            font-family: "Roboto", sans-serif;
            text-transform: uppercase;
            outline: 0;
            background: #4CAF50;
            width: 100%;
            border: 0;
            padding: 15px;
            color: #FFFFFF;
            font-size: 14px;
            -webkit-transition: all 0.3 ease;
            transition: all 0.3 ease;
            cursor: pointer;
        }
        .form button:hover,.form button:active,.form button:focus {
            background: #43A047;
        }
        .form .message {
            margin: 15px 0 0;
            color: #b3b3b3;
            font-size: 12px;
        }
        .form .message a {
            color: #4CAF50;
            text-decoration: none;
        }
        .form .register-form {
            display: none;
        }
        .container {
            position: relative;
            z-index: 1;
            max-width: 600px;
            margin: 0 auto;
        }
        .container:before, .container:after {
            content: "";
            display: block;
            clear: both;
        }
        .container .info {
            margin: 50px auto;
            text-align: center;
        }
        .container .info h1 {
            margin: 0 0 15px;
            padding: 0;
            font-size: 36px;
            font-weight: 300;
            color: #1a1a1a;
        }
        .container .info span {
            color: #4d4d4d;
            font-size: 12px;
        }
        .container .info span a {
            color: #000000;
            text-decoration: none;
        }
        .container .info span .fa {
            color: #EF3B3A;
        }
        body {
            background: #76b852; /* fallback for old browsers */
            background: -webkit-linear-gradient(right, #76b852, #8DC26F);
            background: -moz-linear-gradient(right, #76b852, #8DC26F);
            background: -o-linear-gradient(right, #76b852, #8DC26F);
            background: linear-gradient(to left, #76b852, #8DC26F);
            font-family: "Roboto", sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

    </style>
</head>
<div class="login-page">
    <div class="form">
        <form action="" method="get" class="login-form">
            <table width="100%">
                <tbody>
                <tr>
                    <td width="50%">
                        First Name: <?php echo $_GET['first']?>
                    </td>
                    <td width="50%">
                        Last Name: <?php echo $_GET['last']?>
                    </td>
                </tr>
                <tr>
                    <td>
                        User Name: <?php echo $_GET['user']?>
                    </td>
                    <td>
                        <input type="password" placeholder="Password" name="password">

                    </td>
                <tr>
                    <td>
                        <h3>User Rights</h3>
                    </td>
                    <td>
                        <h3>User Group</h3>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php
                            if ($group == 1){
                                echo 'Admin <input checked type="radio" name="group" value="adminGroup">';
                            } else {
                                echo 'Admin <input type="radio" name="group" value="adminGroup">';
                            }
                            ?>
                    </td>
                    <td>
                        <?php
                            if ($access == 1){
                                echo 'Super Admin <input checked type="radio" name="rights" value="superAdminRights">';
                            } else {
                                echo 'Super Admin <input type="radio" name="rights" value="superAdminRights">';

                            }
                            ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php
                            if ($group == 2) {
                                echo 'Reception <input checked type="radio" name="group" value="receptionGroup">';
                            } else {
                                echo 'Reception <input type="radio" name="group" value="receptionGroup">';
                            }
                            ?>
                    </td>
                    <td>
                        <?php
                        if ($access == 2) {
                            echo ' Admin <input checked type="radio" name="rights" value="adminRights">';
                        } else {
                            echo ' Admin <input type="radio" name="rights" value="adminRights">';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php
                        if ($group == 3) {
                            echo 'Provider <input checked type="radio" name="group" value="providerGroup">';
                        } else {
                            echo 'Provider <input type="radio" name="group" value="providerGroup">';
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                            if ($access == 3) {
                                echo 'Records <input checked type="radio" name="rights" value="recordRights">';
                            } else {
                                echo 'Records <input type="radio" name="rights" value="recordRights">';
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php
                            if ($group == 4) {
                                echo 'Referrals<input checked type="radio" name="group" value="referralsGroup">';
                            } else {
                                echo 'Referrals<input type="radio" name="group" value="referralsGroup">';
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                            if ($access == 4) {
                                echo 'Read/Write <input checked type="radio" name="rights" value="rwRights">';
                            } else {
                                echo 'Read/Write <input type="radio" name="rights" value="rwRights">';
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php
                            if ($group == 5) {
                                echo 'MA <input checked type="radio" name="group" value="maGroup">';
                            } else {
                                echo 'MA <input type="radio" name="group" value="maGroup">';
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                            if ($access == 5) {
                                echo 'Read Only <input checked type="radio" name="rights" value="readRights">';
                            } else {
                                echo 'Read Only <input type="radio" name="rights" value="readRights">';
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php
                            if ($group == 6) {
                                echo 'Inactive <input checked type="radio" name="group" value="inactiveGroup">';
                            } else {
                                echo 'Inactive <input type="radio" name="group" value="inactiveGroup">';
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($access == 6) {
                            echo 'Inactive <input checked type="radio" name="rights" value="inactiveAccess">';
                        } else {
                            echo 'Inactive <input type="radio" name="rights" value="inactiveAccess">';
                        }
                        ?>
                    </td>
                </tr>
                </tbody>
            </table>
            <button>Submit</button>
        </form>
    </div>
</div>
</html>