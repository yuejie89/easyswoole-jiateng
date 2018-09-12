<?php

namespace App\Common;

use EasySwoole\Core\Http\AbstractInterface\Controller;
use EasySwoole\Core\Http\Request;
use EasySwoole\Core\Http\Response;
use \EasySwoole\Core\Component\Logger;

use EasySwoole\Core\Component\Pool\PoolManager;
use App\HttpController\CommonController;

/**
 * @刘家腾
 * Date: 2018/09/11
 * Time: 20:52
 * 
 * class Common
 * @package App\Commin 
 */
class Common extends Controller
{
	
	function __construct(argument)
	{
		# code...
	}
	public function return_ok($data='',$msg='',$code='')
	{
		$pool->getObj($pool_db);
		$data = $data == ''?'true':$data;
		$msg = $msg == ''?'成功！':$msg;
        $code = $code == ''?'200':$code;
       return $this->writeJson($code,$data,$msg); 
	}
}


?>
