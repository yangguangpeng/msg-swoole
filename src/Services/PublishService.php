<?php

/**
 * 向频道发布消息
 */
namespace Perry\MsgSwoole\Services;


use Perry\MsgSwoole\Contracts\PublishInterface;

class PublishService implements PublishInterface
{
    protected $channel;

    public function setReceiver(int $receiver_id)
    {
        $this->channel = config('msg-swoole.channel.channel_prefix').$receiver_id;
        return $this;
    }

    public function publish($content)
    {
        $redis = new \Redis;
        $redis->connect(env('REDIS_HOST'),env('REDIS_PORT'));
        $redis->publish($this->channel,$content);
    }

}