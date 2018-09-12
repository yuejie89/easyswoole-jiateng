<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/1/9
 * Time: 下午1:04
 */

namespace EasySwoole;

use \EasySwoole\Core\AbstractInterface\EventInterface;
use \EasySwoole\Core\Swoole\ServerManager;
use \EasySwoole\Core\Swoole\EventRegister;
use \EasySwoole\Core\Http\Request;
use \EasySwoole\Core\Http\Response;

use EasySwoole\Core\Component\Di;
use EasySwoole\Core\Component\SysConst;
 
use \EasySwoole\Core\Swoole\EventHelper;

use EasySwoole\Core\Component\Pool\PoolManager;
use App\Utility\MysqlPool2;
use App\HttpController\Tcp\Parser;

Class EasySwooleEvent implements EventInterface {

    public static function frameInitialize(): void
    {
        // TODO: Implement frameInitialize() method.
        date_default_timezone_set('Asia/Shanghai');

        
    }

    public static function mainServerCreate(ServerManager $server,EventRegister $register): void
    {
        // TODO: Implement mainServerCreate() method.
        // Di::getInstance()->set('MYSQL',\MysqliDb::class,Array (
        //     'host' => '127.0.0.1',
        //     'username' => 'root',
        //     'password' => 'root',
        //     'db'=> 'easy',
        //     'port' => 3306,
        //     'charset' => 'utf8')
        // );
	
	    $tcp = $server->addServer('tcp',9508);
        EventHelper::registerDefaultOnReceive($register,\App\HttpController\Tcp\Parser::class,function($errorType,$clientData,$client){
            //第二个回调是可有可无的，当无法正确解析，或者是解析出来的控制器不在的时候会调用
            TaskManager::async(function ()use($client){
                sleep(3);
                \EasySwoole\Core\Socket\Response::response($client,"Bye");
                ServerManager::getInstance()->getServer()->close($client->getFd());
            });
            return "{$errorType} and going to close";
        });        
        
    }

    public static function onRequest(Request $request,Response $response): void
    {
        // TODO: Implement onRequest() method.
    }

    public static function afterAction(Request $request,Response $response): void
    {
        // TODO: Implement afterAction() method.
        // $response->write('afterAction EasySwooleEvent');
    }
}
