<?php
namespace App\Responses;

/**
 * 定义API抽象类
 */
abstract class Response
{

    const JSON = 'Json';
    const XML = 'Xml';
    const ARR = 'Array';
    static $_instance;

    /**
     * 定义工厂方法
     * param string $type 返回数据类型
     */
    public static function _instance($type = self::JSON)
    {
        $type = isset($_GET['format']) ? $_GET['format'] : $type;
        $resultClass = ucwords($type); //首字母大写
        if (is_null(self::$_instance) || isset (self::$_instance)) {
            //require_once $type.'.class.php';
            $cla = 'App\\Responses\\'.$type;
            self::$_instance = new $cla();
        }
        return self::$_instance;

    }

    abstract function callback($code, $message, $data);
}