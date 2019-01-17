<?php
/**
 * Created by PhpStorm.
 * User: SimInternal
 * Date: 1/17/2019
 * Time: 11:11 AM
 */
session_start();
session_destroy();
header('location:/index.html');
