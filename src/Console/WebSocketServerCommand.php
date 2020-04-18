<?php


namespace Perry\MsgSwoole\Console;

use Illuminate\Console\Command;
class WebSocketServerCommand extends Command
{
    //做swoole的启动
    //启动，停止，重启，重新加载
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'msg:swoole {action : start|stop|restart|reload|infos}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '这是一个启动msg-swoole命令';

    protected $serverManager;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        //$this->laravel这是application
        $this->serverManager = $this->laravel->make('msg-swoole.manager');
        $this->execution();
    }

    public function execution()
    {
        return $this->{$this->argument('action')}();
    }

    protected function start()
    {
        echo '----start-----';
        $this->serverManager->run();
    }

    protected function stop()
    {
        return 'stop';
    }

    protected function restart()
    {
        return 'start';
    }

    protected function reload()
    {
        return 'reload';
    }

    protected function infos()
    {
        return 'infos';
    }
}