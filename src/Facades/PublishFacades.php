<?php


namespace Perry\MsgSwoole\Facades;

use Illuminate\Support\Facades\Facade;

class PublishFacades extends Facade
{
    protected static function getFacadeAccessor() {
        return 'msg-swoole.publish';
    }
}