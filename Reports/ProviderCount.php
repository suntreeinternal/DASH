<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 11/29/2019
 * Time: 7:20 AM
 */
$con = mssql_connect('sunserver', 'siminternal', 'Watergate2015');
$conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
if (!mssql_select_db('sw_charts', $con)) {
    die('Unable to select database!');
}

$query = "SELECT * FROM Referrals.Provider ORDER BY Initals ASC";
$results = $conReferrals->query($query);



?>
<table id="All" width="100%">
    <tbody>
    <tr>
        <?php
            while ($row = $results->fetch_row()){
                echo '<th>' . $row[2] . '</th>';
            }
        ?>

    </tr>
    <tr>
        <?php
        $query = "SELECT * FROM Referrals.Provider ORDER BY Initals ASC";
        $results = $conReferrals->query($query);
            while ($row = $results->fetch_row()){
                $query = "SELECT COUNT(*) FROM Referrals.Referrals WHERE ProviderID='" . $row[0] . "' AND Date BETWEEN '" . $_GET["beginning"] . " 00:00:00' AND '" . $_GET["end"] . " 00:00:00'";
                $resultsCount = $conReferrals->query($query);
                $temp = $resultsCount->fetch_row();
                echo '<td>' . $temp[0] . '</td>';
            }
        ?>
    </tr>
    </tbody>
</table>

