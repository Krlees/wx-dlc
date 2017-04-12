<?php
namespace App\Controllers;

// +----------------------------------------------------------------------
// | 微信Oauth2.0
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------
use App\Cache\MemcacheOperate;
use App\Responses\Response;
use EasyWeChat\Foundation\Application;

class Oauth extends Common
{

    /**
     * [发起授权]
     * @Author: Krlee
     *
     */
    public function snsapi()
    {

        $type = $this->request->get('type'); // 授权模式
        $targetUrl = $this->request->get('target_url');
        if (!isset($type{0}) || !isset($targetUrl{0})) {
            Response::_instance()->callback(1004);
        }

        $token = $this->request->get('token');

        MemcacheOperate::getInstance()->set('target_url_'.$token,$targetUrl,0,20);
        $options = MemcacheOperate::getInstance()->get($token);
        $options['oauth'] = [
            'scopes' => [$type],
            'callback' => "http://easywx.krlee.com/OauthBack/index?token=".$token,
        ];

        $app = new Application($options);
        $app->oauth->redirect()->send();
    }




}