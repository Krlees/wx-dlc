<?php
define('APP_PATH', __DIR__ . '/../App');
define('STORAGE_PATH', __DIR__ . '/../Storage');
define('NOW_TIME', $_SERVER['REQUEST_TIME'] > 0 ? $_SERVER['REQUEST_TIME'] : time());
define('METHOD', strtolower($_SERVER['REQUEST_METHOD']));

require_once APP_PATH . '/Responses/Response.class.php';
require_once APP_PATH . '/Cache/MemcacheOperate.class.php';

require __DIR__ . '/../vendor/autoload.php';

use Medoo\Medoo;
use App\Cache\MemcacheOperate;

MemcacheOperate::getInstance()->flush();
// 判断并缓存配置信息
try {
    $cache = MemcacheOperate::getInstance();
} catch (Exception $e) {
    Response::_instance()->callback(80001, 'Memcache服务连接失败');
}


$_CFG = $cache->get('config');
if (!$_CFG) {
    $_CFG = require_once(__DIR__ . '/../config.php');
    $cache->set('config', $_CFG, MEMCACHE_COMPRESSED, 0);
}
$GLOBALS['cfg'] = $_CFG; //把配置保存到全局变量

// 初始化配置
$GLOBALS['db'] = new medoo([
    'database_type' => $GLOBALS['cfg']['db']['type'],
    'database_name' => $GLOBALS['cfg']['db']['name'],
    'server' => $GLOBALS['cfg']['db']['host'],
    'username' => $GLOBALS['cfg']['db']['user'],
    'password' => $GLOBALS['cfg']['db']['pwd'],
    'charset' => 'utf8',
    'prefix' => $GLOBALS['cfg']['db']['prefix'],
]);
if (!$GLOBALS['db']) {
    Response::_instance()->callback(80001, '数据库连接失败');
}

require_once __DIR__ . '/../autoload.php';
require_once __DIR__ . '/../route.php';


