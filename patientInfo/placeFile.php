<?php
//TODO add file name and path to sql database

header('Content-Type: text/plain; charset=utf-8');
session_start();

// get details of the uploaded file
$fileTmpPath = $_FILES['fileToUpload']['tmp_name'];
$fileName = $_FILES['fileToUpload']['name'];
$fileSize = $_FILES['fileToUpload']['size'];
$fileType = $_FILES['fileToUpload']['type'];
$fileNameCmps = explode(".", $fileName);
$fileExtension = strtolower(end($fileNameCmps));
$dateArr = getdate();

$newFileName =  md5(time() . $fileName) . '.' . $fileExtension;
echo $newFileName . "<br/>";

//echo print_r(getdate());
//$dateArr = getdate();
$uploadFileDir = '../uploads/uploads/' . $dateArr['year'] . '/' . $dateArr['mon'] . '/';
$dest_path = $uploadFileDir . $newFileName;

echo $dest_path;
//echo var_dump($_FILES['fileToUpload']);
if(move_uploaded_file($fileTmpPath, $dest_path))
{
    $message ='File is successfully uploaded.';
}
else
{
    $message = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
}

$con = mssql_connect('sunserver', 'siminternal', 'Watergate2015');
$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
if (!mssql_select_db('sw_charts', $con)) {
    die('Unable to select database!');
}
$date = date("Y-m-d h:i:sa");
//TODO add to Change log
if (!$_GET['type']){
    $query = "INSERT INTO Referrals.Uploads (PatientID, UserId, OriginalFileName, SavesFileName, type, typeID, year, mon) VALUES ('" . $_SESSION['currentPatient'] ."','" . $_SESSION['name'] . "','" . $fileName . "','" . $newFileName . "','0','0','" . $dateArr['year'] . "','" .  $dateArr['mon'] . "')";
} else {
    $query = "INSERT INTO Referrals.Uploads (PatientID, UserId, OriginalFileName, SavesFileName, type, typeID, year, mon) VALUES ('" . $_SESSION['currentPatient'] . "','" . $_SESSION['name'] . "','" . $fileName . "','" . $newFileName . "','" . $_GET['type'] . "','" . $_GET['typeID'] ."','" . $dateArr['year'] . "','" .  $dateArr['mon'] . "')";
}
echo $query;
$result = $conReferrals->query($query);
//TODO redirect back to previous page
//TODO GET TO work with 2nd drive


