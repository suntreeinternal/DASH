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
        echo $query;
        $result = $con->query($query);
        header('location:/main.php');
    }

}
?>
</html>

