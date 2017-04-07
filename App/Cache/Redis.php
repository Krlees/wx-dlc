<?php

/**
 * RedisOperate.php
 *
 * 单例模式设计Redis操作类
 *
 * Copyright (c) 2015 http://blog.csdn.net/CleverCode
 *
 * modification history:
 * --------------------
 * 2015/6/8, by CleverCode, Create
 *
 */
class RedisOperate extends Redis{
    const host = "127.0.0.1";
    const port = 6379;

    // 实例
    protected static $_instance = null;

    /**
     * Singleton instance（获取自己的实例）
     *
     * @return RedisOperate
     */
    public static function getInstance(){
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->connect(self::host, self::port);
        }
        return self::$_instance;
    }
}

