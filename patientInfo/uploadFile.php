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
            width: 400px;
            padding: 8% 0 0;
            margin: auto;
        }
        .form {
            position: relative;
            z-index: 1;
            background: #FFFFFF;
            max-width: 400px;
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
        .form .message a {
            color: #4CAF50;
            text-decoration: none;
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
        <form action="placeFile.php" method="post" enctype="multipart/form-data" class="login-form">
            Select attachment for patient: <input type="file" name="fileToUpload" id="fileToUpload">
            <button>Submit</button>
        </form>
    </div>
</div>
</html>