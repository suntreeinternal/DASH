<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 2/15/2019
 * Time: 1:04 PM
 */

class server{
    private  $server;
    private $username = 'siminternal';
    private $password = 'Watergate2015';
    public function __construct(){
        $this->server = new mysqli('localhost', $this->username, $this->password, 'Referrals');
    }

    public function getData($query){
        $result = $this->server->query($query);
        return $result;
    }
}