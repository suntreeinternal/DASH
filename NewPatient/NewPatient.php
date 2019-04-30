<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 10/29/2018
 * Time: 12:14 PM
 */
?>

<html>
<head>
    <link rel="stylesheet" href="../Menu/menu.css">
    <style>
        .dropbtn {
            background-color: #4CAF50;
            color: white;
            padding: 16px;
            font-size: 16px;
            border: none;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f1f1f1;
            min-width: 200px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 5px 5px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {background-color: #ddd;}

        .dropdown:hover .dropdown-content {display: block;}

        .dropdown:hover .dropbtn {background-color: #3e8e41;}
    </style>
</head>

<body style="background:darkgray;">
<?php include "../Menu/menu.php"?>

<form action="submitNewPatient.php">
    <table>
        <tbody>
            <tr>
                <td>
                    <input type="text" name="first" placeholder="First Name">
                </td>
                <td>
                    <input type="text" name="last" value="<?php echo $_GET['last']?>">
                </td>
                <td>
                    <input type="date" name="birthDate" value="<?php echo $_GET['date']?>">
                </td>
                <td>
                    <input type="checkbox" name="Pipek">Pipek Patient &nbsp;
                </td>
                <td>
                    <input type="submit" value="Create New Patient">
                </td>
            </tr>
        </tbody>
    </table>
</form>

</body>

