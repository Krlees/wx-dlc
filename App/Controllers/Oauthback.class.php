<?php


namespace App\Controllers;


use App\Cache\MemcacheOperate;
use App\Responses\Response;
use EasyWeChat\Foundation\Application;
use Symfony\Component\HttpFoundation\Request;

class Oauthback
{
    /**
     * [获取到授权的用户信息并跳转回客户的targetUrl]
     * @Author: Krlee
     *
     */
    public function index()
    {
        var_dump(1);exit;
        $requset = new Request($_GET);
        $token = $requset->get('token') or Response::_instance()->callback(1005);

        $targetUrl = MemcacheOperate::getInstance()->get('target_url_'.$token);
        $options = MemcacheOperate::getInstance()->get($token);
        $app = new Application($options);

        $users = $app->oauth->user();


        $params = $users->toArray();
        $targetUrl = $targetUrl.'?'.http_build_query($params);

        header('location:'. $targetUrl);
    }
}