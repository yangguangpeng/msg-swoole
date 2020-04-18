<?php


namespace Perry\MsgSwoole\Server;


use Illuminate\Contracts\Container\Container;

class Manager
{
    /**
     * @var swoole服务
     */
    protected $server;
    /**
     * laravel的应用程序Application
     * @var [type]
     */
    protected $laravel;

    public function __construct(Container $laravel)
    {
        $this->laravel = $laravel;
        $this->server = $this->laravel->make('msg-swoole.server');
        #设置事件监听
        $this->setEvent();
    }


    protected function setEvent()
    {
        //建立连接
        $this->server->on('open',[$this,'open']);
        //接收消息
        $this->server->on('message',[$this,'message']);
        //连接关闭
        $this->server->on('close',[$this,'close']);
    }


    public function open(\Swoole\WebSocket\Server $server, $request)
    {
        echo "server: handshake success with fd{$request->fd}\n";
    }

    public function message(\Swoole\WebSocket\Server $server, $frame)
    {
        echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";

        //接受到来自客户端的数据
        $received_data = $frame->data;

        //验证
        if(config('msg-swoole.channel.is_verification')){
            $received_data = app('msg-swoole.encryption')->getReceiverId($received_data);
            if(!$received_data){
                return false;
            }
        }

        $redis = $this->getRedis();
        if ($redis->subscribe([config('msg-swoole.channel.channel_prefix').$received_data])) // 或者使用psubscribe
        {
            $server->table->set($frame->fd,['receiver_id'=>$received_data]);
            while ($msg = $redis->recv()) {
                // msg是一个数组, 包含以下信息
                // $type # 返回值的类型：显示订阅成功
                // $name # 订阅的频道名字 或 来源频道名字
                // $info  # 目前已订阅的频道数量 或 信息内容
                list($type, $name, $info) = $msg;
                if ($type == 'subscribe') // 或psubscribe
                {
                    // 频道订阅成功消息，订阅几个频道就有几条
                }
                else if ($type == 'unsubscribe') // 或punsubscribe
                {
                    echo '取消订阅名称:'.$name;
                    if($info == 0)
                    break; // 收到取消订阅消息，并且剩余订阅的频道数为0，不再接收，结束循环
                }
                else if ($type == 'message') // 若为psubscribe，此处为pmessage
                {
                    $server->push($frame->fd, "您的消息".$info);

                }
            }
        }
    }

    public function close($ser, $fd)
    {
        $channel_arr = $ser->table->get($fd);
        #订阅
        $redis = $this->getRedis();
        $channel = config('msg-swoole.channel.channel_prefix').$channel_arr['receiver_id'];
        var_dump($channel);
        #取消订阅
        $redis->unsubscribe([$channel]);

        echo "client {$fd} closed\n";
    }

    protected function getRedis()
    {
        $redis = new \Swoole\Coroutine\Redis();
        $redis->connect(env('REDIS_HOST'),env('REDIS_PORT'));
        return $redis;
    }

    /**
     * 启动websocket
     */
    public function run()
    {
        $this->server->start();
    }
}