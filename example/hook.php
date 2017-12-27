<?php
require 'vendor/autoload.php';

function output($data){

    file_put_contents("./a.txt",print_r($data,true));
}


$hook = new \oldtailor\sdk\Hook();


$hook->add("test",function($data){


    return $data;
});

$hook->exec();
