<?php


namespace Perry\MsgSwoole;
use Illuminate\Support\ServiceProvider;
use Perry\MsgSwoole\Console\WebSocketServerCommand;
use Perry\MsgSwoole\Server\Manager;
use Perry\MsgSwoole\Services\EncryptionService;
use Perry\MsgSwoole\Services\PublishService;
use Illuminate\Support\Facades\Route;

class SwooleServiceProvider extends ServiceProvider
{
    /**
     * websocket服务
     * @var
     */
    protected $server;

    public function boot()
    {
        //发布配置文件
        $this->publishes([
            __DIR__.'/Config/msg_swoole.php' => config_path('msg-swoole.php')
        ]);

        // 注册组件路由
        $this->registerRoutes();
        // 指定的组件的名称，自定义的资源目录地址
        $this->loadViewsFrom(
            __DIR__.'/Resources/Views', 'msg-swoole'
        );
    }

    public function register()
    {
        #注册配置文件
        $this->registerConfig();
        #注册服务
        $this->registerServie();
        //注册命令
        $this->commands([
            WebSocketServerCommand::class,
        ]);
    }

    private function registerRoutes()
    {
        Route::group([
            // 是定义路由的命名空间
            'namespace' => 'Perry\MsgSwoole\Http\Controllers',
            // 这是前缀
            'prefix' => 'msg-swoole',
            // 这是中间件
            //'middleware' => 'web',
        ], function () {

            $this->loadRoutesFrom(__DIR__.'/Routes/routes.php');
        });
    }

    /**
     * 注册配置文件
     */
    public function registerConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/Config/msg_swoole.php', 'msg-swoole'
        );
    }

    protected function registerServie()
    {
        //注册websocket服务
        $this->app->singleton('msg-swoole.server',function(){
            if(is_null($this->server)){
                $table = new \swoole_table(1024);
                $table->column('receiver_id', \swoole_table::TYPE_INT);
                $table->create();
                $server = new \Swoole\WebSocket\Server(config('msg-swoole.listen.host'), config('msg-swoole.listen.port'));
                //将table保存在serv对象上
                $server->table = $table;
                $this->server = $server;
            }
            return $this->server;
        });

        $this->app->singleton('msg-swoole.manager',function($app){
            return new Manager($app);
        });

        $this->app->singleton('msg-swoole.publish',function($app){
            return new PublishService($app);
        });

        $this->app->singleton('msg-swoole.encryption',function($app){
            return new EncryptionService($app);
        });
    }
}