<?php

namespace App\HttpController;

use EasySwoole\Core\Http\AbstractInterface\Controller;
use EasySwoole\Core\Component\Pool\PoolManager;

/**
 * Class Common
 * @package App\HttpController
 */
class CommonController
{
    /**
     * 刘家腾
     * 2018-7-31
     * 密码加盐
     */
    function hash($password) {
        $salt = md5('yuejie');
        $password=md5($password).$salt;
        $password=md5($password);
        return $password;    
    }
    /**
     * 刘家腾
     * 2018-7-31
     * token_hash 加盐
     */
    function token_hash($user_id) {
        //加盐加密
        $salt = md5(random_bytes(8));//从随机源创建一个32位的初始向量，然后进行md5加密。
        //初始向量只是为了给加密算法提供一个可用的种子， 所以它不需要安全保护， 你甚至可以随同密文一起发布初始向量也不会对安全性带来影响。
        $token_hash=md5($user_id).$salt;  //把密码进行md5加密然后和salt连接
        $token_hash=md5($token_hash);  //执行MD5散列
        return $token_hash;  //返回散列    
    }
    /**
     * 刘家腾
     * 2018-7-31
     * 用token获取user_id  并返回下次登录验证token
     *
     * @param      string   $token  The token
     *
     * @return     boolean  ( description_of_the_return_value )
     */
    public function token_user_id($token='')
    {
        $pool = PoolManager::getInstance()->getPool('App\Utility\MysqlPool2'); // 获取连接池对象
        $pool_db = $pool->getObj($timeOut = 0.1);
        $user = $pool_db->where('token',$token)->getOne("token");
        if( empty($user) ){
            self::ReturnErr(10000,$token,"token 错误！请重新登录！");
        }
        $token_value=self::token_hash( $user['user_id']);
        $uptime = date("Y-m-d H:i:s");
        $updata = Array (
            'token' => $token_value,
            'updatetime' => $uptime
        );
        $pool_db->where('token',$token)->update('token', $updata);
        self::ReturnOK($token_data,'token 换取成功！');
    }
    public function ReturnOK($data='',$msg='')
    {
        $pool->getObj($pool_db);
            $datas['code'] = 200;
            $datas['data'] = $data;
            $datas['msg'] = $msg;
            return $datas;
    }
    public function ReturnErr($code='',$data='',$msg='')
    {
        $pool->getObj($pool_db);
            $datas['code'] = $code;
            $datas['data'] = $data;
            $datas['msg'] = $msg;
            return $datas;
    }
}