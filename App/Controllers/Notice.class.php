<?php
namespace App\Controllers;

use App\Responses\Response;

// +----------------------------------------------------------------------
// | 模板消息
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------
class Notice extends Common
{
    public function send()
    {
        $openid = $this->request->get('openid');
        $template_id = $this->request->get('template_id');
        $url = $this->request->get('url');
        $data = $this->request->get('data');
        if (!isset($openid{0}) || !isset($template_id{0})) {
            Response::_instance()->callback(1004);
        }

        $notice = $this->app->notice;
        $data = [
            'touser' => $openid,
            'template_id' => $template_id,
            'url' => $url,
            'data' => $data,
        ];

        $result = $notice->send($data);

        echo $result;
        exit;
    }
}