<?php
/**
 * MemcacheOperate.php
 *
 * 单例模式设计Memcache操作类
 *
 * Copyright (c) 2015 http://blog.csdn.net/CleverCode
 *
 * modification history:
 * --------------------
 * 2015/6/8, by CleverCode, Create
 *
 */
class MemcacheOperate extends Memcache{
    const host = "127.0.0.1";
    const port = 11211;

    protected static $_instance = null;     // 实例

    /**
     * Singleton instance（获取自己的实例）
     * @return MemcacheOperate
     */
    public static function getInstance(){
        if (null === self::$_instance) {
            self::$_instance = new self();
            try{
                self::$_instance->addServer(self::host, self::port);
            } catch (Exception $e) {
                file_put_contents('./Cache/logs/'.date('y-m-d') . '.txt' , $e->getMessage());
                return;
            }

        }
        return self::$_instance;
    }
}
