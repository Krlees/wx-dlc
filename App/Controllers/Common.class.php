<?php
namespace App\Controllers;
use EasyWeChat\Foundation\Application;


class Common
{
    protected $app; // easywechat插件实例化对象

    public function __construct()
    {

        $this->app = new Application($options);
    }


}