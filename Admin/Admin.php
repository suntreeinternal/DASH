<?php
    session_start();
//    echo var_dump($_SESSION);
?>

<html style='height: 100%'>
  <head>
        <link rel="stylesheet" href="../Menu/menu.css">
        <title>DASH: <?php echo $_SESSION['name']?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        </head>
  <style>
        .modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 400px;
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
        #customers {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #customers td, #customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #customers tr:nth-child(even){background-color: #f2f2f2;}

        #customers tr:hover {background-color: #ddd;}

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #4CAF50;
            color: white;
        }
        </style>
  <body style="background: #C0C0C0; height: 100%">
<?php include "../Menu/menu.php";?>
<table style="height: 400px" width="100%" cellpadding="10px" cellspacing="5px" >
    <tbody>
        <?php
        if ($_SESSION['group'] == 'Admin'){
            echo '<tr>';
            include "Users.php";
            include "Providers.php";
            include "Specialist.php";
            include "Specialty.php";
            echo'</tr>';
        } else {
            echo '<tr>';
            include "Specialist.php";
            include "Specialty.php";
            echo '</tr>';
        }
        ?>
    </tbody>
</table>

</body>
</html>