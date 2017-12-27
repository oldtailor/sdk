<?php
error_reporting(E_ALL);

require 'vendor/autoload.php';

$client = new \oldtailor\sdk\Client();

$client->appId="10001";
$client->appKey="1234567";

$token = $client->call("token",[
    'key'=>"admin",
    'password'=>'han123',
    'type'=>4
]);

$client->token = $token;

$resp = $client->call("order_create",[

    'skus'=>[
        2473 => 1
    ],
    'address_id'=>6,
    'delivery_id'=>12,
    'plugin_data'=>[
        'store_take'=>['store_id'=>428],
    ],


]);

print_r($resp);