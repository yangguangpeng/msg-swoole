<?php


namespace Perry\MsgSwoole\Http\Controllers;


class DemoController
{
    public function show()
    {
        $host = config('msg-swoole.listen.host');
        $port = config('msg-swoole.listen.port');
        $is_encryption = config('msg-swoole.channel.is_verification');
        $encryption = app('msg-swoole.encryption');
        return view('msg-swoole::show',[
            'ids'=>[
                $is_encryption?$encryption->encrypt(1):1,
                $is_encryption?$encryption->encrypt(2):2,
                $is_encryption?$encryption->encrypt(3):3,
            ]
        ]);
    }


    public function publish()
    {
        $user_id = request()->user_id;
        if($user_id){
            //发布
            app('msg-swoole.publish')->setReceiver($user_id)->publish(request()->get('content'));
        }
        return view('msg-swoole::publish');
    }
}