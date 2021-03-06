<?php
defined('APP_PATH') or exit('非法入口');

return [
    'db' => [
        'type' => 'mysql',     // 数据库类型
        'host' => 'localhost', // 服务器地址
        'name' => 'wxapi',        // 数据库名
        'user' => 'root',      // 用户名
        'pwd' => 'root',          // 密码
        'port' => '3306',      // 端口
        'prefix' => '',       // 数据库表前缀
    ],

    // Memcache 缓存设置
    'memcache_host' => 'localhost',
    'memcache_port' => '11211',

    'token' => [
        'expires_in' => 3600,  // token有效时间
    ],

    // 分页设置
    'pagesize' => 18,


    // 文件缓存设置
//    'file_cache_path' => STORAGE_PATH.'/data/',// 缓存路径设置 (仅对File方式缓存有效)
//    'file_cache_time' =>  0,      // 数据缓存有效期 0表示永久缓存

    'wx' => [
        'appid' => 'wxe64ffd96ea5834e8',
        'secret' => 'a95e1dabb2564a763db4875dcaeb1641',
        'mchid' => '1261433001',
        'key' => '68888888888888888888888888888887',
        'sslcert_path' => 'wx05f8c4c5ed16b174',
        'sslkey_path' => 'wx05f8c4c5ed16b174',
    ],


    // 返回码说明
    'code' => [
        '-1'   => '系统繁忙，此时请开发者稍候再试',
        '0'    => '请求成功',
        '1000' => '请求格式错误',
        '1001' => '请检查appid,secret,token参数',
        '1002' => '缺少secret参数',
        '1003' => '缺少签名参数',
        '1004' => '缺少必须参数',
        '1005' => 'token参数不正确或已失效，请检查token或者重新获取!',
        '1008' => '参数格式错误',
        '1010' => '签名失败',
        '1011' => '请求已过期',
        '2000' => '服务器出错',
        '2003' => '没有权限',
        '2004' => '非法入口',
        '20000' => '请求成功，数据数据操作失败',
        '20001' => '账号或密码错误',
        '20010' => '订单创建失败,请查看参数是否正确',
        '20011' => '该订单不存在',
        '20012' => '该订单状态不正确',
        '20013' => '该办理入办理的此业务已存在',
        '80001' => '其他错误'
    ]
];