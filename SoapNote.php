<?php
    $ID = $_GET['ID'];

    $con = mssql_connect('sunserver', 'siminternal', 'Watergate2015');
    if (!mssql_select_db('sw_charts', $con)) {
       die('Unable to select database!');
    }

    $query = 'SELECT * FROM dbo.Encounter_Data WHERE EncountID=\''. $ID . '\'';
    $result = mssql_query($query);
    while ($row = mssql_fetch_array($result)){
        $temp = $row[3];
        $temp = str_ireplace('{\rtf1\ansi\ansicpg1252\deff0{\fonttbl',"", $temp);
        $temp = str_ireplace('{\f0\fnil\fcharset0 Courier New;}', '', $temp);
        $temp = str_ireplace('{\f1\fnil\fcharset0 Arial;}', '', $temp);
        $temp = str_ireplace('{\colortbl ;\red0\green0\blue0;\red255\green0\blue0;}', '', $temp);
        $temp = str_ireplace('{\colortbl ;\red255\green0\blue0;\red0\green0\blue0;}', '', $temp);
        $temp = str_ireplace('\viewkind4\uc1\pard\cf1\lang1033\f0\fs20', '', $temp);
        $temp = str_ireplace('\cf2\b PMH\cf1\b0', '', $temp);
        $temp = str_ireplace('}', '', $temp);
        $temp = str_ireplace('\pard', "", $temp);
        $temp = str_ireplace('\par', '<br/>', $temp);
        $temp = str_ireplace('{\colortbl ;\red0\green0\blue0;', '', $temp);
        $temp = str_ireplace('\tab', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $temp);
        $temp = str_ireplace('\b0', '', $temp);
        $temp = str_ireplace('\b', '', $temp);
        $temp = str_ireplace('\cf2', '', $temp);
        $temp = str_ireplace('\cf1', '', $temp);
        $temp = str_ireplace('\f1\fs16','', $temp);
        $temp = str_ireplace('\f0\fs20', '', $temp);
        echo $temp;
    }