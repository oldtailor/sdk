<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/26
 * Time: 16:46
 */

namespace oldtailor\sdk;

use oldtailor\sdk\errors\LogicError;
use oldtailor\sdk\errors\ParamError;
use oldtailor\sdk\errors\SystemError;

/**
 * 系统钩子
 * Class Event
 * @package oldtailor\sdk
 */
class Hook
{

    private $pool = [];
    private $user = null;
    private $request = null;

    public function add(string $event,callable $callback){
        $this->pool[$event] = $callback;
    }


    public function exec(){

        if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['event']) && !empty($_POST['user']) && !empty($_POST['request']) ){

            $event = $_POST['event'];
            $this->user = $_POST['user'];
            $this->request = $_POST['request'];
            $data = $_POST['data'] ?? [];
            header("Content-Type:application/json; charset=UTF-8");

            if ($this->pool[$event]){
                try {

                    $data = call_user_func($this->pool[$event], $data);

                    $this->success($data);

                }catch (ParamError $e){
                    $this->error(5,$e->getMessage() );
                }catch (SystemError $e){

                    $this->error(1,$e->getMessage() );

                }catch (LogicError $e){

                    $this->error(6,$e->getMessage(),$e->getCode() );

                }catch (\Exception $e){

                    $this->error(1,$e->getMessage());
                }

            }else{
                $this->error(1,"未有监听函数");
            }

        }

    }

    public function user(){

        return $this->user;
    }

    public function request(){

        return $this->request;
    }

    private function success($data){

        echo json_encode([
            'ret_code'=>'SUCCESS',
            'ret_msg'=>'OK',
            'res_code'=>'SUCCESS',
            'err_code'=>0,
            'err_code_sub'=>0,
            'err_code_des'=>'OK',
            'response'=>$data,
        ]);

        exit;
    }

    private function error(int $code,string $des,int $sub_code=0){

        echo json_encode([
            'ret_code'=>'SUCCESS',
            'ret_msg'=>'OK',
            'res_code'=>'FAIL',
            'err_code'=>$code,
            'err_code_sub'=>$sub_code,
            'err_code_des'=>$des,
        ]);

        exit;
    }



}