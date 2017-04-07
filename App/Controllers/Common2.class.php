<?php
defined('ROOT_PATH') or exit(json_encode(['code'=>2004,'message'=>'非法入口']));

class Common {

    public $sign; //签名密钥
    public $access_token;
    public $api_data; // 存放API加密信息
    public $parameters = array();
    public $message;
    public $_request;
    public function __construct()
    {

        $this->_request = Response::_instance();
        $this->message = file_get_contents("php://input");
        $this->message = urldecode($this->message);
        if( strpos($this->message,"param=") === false ){
            $this->_request->callback(1008,$GLOBALS['cfg']['code']['1008'],$this->message);
        }
        else {
            file_put_contents(ROOT_PATH.'/Cache/logs/message/'.date('Ymd').'.txt', "Time：".date('Y-m-d H:i:s').PHP_EOL.$this->message.PHP_EOL, FILE_APPEND);
            $this->message = str_replace("param=","",$this->message);
        }

        // 【所有参数】  ==> 报文数据 <数组格式> Array
        $this->parameters = json_decode($this->message,true);

        $this->parameters['access_token'] or $this->_request->callback(1005,$GLOBALS['cfg']['code']['1005']);
        $this->parameters['sign']         or $this->_request->callback(1003,$GLOBALS['cfg']['code']['1003']);

        if( !isset($this->parameters['salt']{0}) || !$this->parameters['timestamp'] ){
            $this->_request->callback(1004,$GLOBALS['cfg']['code']['1004']);
        }
        elseif ( $this->parameters['timestamp']+30 < NOW_TIME ){
            $this->_request->callback(1011,$GLOBALS['cfg']['code']['1011'],NOW_TIME);
        }
            

        $this->check();
        $this->check_sign();
    }

    /**
     * 认证token【令牌】
     */
    public function check() {
        $this->api_data = $GLOBALS['db']->get('api_token','*',[
            'AND' => [
                'access_token' => $this->parameters['access_token']
            ]
        ]) or $this->_request->callback(1007,$GLOBALS['cfg']['code']['1007']);

        // 判断是否过时
        if( $this->api_data['times']+$GLOBALS['cfg']['token']['expires_in'] < NOW_TIME ){
            $GLOBALS['db']->delete('api_token',['access_token' => $this->parameters['access_token']]);

            $this->_request->callback(1006,$GLOBALS['cfg']['code']['1006'],$this->parameters);
        }

    }

    /**
     * 检测签名算法
     * @param <array>  $parameters 参数数据
     * @param <string> $sign       签名字符串
     * @return bool
     */
    public function check_sign() {
        $sign_param = $this->parameters;
        unset($sign_param['sign']);
        $signtura = get_sign($sign_param,$this->api_data['appkey']); // 服务器端sign
        if( $signtura != $this->parameters['sign'] ){
            $this->_request->callback(1010,$GLOBALS['cfg']['code']['1010']);
        }

        return true;
    }


}
