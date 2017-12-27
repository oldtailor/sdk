<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/27
 * Time: 9:00
 */

namespace oldtailor\sdk;


use oldtailor\sdk\errors\LogicError;
use oldtailor\sdk\errors\ParamError;
use oldtailor\sdk\errors\SystemError;

class Api
{

    protected $api;
    protected $user;
    protected $request;
    protected $params;
    protected $method;

    public function __construct($api)
    {

        $this->api = $api;
        $this->user = $_POST['user'];
        $this->params = $_POST['params'];
        $this->method = $_POST['method'];
        $this->request = $_POST['request'];

    }


    public function handle(){

        header("Content-Type:application/json; charset=UTF-8");
        $ret = [
            'ret_code'=>'SUCCESS',
            'ret_msg'=>'OK',
            'res_code'=>'FAIL',
            'err_code'=>0,
            'err_code_sub'=>0,
            'err_code_des'=>'OK',
        ];

        try {

            $res = call_user_func([$this->api, $this->method], $this->params);

            $ret['res_code'] = 'SUCCESS';
            $ret['response'] = $res;


        }catch (ParamError $e){
            $ret['err_code'] = 5;
            $ret['err_code_des'] = $e->getMessage();

        }catch (LogicError $e){

            $ret['err_code'] = 6;
            $ret['err_code_sub'] = $e->getCode();
            $ret['err_code_des'] = $e->getMessage();

        }catch (\Exception $e){

            $ret['err_code'] = 1;
            $ret['err_code_des'] = $e->getMessage();

        }catch (\Error $e){

            $ret['err_code'] = 1;
            $ret['err_code_des'] = $e->getMessage();

        }

        echo json_encode($ret);
        exit();
    }


}