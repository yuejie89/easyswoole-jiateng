<?php
namespace App\HttpController;


use EasySwoole\Core\Http\AbstractInterface\Controller;
use EasySwoole\Core\Swoole\ServerManager;

class Tcp extends Controller
{

    function index()
    {
        // TODO: Implement index() method.
       //$this->actionNotFound('tcp null');  
       $this->response()->write('Tcp index');

    } 
    protected function actionNotFound($action):void
    {
        $this->response()->write('Tcp actionNotFound');
    }
    /*
     * 请调用who，获取fd
     * http://ip:9501/tpc/push/index.html?fd=xxxx
     */
    function push()
    {
        $fd = intval($this->request()->getRequestParam('fd'));
        $info = ServerManager::getInstance()->getServer()->connection_info($fd);
        if(is_array($info)){
            ServerManager::getInstance()->getServer()->send($fd,'push in http at '.time());
        }else{
            $this->response()->write("fd {$fd} not exist");
        }
    }
}
