<html>
<style>
    * {
        font-family: "Courier New";
    }
</style>

<body>
<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 10/25/2018
 * Time: 12:01 PM
 */
    $connection = mssql_connect('sunserver', 'siminternal', 'Watergate2015');

    if (!$connection) {
        die('Unable to connect!');
    }

    if (!mssql_select_db('sw_charts', $connection)) {
        die('Unable to select database!');
    }

    $result = mssql_query('SELECT * FROM dbo.Gen_Demo WHERE last_name=\'smith\' AND first_name=\'darrell\'');
    $row = mssql_fetch_array($result);
    $patientID = $row[0];
    $patientName = strtolower($row[2]);

//    echo $row[2] . " " . $row[1] . "<br/>";
    $result = mssql_query('SELECT * FROM dbo.Encounters WHERE Patient_ID=\'' . $patientID . '\'' );
    $row = mssql_fetch_array($result);
    $encounterID = $row[0];
//    echo $row['visit_date'] . " " . $encounterID . "<br/>";

    $result = mssql_query('SELECT * FROM dbo.Encounter_Data WHERE EncountID=\'' . $encounterID . '\'');
    $row = mssql_fetch_array($result);
//    echo $row[3] . "<br/><br/>";

    $result = mssql_query('SELECT * FROM dbo.Reports WHERE Patient_ID=\'' . $patientID . '\'');
    $row = mssql_fetch_array($result);
    $report = $row[1];
//    echo $reportort;

    $result = mssql_query('SELECT * FROM dbo.Reports_Text WHERE Report_ID=\'' . $report . '\'');
    $row = mssql_fetch_array($result);
    $toPrint = $row[2];
    $temp = strtolower($row[2]);

    $test = strpos($temp, '\b\par\par\par\par\par');
    $toPrint = substr($row[2], $test + 23);
    $test = strpos($toPrint, 'mlf');
    $takeOff = (strlen($toPrint)-$test)*-1;
//    echo $takeOff;
    $toPrint = substr($toPrint, 0, $takeOff);
    $toPrint = str_ireplace('\par', '<br/>', $toPrint);
    $toPrint = str_ireplace('\tab', '&nbsp; &nbsp; &nbsp;',$toPrint);
    echo $toPrint . "<br/>";

?>
</body></html>
