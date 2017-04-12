<?php
namespace App\Controllers;

use App\Cache\MemcacheOperate;
use App\Responses\Response;
use EasyWeChat\Foundation\Application;
use Symfony\Component\HttpFoundation\Request;

// +----------------------------------------------------------------------
// | 微信中间库登陆获取微信Access_Token
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------
class Login
{
    protected $request;
    protected $wx_config = []; //微信参数

    public function __construct()
    {
        $this->request = new Request($_GET, $_POST);

//        $wx_config = $this->request->query->get('wx_config');
//        $wx_config = \GuzzleHttp\json_decode($wx_config);
//        if (!isset($wx_config['appid']) || !isset($wx_config['secret'])) {
//            Response::_instance()->callback(1001);
//        }
        $wx_config = [
            'appid'  => 'wxe64ffd96ea5834e8',
            'secret' => 'a95e1dabb2564a763db4875dcaeb1641',
            'token' => 'krlee',
            'aes_key' => '',
            'payment' => [
                'merchant_id'        => '1261433001',
                'key'                => '',
                'cert_path'          => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
                'key_path'           => 'path/to/your/key',      // XXX: 绝对路径！！！！
                'notify_url'         => '默认的订单回调地址',       // 你也可以在下单时单独设置来想覆盖它
                // 'device_info'     => '013467007045764',
                // 'sub_app_id'      => '',
                // 'sub_merchant_id' => '',
                // ...
            ],
        ];

        $this->wx_config = $wx_config;
    }

    /**
     * [获取token，此token为微信唯一ID]
     * @Author: Krlee
     */
    public function getToken()
    {

        // 配置信息
        $options = [
            'debug' => true,
            'app_id' => $this->wx_config['appid'],
            'secret' => $this->wx_config['secret'],
            'token'  => $this->wx_config['token'], //微信验证的token，跟access_token不一样

            'aes_key' => $this->wx_config['aes_key'] ?: '', // 可选
            'log' => [
                'level' => 'debug',
                'permission' => 0777,
                'file' => STORAGE_PATH . '/logs/easywx/'.date('y-m-d').'.txt', // XXX: 绝对路径！！！！
            ],


            'payment' => [
                'merchant_id'        => $this->wx_config['payment']['merchant_id'],
                'key'                => $this->wx_config['payment']['key'],
                'cert_path'          => ROOT_PATH.'/cert/'.$this->wx_config['appid'].'/cert.pem', // XXX: 绝对路径！！！！
                'key_path'           => ROOT_PATH.'/cert/'.$this->wx_config['appid'].'/key.pem',  // XXX: 绝对路径！！！！
                'notify_url'         => $this->wx_config['payment']['notify_url'],       // 你也可以在下单时单独设置来想覆盖它
                // 'device_info'     => '013467007045764',
                // 'sub_app_id'      => '',
                // 'sub_merchant_id' => '',
                // ...
            ],

            //...
        ];

        $app = new Application($options);

        // 获取 access token 实例
        $accessToken = $app->access_token; // EasyWeChat\Core\AccessToken 实例
        $token = $accessToken->getToken(); // token 字符串
        if (!$token) {
            $token = $accessToken->getToken(true);
        }

        MemcacheOperate::getInstance()->set($token, $options, 0, 7190);
        Response::_instance()->callback(0, '获取成功', compact('token'));
    }


}