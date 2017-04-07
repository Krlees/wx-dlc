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
function get_sign($parameters,$appkey) {
    $parameters['appkey'] = $appkey;
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
    $result = sha1($string);

    return $result;
}

/**
 * 生成用户唯一令牌
 * @param $appid     
 * @param $appkey
 * @param $username
 * @param $pwd
 * @param $role
 * @return string
 */
function get_token($appid,$appkey,$username,$pwd, $role=1) {
    $salt = create_randomstr();
    $result = md5( md5($appid . $appkey . $role)  . $username . $salt . $pwd);
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

/***
 * 生成唯一编号
 * @return <string>
 */
function unique_sn() {
    return date('ymd').random(6);
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

/**
 * 接口错误日志
 * @param string $message 内容
 * @return bool
 */
function error_logs($code=0,$message=''){

//    $b = $GLOBALS['db']->insert('api_error_log',[
//        'code'     => $code,
//        'message'  => $message,
//        'add_time' => NOW_TIME,
//    ]);

//    return $b ? true : false;
}

function dd($data)
{
    echo "<pre>";
    print_r($data);
    echo  "</pre>";
    exit;
}

/**
 * 微信支付接口日志
 * @param <string> $info 日志内容
 * @param <mixed> $data 记录数据
 */
function pay_log($info, $data = '') {

    if($data) {
        if(is_array($data)) {
            $message = json_encode($data);
        } else {
            $message = $data;
        }
    }
    else {
        $data = '';
    }

    $GLOBALS['db']->insert('pay_log',[
        'title'    => $info,
        'message'  => $data,
        'add_time' => date('Y-m-d H:i:s'),
        'add_ip'   => $_SERVER["REMOTE_ADDR"] ? $_SERVER["REMOTE_ADDR"] : 0,
    ]);

    file_put_contents(ROOT_PATH.'/Cache/logs/wxpay/'.date('Y_m_d').'.log', 'Times: '.date('Y-m-d H:i:s').PHP_EOL.'Info: '.$info.PHP_EOL.'ADD_IP: '.$_SERVER["REMOTE_ADDR"].PHP_EOL.'data: '.$data.PHP_EOL.PHP_EOL,FILE_APPEND);

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

            //Response::_instance()->callbacl(80001,'短信发送失败',['code'=>$result->statusCode,'message'=>$result->statusMsg]);
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
