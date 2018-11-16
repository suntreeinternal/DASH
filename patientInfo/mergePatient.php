<?php
    session_start();

    /**
     * Created by PhpStorm.
     * User: SimInternal
     * Date: 11/2/2018
     * Time: 11:56 AM
     */
    $last = $_GET['last'];
    $date = $_GET['date'];
    //connection for mssql
    $con = mssql_connect('sunserver', 'siminternal', 'Watergate2015');
    //connection for sqli
    $conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');

    if($con->connect_error){
        header('location:/index.html');
    }

    if (!mssql_select_db('sw_charts', $con)) {
        die('Unable to select database! ');
    }


    /**
     * check to see if the patient exists as a temp patient
     */
    $query = 'SELECT * FROM dbo.Gen_Demo WHERE last_name=\''. $last .'\' AND birthdate=\''. $date . '\'';
    $result = mssql_query($query);
    $row = mssql_fetch_array($result);
    $SoapName = $row[2] . " " . $row[1];
    $swID = $row[0];
    $swDob = $row['birthdate'];


    $query = 'SELECT * FROM Referrals.TempPatient WHERE LastName=\''. $last . '\' AND BirthDate=\''. $date . '\'';
    $result = $conReferrals->query($query);
    $row = $result->fetch_row();
    $tempId = $row[0];
    $tempName = $row[1] . " " . $row[2];
    $tempDob = $row[3];
    $conReferrals->close();



?>

<!DOCTYPE html>
<html>
<head>
    <!--    <link rel="stylesheet" href="updateForm.css">-->
    <title>DASH</title>
    <style>
        .login-page {
            width: 700px;
            padding: 8% 0 0;
            margin: auto;
        }
        .form {
            position: relative;
            z-index: 1;
            background: #FFFFFF;
            max-width: 700px;
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
        <form action="/patientInfo/mergeAndDelete.php" method="get" class="login-form">
            <table width="100%">
                <tbody>
                <tr>
                    <td width="50%">
                        Soap Ware Patient Name: <?php echo $SoapName?>
                    </td>
                    <td width="50%">
                        Soap Ware DOB: <?php echo date("m-d-Y", strtotime($swDob))?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Dash Patient Name: <?php echo $tempName?>
                    </td>
                    <td>
                        Dash Patient DOB: <?php echo date("m-d-Y", strtotime($tempDob))?>
                        <input type="hidden" name="id" value="<?php echo $tempId?>">
                        <input type="hidden" name="sw" value="<?php echo $swID?>">
                    </td>
                </tr>
                </tbody>
            </table>
            <button>Are you sure you want to merge these two patients</button>
        </form>
    </div>
</div>
</html>