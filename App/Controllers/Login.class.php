<?php
namespace App;
use Symfony\Component\HttpFoundation\Request;

class Login
{
    /**
     * [获取token，此token为微信唯一ID]
     * @Author: Krlee
     */
    public function getToken()
    {
        $request = new Request();
        echo $request->get('a');
    }
}