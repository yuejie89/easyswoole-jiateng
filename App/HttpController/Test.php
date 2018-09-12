<?php
namespace App\HttpController;

use EasySwoole\Core\Http\AbstractInterface\Controller;
use Core\AbstractInterface\AbstractController;
use EasySwoole\Core\Http\Request;
use EasySwoole\Core\Http\Response;
use \EasySwoole\Core\Component\Logger;
use \EasySwoole\Core\Swoole\ServerManager;

use App\HttpController\CommonController;

/**
 * Class Register
 * @package App\HttpController
 */
class Test extends Controller
{
    function index()
    {
        $this->response()->write('Register easySwoole!');
    }
    protected function onRequest($action): ?bool
    {
    	$this->request()->withAttribute('requestTime', microtime(true));
        return parent::onRequest($action); // TODO: Change the autogenerated stub
    }
    /**
     * web注册 PC端、手机端（用手机号注册）
     */
    function add_test()
    {
        $post=$this->request()->getRequestParam();
        $arr=[];
        $arr['key'][]=array_keys($post);

        $this->writeJson('666',$arr, '123456');
    }
    /**
     * token 测试
     */
    function token_test()
    {
        $post=$this->request()->getRequestParam();
        
        $data=CommonController::token_user_id($post['token']);
        $this->writeJson('666',$post, $data);
    }
    
 

	
    protected function actionNotFound($action):void
    {
        $this->response()->write('Register actionNotFound');
    }
    protected function afterAction($actionName):void
    {
    	
    }
}
