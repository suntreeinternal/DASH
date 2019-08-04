<html>
<?php
session_start();
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 11/5/2018
 * Time: 10:02 AM
 */
$con = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
if($con->connect_error){
    header('location:/index.html');
    exit();
} else {
    echo '';
    $query = 'SELECT COUNT(*) FROM TempPatient where LastName=\'' . $_GET['last'] . '\' AND BirthDate=\'' . $_GET['birthDate'] . '\'';
    $result = $con->query($query);
    $row = $result->fetch_row();
    if ($row[0] == 0) {
        if ($_GET['Pipek'] == "on"){
            $query = 'INSERT INTO Referrals.TempPatient (FirstName, LastName, BirthDate, Pipek) VALUES (\'' . $_GET['first'] . '\',\'' . $_GET['last'] . '\',\'' . $_GET['birthDate'] . '\',\'1\')';
        } else {
            $query = 'INSERT INTO Referrals.TempPatient (FirstName, LastName, BirthDate) VALUES (\'' . $_GET['first'] . '\',\'' . $_GET['last'] . '\',\'' . $_GET['birthDate'] . '\')';
        }
        echo $query . "</br>";
        $result = $con->query($query);
        $query = "SELECT LAST_INSERT_ID()";
        $result = $con->query($query);
        $row = $result->fetch_row();
        echo var_dump($row) . '</br>';
        $query = "INSERT INTO PatientData(SW_ID, Message_alert_to_group, Note, Phone_number, temp) VALUES ('" . $row[0] ."','0','','0','1')";
        echo $query. "</br>";
        $result = $con->query($query);
        $query = "INSERT INTO Referrals.ChangeLog (UserID, ChangeSummery, DateTime) VALUES ('" . $_SESSION['userID'] . "', 'Patient " . $_GET['first'] . " " . $_GET['last'] . " with DOB " .  $_GET['birthDate'] . " Added as a Temporary patient who is not is SW yet', ' " . date("Y-m-d h:i:sa") . "')";

        $result = $con->query($query);
        if ($_GET['Pipek'] == 'on'){
            header('location:/main.php');
        } else {
            header($_SESSION['previous']);
        }
    }

}
?>
</html>

