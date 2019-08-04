<?php

//echo 'asdf';


session_start();


//echo 'asdf';

$previousMessages = "";
$con = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
if($con->connect_error){
    header('location:/index.html');
}

$query = 'SELECT * FROM Referrals.PatientPhoneMessages WHERE id=' . $_GET['parent'] . ' OR ParrentMessage=' . $_GET['parent'] . ' ORDER BY id ASC' ;
$result = $con->query($query);
while ($row = $result->fetch_row()) {
    $messageGroup = $row[5];
    switch ($messageGroup) {
        case 'Admin':
            $previousMessages .= "<tr style=\"background-color: #0066ff\">";
            break;

        case 'Reception':
            $previousMessages .= "<tr style=\"background-color: #F4D03F\">";
            break;

        case 'Provider':
            $previousMessages .= "<tr style=\"background-color: #E74C3C\">";
            break;

        case 'Referrals':
            $previousMessages .= "<tr style=\"background-color: #BB8FCE\">";
            break;

        case 'MA':
            $previousMessages .= "<tr style=\"background-color: #45B39D\">";
            break;
    }


    $date = date_create($row[3]);

    $previousMessages .= "
                                <td style=\"border-radius: 7px\" colspan='2'>
                                    " . $row[2] . " " . date_format($date, 'm/d/Y H:i:s') . " <br/> <br/>" . $row[4] . "
                                </td>
                            </tr>
                        ";
}


$destination = "";
$query = 'SELECT * FROM Referrals.Provider WHERE Active=1';
$result = $con->query($query);


while ($row = $result->fetch_row()){
    $val = 4+$row[0];
    $valMA = 104+$row[0];
    $destination .= "<input type='radio' name='dest' value='" . $val . "'>" . $row[2] . "</br>";
    $destinationMA .= "<input type='radio' name='dest' value='" . $valMA . "'>" . $row[2] . " ma </br>";

}

?>

<!DOCTYPE html>
<html>
<head>
    <!--    <link rel="stylesheet" href="updateForm.css">-->
    <title>DASH</title>
    <style>
        .login-page {
            width: 600px;
            padding: 3% 0 0;
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
            /*width: 100%;*/
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
        <table width="100%">
            <tbody>
                <?php echo $previousMessages?>
            </tbody>
        </table>
        <form action="PushMessageToConversation.php" method="get" class="login-form">
            <table width="100%" style="padding: 5px;">
                <tbody>
                <tr>
                    <td colspan="3" width="100%">
                        <input type="text" name="message" placeholder="Type reply">
                        <input type="hidden" name="parent" value="<?php echo $_GET['parent'] ?>">
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        Select Message Destination<br/><br/>
                    </td>

                </tr>
                <tr>
                    <td width="33%">
                        <?php echo $destination?>
                    </td>
                    <td width="33%">
                        <?php echo $destinationMA?>
                    </td>
                    <td width="33%">
<!--                        <input type="hidden" name="message" value="--><?php //echo $_GET['message'] ?><!--">-->
                        <input type='radio' name='dest' value='0'>MA</br>
                        <input type='radio' name='dest' value='1'>Reception</br>
                        <input type='radio' name='dest' value='2'>Referral</br>
                        <input type='radio' name="dest" value="3">Left VM<br/>
                        <input type='radio' name="dest" value="-1">Med. Auth.<br/>
                        <input type='radio' name='dest' value=''>None</br>

                    </td>
                </tr>

                </tbody>
            </table>
            <button>Submit</button>
        </form>
    </div>
</div>
</html>