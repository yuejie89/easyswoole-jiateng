<?php

namespace App\HttpController;

use EasySwoole\Core\Http\AbstractInterface\Controller;
use EasySwoole\Core\Component\Di;
use EasySwoole\Core\Component\Pool\PoolManager;
use EasySwoole\Core\Swoole\Coroutine\Client\Redis;

/**
 * Class Index
 * @package App\HttpController
 */
class Index extends Controller
{
    /**
     * 首页方法
     * @author : evalor <master@evalor.cn>
     */
    function index()
    {
        $this->response()->withHeader('Content-type', 'text/html;charset=utf-8');
        $this->response()->write('<div style="text-align: center;margin-top: 30px"><h2>欢迎使用EASYSWOOLE</h2></div></br>');
        $this->response()->write('<div style="text-align: center">您现在看到的页面是默认的 Index 控制器的输出</div></br>');
        $this->response()->write('<div style="text-align: center"><a href="https://www.easyswoole.com/Manual/2.x/Cn/_book/Base/http_controller.html">查看手册了解详细使用方法</a></div></br>');
    }
    /**
    *capistrano  git自动拉取test
    */
    function index()
    {
        echo 'capistrano 自动拉取没';
    }
    
    /**
     * front_test
     */
    function front_test()
    {   
    	// $response=$this->response();
     //    $statusCode=666;
        // $data=array(
        //     'code'=>200,
        //     'data'=>"CS",
        //     'session'=>$_SESSION
        //     );
     //    $response->withStatus($statusCode);
    	$_SESSION['login']='123';
    	
        $this->writeJson($data);
        
    }
    /**
     * mysql_test
     */
    function mysql_test()
    {   

        // $db = Di::getInstance()->get('MYSQL');
        // $data['phone']=15317937990;
        // $data['password']='888888';
        // $data['system_id']=1;
        // $db->insert ("user", $data);

        // $users = $db->get('user');
        // $this->writeJson($users);

        $pool = PoolManager::getInstance()->getPool('App\Utility\MysqlPool2'); // 获取连接池对象
        $pool_db = $pool->getObj($timeOut = 0.1);
        $pool_users = $pool_db->get('user');
         $pool->getObj($pool_db);
        $this->writeJson($users,$pool_users,$pool_db);

       
        
    }
    function redis_test()
    {
        // $redis = new Redis('127.0.0.1',6379,false);
        // $redis->exec('set', 'a', '普通连接 我 不 傻');
        // $a = $redis->exec('get', 'a');

        $pool = PoolManager::getInstance()->getPool('App\Utility\RedisPool'); 
        $pool_redis = $pool->getObj(); // 这里的pool是通过poolManager获取的RedisPool
        $pool_redis->exec('set', 'test', '345');
        $pool_a = $pool_redis->exec('get', 'test');
        $pool->freeObj($pool_redis);
        $this->writeJson(200,'',$pool_a);
    }
}
