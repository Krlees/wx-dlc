<?php
namespace App\Controllers;
use EasyWeChat\Foundation\Application;

// +----------------------------------------------------------------------
// | 微信服务器入口
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------
class Wx
{

    protected $app;
    public function index()
    {

        $options = [
            'debug'  => true,
            'app_id' => 'wxe64ffd96ea5834e8',
            'secret' => 'a95e1dabb2564a763db4875dcaeb1641',
            'token'  => 'krlee',
            // 'aes_key' => null, // 可选
            'log' => [
                'level' => 'debug',
                'file'  => '/tmp/easywechat.log', // XXX: 绝对路径！！！！
            ],
            //...
        ];
        $this->app = new Application($options);

        $wechatServer = $this->app->server; // 服务端
        $userApi      = $this->app->user;   // 用户

        $wechatServer->setMessageHandler(function ($message) use ($userApi){
            $nickname = $userApi->get($message->FromUserName)->nickname;
            switch ($message->MsgType) {
                case 'event':
                    # 事件消息...
                    switch ( strtolower($message->Event) )
                    {
                        case 'subscribe':
                            if( $message->EventKey )
                            {
                                return '扫描二维码关注-'.$nickname;
                            }
                            return '谢谢你的关注-'.$nickname;
                            break;

                        case 'unsubscribe':
                            return '取消关注-'.$userApi->get($message->ToUserName)->nickname;
                            break;

                        case 'location':
                            return '上报地理位置:  纬度-经度-精度'.$message->Latitude.','.$message->Longitude.','.$message->Precision;
                            break;

                        case 'click':
                            return '自定义菜单事件'.$message->EventKey;
                            break;
                    }

                    break;
                case 'text':
                    return '文字-'.$nickname;
                    break;
                case 'image':
                    return '图片-'.$nickname;
                    break;
                case 'voice':
                    return '语音-'.$nickname;
                    break;
                case 'video':
                    return '视频-'.$nickname;
                    break;
                case 'location':
                    return '坐标-'.$nickname;
                    break;
                case 'link':
                    # 链接消息...
                    return '链接URL-'.$nickname;
                    break;
                // ... 其它消息
                default:
                    # code... 转接客服
                    //return new Message\Transfer();
                    break;
            }
        });

        $response = $wechatServer->serve();
        $response->send();


    }

}