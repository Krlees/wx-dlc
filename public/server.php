<?php
require __DIR__ . '/../vendor/autoload.php';

use EasyWeChat\Foundation\Application;

$options = [
    'debug'  => true,
    'app_id' => 'wxe64ffd96ea5834e8',
    'secret' => 'a95e1dabb2564a763db4875dcaeb1641',
    'token'  => 'krlee',
    // 'aes_key' => null, // 可选
    'log' => [
        'level' => 'debug',
        'file'  => '/tmp/easywechat.log', // XXX: 绝对路径！！！！
    ],
    //...
];
$app = new Application($options);
$response = $app->server->serve();
// 将响应输出
$response->send();
