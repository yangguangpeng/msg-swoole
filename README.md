<h1 align="center"> msg-swoole </h1>

<p align="center"> msg-swoole.</p>

###功能
    实现实时推送消息到客户端
## 环境准备
    1.安装redis拓展
    pecl install redis
    2.安装swoole拓展
    pecl install swoole
## 安装和使用

1.下载
```shell
$ composer require perry/msg-swoole -vvv
```
2.配置上服务提供者
Perry\MsgSwoole\SwooleServiceProvider::class

3.生成配置文件
php artisan vendor:publish --provider="Perry\MsgSwoole\SwooleServiceProvider"

4.发布信息
    $receiver_id：接受者id，（这里的$receiver_id是明文）
    $content：发布的内容
    a.门面模式调用
    在app.php加上alias加上门面别名配置：'MsgPublish'=>Perry\MsgSwoole\Facades\PublishFacades::class
    代码：MsgPublish::setReceiver($receiver_id)->publish();
    b.服务容器调用
    代码：app('msg-swoole.publish')->setReceiver($receiver_id)->publish($content)

5.演示demo路由，路由前缀是msg-swoole
Route::get('/', 'DemoController@show');
Route::get('/publish', 'DemoController@publish');

6.启动websocket服务
php artisan msg:swoole start

## 安全考虑,对来自客户端的$receiver_id进行加密
1.开启配置文件
'channel'=>[
        //是否验证客户端的信息，默认开启
        'is_verification'=>true
 ]

2.安装原生jwt: github地址：https://github.com/cdoco/php-jwt


3.加密$receiver_id后发给客户端
app('msg-swoole.encryption')->encrypt($receiver_id);

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/perry/msg-swoole/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/perry/msg-swoole/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT
