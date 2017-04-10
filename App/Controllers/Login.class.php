<?php
namespace App\Controllers;

use App\Cache\MemcacheOperate;
use App\Responses\Response;
use EasyWeChat\Foundation\Application;
use Symfony\Component\HttpFoundation\Request;


class Login
{
    protected $request;
    protected $params = []; //微信参数

    public function __construct()
    {
        $this->request = new Request($_GET, $_POST);

        $appid = $this->request->query->get('appid');
        $secret = $this->request->query->get('secret');
        $token = $this->request->query->get('token');
        if (!isset($appid{0}) || !isset($secret{0}) || !isset($token{0})) {
            Response::_instance()->callback(1001);
        }

        $this->params = compact('appid','secret','token');
    }

    /**
     * [获取token，此token为微信唯一ID]
     * @Author: Krlee
     */
    public function getToken()
    {

        // 配置信息
        $options = [
            'debug'  => true,
            'app_id' => $this->params['appid'],
            'secret' => $this->params['secret'],
            'token'  => $this->params['token'],
            // 'aes_key' => null, // 可选
            'log' => [
                'level' => 'debug',
                'file'  => STORAGE_PATH . '/logs/easywechat.log', // XXX: 绝对路径！！！！
            ],
            //...
        ];

        $app = new Application($options);

        // 获取 access token 实例
        $accessToken = $app->access_token; // EasyWeChat\Core\AccessToken 实例
        $token = $accessToken->getToken(); // token 字符串
        if( !$token ){
            $token = $accessToken->getToken(true);
        }

        MemcacheOperate::getInstance()->set($token,$options,0,7190);
        Response::_instance()->callback(0,'获取成功',compact('token'));
    }


}