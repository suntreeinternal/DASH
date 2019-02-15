<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 2/11/2019
 * Time: 8:26 AM
 */


class AuditLog
{
    public function SetChange($change){
        echo $change;
        $conReferrals = new mysqli('localhost', $_SESSION['username'], $_SESSION['password'], 'Referrals');
        $query = "INSERT INTO Referrals.ChangeLog (UserID, ChangeSummery, DateTime) VALUES ('" . $_SESSION['userID'] . "', ' ". $change . "', '"
        . date("Y-m-d h:i:sa") . "')";
        $conReferrals->query($query);
        return;
    }

}