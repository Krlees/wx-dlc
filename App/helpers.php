<?php


/**
 * 对用户的密码进行加密
 * @param $password  加密的密码
 * @param $encrypt   传入加密串，在修改密码时做认证
 * @return array/password
 */
function password($password, $encrypt='') {

    $pwd = array();
    $pwd['encrypt']  = $encrypt ? $encrypt : create_randomstr();
    $pwd['password'] = md5( md5( trim($password) ).$pwd['encrypt']);

    return $encrypt ? $pwd['password'] : $pwd;
}

/**
 * 根据PHP各种类型变量生成唯一标识号
 * @param mixed $mix 变量
 * @return string
 */
function to_guid_string($mix) {
    if (is_object($mix)) {
        return spl_object_hash($mix);
    } elseif (is_resource($mix)) {
        $mix = get_resource_type($mix) . strval($mix);
    } else {
        $mix = serialize($mix);
    }
    return md5($mix);
}

/**
 * 【签名加密】 排序并加密
 * @param $parameters array
 * @return string
 */
function create_sign($parameters) {

    // 签名步骤一：按字典序排序参数
    ksort($parameters);
    $buff = $query = '';
    foreach($parameters as $k => $v) {
        $buff .= $k.'='.$v.'&';
    }
    if(strlen($buff) > 0) {
        $string = substr($buff, 0, strlen($buff) - 1);
    }

    // 签名步骤二：sha1加密
    $result = sha1($string.'&dls808krlee');

    return $result;
}


/**
 * 生成随机字符串
 * @param string $lenth 长度
 * @return string 字符串
 */
function create_randomstr($lenth = 6) {
    return random($lenth, '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');
}

/**
 * 产生随机字符串
 * @param	int		$length  输出长度
 * @param	string	 $chars   可选的 ，默认为 0123456789
 * @return   string	 字符串
 */
function random($length, $chars = '0123456789') {
    $hash = '';
    $max = strlen($chars) - 1;
    for($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}

/**
 * 获取配置
 * @param <string> $classify 分类名
 * @param <string> $name 配置名
 * @return <string|array>
 */
function get_setting($classify = '', $key = '') {
    $setting = MemcacheOperate::getInstance()->get('setting');
    if( !$setting ) {
        $setting = array();
        $data = $GLOBALS['db']->select('setting','*');
        foreach($data as $item) {
            $setting[$item['classify']][$item['key']] = $item['value'];
        }
        MemcacheOperate::getInstance()->set('setting',$setting);
    }

    return $classify ? ($key ? $setting[$classify][$key] : $setting[$classify]) : $setting;
}

/**
 * 保存配置
 * @param <string> $classify 分类名
 * @param <string> $name 配置键
 * @param <mixed> $value 配置值
 * @return <bool>
 */
function set_setting($classify, $key, $value) {

    $count = $GLOBALS['db']->has('setting',[
        'AND' => [
            'classify' => $classify,
            'key' => $key,
        ]
    ]);
    if($count) {
        $affected = $GLOBALS['db']->update('setting',[
            'value' => $value
        ],[
            'AND' => [
                'classify' => $classify,
                'key' => $key
            ]
        ]);
    } else {
        $affected = $GLOBALS['db']->insert('setting',[
            'value' => $value,
            'classify' => $classify,
            'key' => $key
        ]);
    }
    return ($affected===false) ? false : true;
}

function dd($data)
{
    echo "<pre>";
    print_r($data);
    echo  "</pre>";
    exit;
}



/**
 * 验证手机号码合法性
 * @param $phone  手机号码
 * @return bool
 */
function checkMobileValid($phone)
{
    if(preg_match('/^1[34578]{1}\d{9}$/',$phone)){
        return true;
    }else{
        return false;
    }
}

/**
 * 获取数值中的key值
 *
 * @param  \ArrayAccess|array  $array
 * @param  string  $key
 * @param  mixed   $default
 * @return mixed
 */
function array_get($array, $key, $default = null)
{

    if (!isset($array[$key])) {
        if( !is_null($default)){
            return $default;
        }

        return null;
    }

    return $array[$key];

}

/**
 * cURL请求接口数据 ...
 * @param $url
 * @param $data
 * @return json
 */
function curl_do($url,$data = null){
    $curl = curl_init();
    // 设置超时
    curl_setopt($curl, CURLOP_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_HEADER, 0); //不取得返回头信息
    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;

}

/**
 * [多维数组排序]
 * @Author: Krlee
 * @param $array
 * @param $key
 * @return null
 */
function array_sort($array, $key){
    if(is_array($array)){
        $key_array = null;
        $new_array = null;
        for( $i = 0; $i < count( $array ); $i++ ){
            $key_array[$array[$i][$key]] = $i;
        }
        ksort($key_array);
        $j = 0;
        foreach($key_array as $k => $v){
            $new_array[$j] = $array[$v];
            $j++;
        }
        unset($key_array);
        return $new_array;
    }else{
        return $array;
    }
}

function JsonToArr($json) {
    $newJson = json_decode($json,true);
    if (json_last_error() == JSON_ERROR_NONE){
        return $json;
    }

    return $newJson;
}


//**************************************举例说明***********************************************************************
//*假设您用测试Demo的APP ID，则需使用默认模板ID 1，发送手机号是13800000000，传入参数为6532和5，则调用方式为           *
//*result = sendTemplateSMS("13800000000" ,array('6532','5'),"1");																		  *
//*则13800000000手机号收到的短信内容是：【云通讯】您使用的是云通讯短信模板，您的验证码是6532，请于5分钟内正确输入     *
//*********************************************************************************************************************
/**
 * 发送模板短信【云通信短信接口】
 *
 * @param $to       手机号码集合,用英文逗号分开
 * @param $datas    内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
 * @param $tpl_key 模板标识名
 *
 * @return bool
 */
function sendSMS($to, $datas, $tpl_key='')
{
    if( !checkMobileValid($to) || !is_array($datas) || !$tpl_key ){
        return false;
    }

    $setting = json_decode(get_setting('sms','base'),true);
    $tpl     = json_decode(get_setting('sms','template'),true);
    $tempId  = $tpl[$tpl_key]; //模板Id,测试应用和未上线应用使用测试模板请填写1，正式应用上线后填写已申请审核通过的模板ID

    if( !$tempId ){
        return false;
    }

    include VENDOR_PATH.'Sms/CCPRestSmsSDK.php';

    // 初始化REST SDK
    $sms_obj = new REST($setting['appId'],$setting['accountSid'], $setting['accountToken']);
    
    // 发送模板短信
    $result = $sms_obj->sendTemplateSMS($to,$datas,$tempId);
    if($result == NULL ) {
        return false;
    }
    else {
        if($result->statusCode!=0) {
            $GLOBALS['db']->insert('sms_log',[
                'phone'     => $to,
                'tpl_id'    => $tempId,
                'send_time' => $_SERVER['REQUEST_TIME'] ? $_SERVER['REQUEST_TIME'] : time(),
                'msg_id'    => '',
                'flag'      => $tpl_key,
                'status'    => 0
            ]);

            return false;

            //Responses::_instance()->callbacl(80001,'短信发送失败',['code'=>$result->statusCode,'message'=>$result->statusMsg]);
        }
        else{

            // 获取返回数据
            $message = $result->TemplateSMS;

            // 获取短信的类型
            $tpl_key = '';
            $template = json_decode(get_setting('sms','template'),true);
            if( is_array($template) ){
                $key = array_search($tempId, $template);
                $tpl_key = $key ? $key : '';
            }

            // 记录下发日志
            $GLOBALS['db']->insert('sms_log',[
                'phone'     => $to,
                'tpl_id'    => $tempId,
                'add_time' => strtotime($message->dateCreated),
                'msg_id'    => $message->smsMessageSid,
                'flag'      => $tpl_key,
                'status'    => 1
            ]);

            return true;
            //TODO 添加成功处理逻辑
        }

    }

}


