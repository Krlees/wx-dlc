<?php
namespace App\Controllers;

use App\Cache\MemcacheOperate;
use App\Responses\Response;
use EasyWeChat\Foundation\Application;
use Symfony\Component\HttpFoundation\Request;


class Common
{
    protected $app; // easywechat插件实例化对象
    protected $token; // 微信access_token
    protected $request;

    public function __construct()
    {
        $this->request = new Request($_GET, $_POST);

        // 检测访问源的合法性
        //$this->__checkSign($this->request);

        // 获取该公众号的配置
        $this->token = $this->request->get('token') or Response::_instance()->callback(1005);
        $options = MemcacheOperate::getInstance()->get($this->token);
        if (!$options) {
            Response::_instance()->callback(1005);
        }

        // 实例化EasyWeChat对象
        $this->app = new Application($options);
    }

    /**
     * [检测访问源的合法性]
     * @Author: Krlee
     * @param $request
     */
    private function __checkSign($request)
    {
        $timestamp = $request->query->get('timestamp');
        $salt = $request->query->get('salt');
        $sign = $request->query->get('sign');

        if (!$timestamp || !$salt || !$salt) {
            Response::_instance()->callback(1004);
        }
        elseif ($timestamp < time() + 10) {
            Response::_instance()->callback(1011);
        }

    }

    /**
     * [判断必选参数]
     * @Author: Krlee
     *
     */
    protected function checkParam(array $parmeters)
    {
        foreach ($parmeters as $k=>$v){
            if(!$v){
                Response::_instance()->callback(1004);
            }
        }
    }

    /**
     * [适用于微信结果直接返回列表的情况]
     * @Author: Krlee
     *
     */
    protected function responseList($result)
    {
        if( !isset($result->errcode)){
            Response::_instance()->callback(0,'',JsonToArr($result));
        }
        else {
            echo $result;
            exit;
        }

    }


}