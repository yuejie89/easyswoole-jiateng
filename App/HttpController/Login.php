<?php
namespace App\HttpController;

use EasySwoole\Core\Http\AbstractInterface\Controller;
use Core\AbstractInterface\AbstractController;
use EasySwoole\Core\Http\Request;
use EasySwoole\Core\Http\Response;
use \EasySwoole\Core\Component\Logger;
use \EasySwoole\Core\Swoole\ServerManager;

use EasySwoole\Core\Component\Pool\PoolManager;

use \EasySwoole\Core\Utility\Validate;
use App\HttpController\CommonController;
/**
 * Class Login
 * @package App\HttpController
 */
class Login extends Controller
{

	function index()
    {
        $this->response()->write('Login easySwoole!');
    }
	protected function onRequest($action): ?bool
    {
    	$this->request()->withAttribute('requestTime', microtime(true));
        return parent::onRequest($action); // TODO: Change the autogenerated stub
    }
	function login_web()
	{	
		$post = $this->request()->getRequestParam();
 
		

		
        if( empty($post['phone']) ) {
            $this->writeJson('3000',$post, '缺少核心参数：phone ');
            return false;
        }
        $data['phone'] = $post['phone'];
        if( empty($post['password']) ) {
            $this->writeJson('3000',$post, '缺少核心参数：password ');
            return false;
        }
        $common = new CommonController();
        $data['password']=$common->hash($post['password']);

        $pool = PoolManager::getInstance()->getPool('App\Utility\MysqlPool2'); // 获取连接池对象
        $pool_db = $pool->getObj($timeOut = 0.1);
		$user = $pool_db->where('phone',$data['phone'])->where('password',$data['password'])->getOne("user");
		if( empty($user) ){
			$pool->getObj($pool_db);
			$msg="账号不存在或错误！";
			$this->writeJson('200',$user, $msg);
        	return false;
		}
		$token_data['token']=$common->token_hash($user['id']);
		$token_data['user_id'] = $user['id'];
		$token_data['creationtime'] = date('Y-m-d H:i:s');
		$token_data['aging'] = 7200;//秒 （俩小时）
		//把之前登录的token改为失效
		$up_token_data['conditions']=1;
		$pool_db->where('user_id',$user['id'])->update("token", $up_token_data);
		//插入新产生的登录token
		$token_id = $pool_db->insert ("token", $token_data);
		if( empty($token_id) ){
			$pool->getObj($pool_db);
			$msg="token 创建失败！请稍后重试！";
			$this->writeJson('200',$token_id, $msg);
        	return false;
		}
		$pool->getObj($pool_db);
		$this->writeJson('200',$token_data, '登录成功！');
        return true;
	}
	protected function actionNotFound($action):void
    {
        $this->response()->write('Login actionNotFound');
    }
    protected function afterAction($actionName):void
    {
    	

    	$request=$this->request();
    	//从请求里获取之前增加的时间戳
		$reqTime = $request->getAttribute('requestTime');
		//计算一下运行时间
		$runTime = round(microtime(true) - $reqTime, 3);
		//获取用户IP地址
		$ip = ServerManager::getInstance()->getServer()->connection_info($request->getSwooleRequest()->fd);

		//拼接一个简单的日志
		$logStr = ' | '.$ip['remote_ip'] .' | '. $runTime . '|' . $request->getUri() .' | '.
		    $request->getHeader('user-agent')[0] .'|Login.php=>afterAction';
		    //判断一下当执行时间大于1秒记录到 slowlog 文件中，否则记录到 access 文件
		if($runTime > 1){
		    Logger::getInstance()->log($logStr, 'slowlog-07-23');
		}else{
		    logger::getInstance()->log($logStr,'access-07-23');
		}
    }
}

?>
