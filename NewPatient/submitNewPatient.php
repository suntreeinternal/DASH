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
    $query = 'SELECT COUNT(*) FROM TempPatient where LastName=\'' . $_GET['last'] . '\' AND BirthDate=\'' . $_GET['birthDate'] . '\'';
    $result = $con->query($query);
    $row = $result->fetch_row();
    if ($row[0] == 0) {
        $query = 'INSERT INTO TempPatient (FirstName, LastName, BirthDate) VALUES (\'' . $_GET['first'] . '\',\'' . $_GET['last'] . '\',\'' . $_GET['birthDate'] . '\')';
//        echo $query;
        $result = $con->query($query);
        $query = "SELECT LAST_INSERT_ID()";
        $result = $con->query($query);
        $row = $result->fetch_row();

//        echo var_dump($row);
//        echo $row[0];
        $query = "INSERT INTO PatientData(SW_ID) VALUES ('" . $row[0] ."')";

        if (!$result = $con->query($query)) {
            // Oh no! The query failed.
            echo "Sorry, the website is experiencing problems.";

            // Again, do not do this on a public site, but we'll show you how
            // to get the error information
            echo "Error: Our query failed to execute and here is why: </br>";
            echo "Query: " . $query . "</br>";
            echo "Errno: " . $con->errno . "</br>";
            echo "Error: " . $con->error . "<br/>";
            exit;
        }

        echo $query;
        $result = $con->query($query);
        $query = "INSERT INTO Referrals.ChangeLog (UserID, ChangeSummery) VALUES ('" . $_SESSION['userID'] . "', 'Patient " . $_GET['first'] . " " . $_GET['last'] . " with DOB " .  $_GET['birthDate'] . " Added as a Temporary patient who is not is SW yet')";
        $result = $con->query($query);
        header($_SESSION['previous']);

    }

}
?>
</html>

