<?php
require_once APP_PATH . '/Responses/Response.class.php';

$_DocumentPath = $_SERVER['DOCUMENT_ROOT'];
$_RequestUri = $_SERVER['REQUEST_URI'];
$_UrlPath = $_RequestUri;
$_FilePath = __FILE__;
$_AppPath = str_replace($_DocumentPath, '', $_FilePath);    //==>\router\index.php
$_AppPathArr = explode(DIRECTORY_SEPARATOR, $_AppPath);

for ($i = 0; $i < count($_AppPathArr); $i++) {
    $p = $_AppPathArr[$i];
    if ($p) {
        $_UrlPath = preg_replace('/^\/' . $p . '\//', '/', $_UrlPath, 1);
    }
}

$_UrlPath = preg_replace('/^\//', '', $_UrlPath, 1);

$_AppPathArr = explode("/", $_UrlPath);
$_AppPathArr_Count = count($_AppPathArr);
$arr_url = [
    'controller' => 'sharexie/test',
    'method' => 'index',
    'parms' => []
];
if($_AppPathArr_Count == 1){
    $_AppPathArr[1] = $_AppPathArr[0];
    $_AppPathArr[0] = 'Index';
}

$arr_url['controller'] = $_AppPathArr[0];
$arr_url['method'] = $_AppPathArr[1];


if ($_AppPathArr_Count > 2 && $_AppPathArr_Count % 2 != 0) {
    \App\Responses\Response::_instance()->callback(1000, 'url参数错误');
} else {
    for ($i = 2; $i <= $_AppPathArr_Count; $i += 2) {
        $arr_temp_hash = array(array_get($_AppPathArr, $i) => array_get($_AppPathArr, $i + 1));
        $arr_url['parms'] = array_merge($arr_url['parms'], $arr_temp_hash);
    }
}

$module_name = $arr_url['controller'] ? ucwords(strtolower($arr_url['controller'])) : Response::_instance()->callback(1000, 'url参数错误');
$module_file = APP_PATH . '/controllers/' . $module_name . '.class.php';
$offset = strpos($arr_url['method'], '?');
$method_name = ($offset === false) ? $arr_url['method'] : substr($arr_url['method'], 0, $offset);

if (!file_exists($module_file)) {
    \App\Responses\Response::_instance()->callback(1000, 'url参数错误');
}

require_once APP_PATH . '/Controllers/Common.class.php'; //公共处理类库
require_once $module_file;

$module_name = 'App\\Controllers\\'.$module_name;
$obj_module = new $module_name();

if (method_exists($module_name, $method_name)) {
    if (is_callable(array($obj_module, $method_name))) {
        $obj_module->$method_name($module_name, $arr_url['parms']);
        //$obj_module -> printResult();
    }
} else {
    \App\Responses\Response::_instance()->callback(1000, 'url参数错误');
}
