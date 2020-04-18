<?php


namespace Perry\MsgSwoole\Contracts;


interface PublishInterface
{
    /**
     * 发布信息到频道
     * @param $content
     * @return mixed
     */
    public function publish($content);

    public function setReceiver(int $receiver_id);
}