<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 2/15/2019
 * Time: 1:04 PM
 */

class server{
    private  $server;
    private $username = 'DashLoginUser';
    private $password = 'Cr4sietd!';
    public function __construct(){
        $this->server = new mysqli('localhost', $this->username, $this->password, 'Referrals');
    }

    public function getData($query){
        $result = $this->server->query($query);
        return $result;
    }
}