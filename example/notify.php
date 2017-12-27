<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/27
 * Time: 8:18
 */
error_reporting(E_ALL);

require 'vendor/autoload.php';

define("OLDTAILOR_APP",'fenrun');

$notify = new \oldtailor\sdk\Notify();

$notify->mq = "tcp://127.0.0.1";
$notify->mq_username = "admin";
$notify->mq_password = "admin";

$notify->add("test",function ($data){

    file_put_contents('php://stdout', print_r($data, true));

});

$notify->listen();
