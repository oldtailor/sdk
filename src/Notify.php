<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/26
 * Time: 16:47
 */

namespace oldtailor\sdk;

use \Stomp;
/**
 * 消息通知
 * Class Notify
 * @package oldtailor\sdk
 */
class Notify
{

    public $mq;
    public $mq_username;
    public $mq_password;


    private $pool = [];

    /**
     * 监听消息通知
     */
    public function listen(){

        $stomp = new Stomp($this->mq,$this->mq_username,$this->mq_password);
        $stomp->subscribe("/queue/".OLDTAILOR_APP);

        while(true) {

            $hasFrame = $stomp->hasFrame();

            //判断是否有读取的信息
            if($hasFrame) {

                $frame = $stomp->readFrame();

                try {

                    $data = json_decode($frame->body);

                    foreach ($this->pool[$data->event] ?: [] as $item){
                        call_user_func($item,$data->data);
                    }

                    $stomp->ack($frame);

                }catch (\Exception $e){


                }


            }

        }

    }


    public function add(string $event,$callback){

        $this->pool[$event][] = $callback;

    }


}