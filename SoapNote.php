<?php
session_start();
$ID = $_GET['ID'];

$con = mssql_connect('sunserver', 'siminternal', 'Watergate2015');
if (!mssql_select_db('sw_charts', $con)) {
    die('Unable to select database!');
}
$first = $_GET['first'];
$first = strtolower($first);
$first = ucfirst($first);

$last = $_GET['last'];
$last = strtolower($last);
$last = ucfirst($last);

$patientName = $last . ', ' . $first;

//Converting DOS to good format

$date = strtotime($_GET['dos']);
$DOS = date('m-d-Y', $date);
$date = strtotime($_SESSION['patientDOB']);

$DOB = date("m-d-Y", $date);
$query = 'SELECT * FROM dbo.Encounter_Data WHERE EncountID=\''. $ID . '\'';

$result = mssql_query($query);
echo '<table width="100%"><tbody><tr><th width="33%" align="left">' . $patientName . '</th><th width="33%">DOS: ' . $DOS . '</th><th width="34%" align="right">DOB: ' . $DOB .'</th> </tr></tbody></table>';
while ($row = mssql_fetch_array($result)){

    if ($row[2] == 100){
        echo '</br></br><h4 style="display: inline">SUBJECTIVE:</h4>';
    } elseif ($row[2] == 101) {
        echo '</br></br><h4 style="display: inline">OBJECTIVE:</h4>';
    } elseif ($row[2] == 102) {
        echo '</br></br><h4 style="display: inline">ASSESSMENT:</h4>';
    } elseif ($row[2] == 103) {
        echo '</br></br><h4 style="display: inline">PLAN:</h4>';
    } elseif ($row[2] == 104) {
        echo '</br></br><h4 style="display: inline">MEDICATION:</h4>';
//            echo $row[3];
    } elseif ($row[2] == 105) {
        echo '</br></br><h4 style="display: inline">FOLLOW UP:</h4>';
    }

    $temp = $row[3];
    $countLine = 0;
    $countL = 0;
    $index = 0;

    echo "<a style=\"font-family: 'Courier New'; font-size: 10pt\">";
    $temp = str_ireplace('\rtf1\ansi\ansicpg1252\deff0',"", $temp);
    $temp = str_ireplace('\rtf1\ansi\deff0',"",$temp);
    $temp = str_ireplace('\fonttbl', '', $temp);
    $temp = str_ireplace('\f0\fnil\fcharset0 ', '', $temp);
    $temp = str_ireplace('{\colortbl ;\red0\green0\blue0;\red255\green0\blue0;}', '', $temp);
    $temp = str_ireplace('{\colortbl ;\red255\green0\blue0;\red0\green0\blue0;}', '', $temp);
    $temp = str_ireplace('}', '', $temp);
    $temp = str_ireplace('{', '', $temp);
    $temp = str_ireplace('\viewkind4\uc1', "", $temp);
//        $temp = str_ireplace('\par', '<br/>', $temp);
    $temp = str_ireplace('\colortbl ;\red0\green0\blue0;', '', $temp);
    $temp = str_ireplace('\f1\fnil\fcharset0', "", $temp);
    $temp = str_ireplace('\f1\fs16', "", $temp);
    $temp = str_ireplace(' \f0\fs20', "", $temp);
    $temp = str_ireplace('\f0\fs20  ', "", $temp);
    $temp = str_ireplace('\fs20  ', "", $temp);
    $temp = str_ireplace('\f2 ', "", $temp);
    $temp = str_ireplace('\f1  ', "", $temp);

    $temp = str_ireplace('Arial;', "", $temp);

    $temp = str_ireplace('\tab', '', $temp);
    $temp = str_ireplace('\b0', '</b>', $temp);
    $temp = str_ireplace('\b', '<b>', $temp);
    $temp = str_ireplace('\cf2', '', $temp);
    $temp = str_ireplace('\cf1', '', $temp);
    $temp = str_ireplace('Courier New;','', $temp);
//        $temp = str_ireplace('\f0\fs20', '', $temp);
    $temp = str_ireplace('d\lang1033\f0\fs20 ', '', $temp);
//        echo $temp;
    $count = substr_count($temp, 'LAST');

    if ($row[2] == 100) {


        if ($count != 0) {

            $tempCount = 0;
            $stringToReplace = '';
            $start = strrpos($temp, 'LAST');

            $str_arr = explode(' ', $temp);
//            echo var_dump($str_arr);
            $max = sizeof($str_arr);
            $val = array_search('LAST', $str_arr);
//        echo $val;
            $total = "";
            $toPrint = '';
            echo "<br/>";

            if ($val != '') {
                for ($x = $val; $x < $max; $x++) {
//                    echo var_dump($str_arr);
                    if ($str_arr[$x] == 'LAST') {
                        if ($countLine == 0) {
                            $toPrint .= $str_arr[$x] . ' ';
                        } elseif ($countLine == 1) {
                            $lastSpot = strlen($toPrint);
                            for ($i = $lastSpot; $i < 35; $i++) {
                                $toPrint .= '&nbsp;';
                            }
                            $toPrint .= $str_arr[$x] . " ";

                        } elseif ($countLine == 2) {
                            $lastSpot = strlen($toPrint);
                            $countString = substr_count($toPrint, "&nbsp;");
                            $lastSpot = $lastSpot - ($countString * 5);
                            for ($i = $lastSpot; $i < 70; $i++) {
                                $toPrint .= '&nbsp;';
                            }
                            $toPrint .= $str_arr[$x] . " ";
                        }
                        $countLine++;
                    } elseif ($countLine == 0) {
                        if ($str_arr[$x] !== '') {
                            $toPrint .= $str_arr[$x] . ' ';
                        }
                        $countLine++;
                    } else {
                        if (strpos($str_arr[$x], '\par') !== false) {
//                        $str_arr[$x] = str_ireplace("\par", "</br></br>", $str_arr[$x]);
                            $countLine = 0;
                            $total .= $toPrint . "</br>";
                            $toPrint = '';
                        } else {
                            if ($str_arr[$x] !== '') {
                                $toPrint .= $str_arr[$x] . ' ';
                            }
                        }
                    }
                }
//            echo $total;
            }
            $temp = str_ireplace('\par', '<br/>', $temp);

            $val = strpos($temp, "LAST");
            $lastVal = strrpos($temp, "LAST", -1);
            $lastVal = strpos($temp, '<br/>', $lastVal);
            $lastVal .= 5;
            $temp1 = substr($temp, 0, $val);
            $size = sizeof($temp);
            $temp = $temp1 . $total . substr($temp, $lastVal, $size);
        } else {
            $temp = str_ireplace('\par', '<br/>', $temp);
        }
    } else {
        $temp = str_ireplace('\par', '<br/>', $temp);
    }

    echo $temp;
}