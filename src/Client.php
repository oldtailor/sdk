<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/26
 * Time: 14:26
 */


namespace oldtailor\sdk;

use oldtailor\sdk\errors\ConnectError;
use oldtailor\sdk\errors\GateError;
use oldtailor\sdk\errors\ParamError;
use oldtailor\sdk\errors\LogicError;
use oldtailor\sdk\errors\UserAuthError;
use oldtailor\sdk\errors\SystemError;

class Client
{
    public $api="http://127.0.0.1/";
    public $token;
    public $appId;
    public $appKey;


    private $curl;

    public function __construct()
    {
        $this->curl = new \Curl\Curl();
    }


    public function call($method,$params = [],$app=""){

        $resp = $this->curl->post($this->api,[
            'app'  =>$app,
            'token'=>$this->token,
            'app_id'=>$this->appId,
            'app_key'=>$this->appKey,
            'method'=>$method,
            'params'=>$params,
        ]);

        if(!$resp) throw new ConnectError();

        if($resp->ret_code != "SUCCESS" ) throw new GateError($resp->ret_msg);

        if($resp->res_code != "SUCCESS" ){

            switch ($resp->err_code){
                case 1:
                    throw new SystemError($resp->err_code_des);
                case 4:
                    throw new UserAuthError();
                case 5:
                    throw new ParamError($resp->err_code_des);
                case 6:
                    throw new LogicError($resp->err_code_des,$resp->err_code_sub);
                default:
                    throw new \Exception("unknown");
            }

        }

        return $resp->response;
    }


}