<?php

/**
 * singletonPattern.php
 *
 * 单例模式
 *
 * Copyright (c) 2015 http://blog.csdn.net/CleverCode
 *
 * modification history:
 * --------------------
 * 2015/6/8, by CleverCode, Create
 *
 */

// 加载Memcache
include_once('MemcacheOperate.class.php');

/*
 * 客户端类
 * 让客户端和业务逻辑尽可能的分离，降低客户端和业务逻辑算法的耦合，
 * 使业务逻辑的算法更具有可移植性
 */
class Cache{

    /**
     * 初始化配置文件
     * @return object
     */
    public function __construct(){

        $cache = MemcacheOperate::getInstance();
        return $cache;

    }

}



?>