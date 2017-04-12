<?php


namespace App\Controllers;


use App\Responses\Response;

class Qrcode extends Common
{

    protected $qrcode;

    public function __construct()
    {
        parent::__construct();
        $this->qrcode = $this->app->qrcode;
    }

    /**
     * 生成临时二维码
     * @param 
     * @return mixed
     */
    public function temporary()
    {
        $scene_id = $this->request->get('scene_id',mt_rand(10000,99999));
        $expireSeconds = $this->request->get('expire', 6 * 24 * 3600);

        $result = $this->qrcode->temporary($scene_id, $expireSeconds);

        $this->responseList($result);
    }

    /**
     * 生成永久二维码
     * @param 
     * @return mixed
     */
    public function forever()
    {
        $scene_id = $this->request->get('scene_id',mt_rand(10000,99999));

        $result = $this->qrcode->forever($scene_id);

        $this->responseList($result);
    }

    /**
     * 获取二维码网址
     * @param $ticket
     */
    public function url($ticket)
    {
        $ticket = $this->request->get('ticket') or Response::_instance()->callback(1004);

        $result = $this->qrcode->url($ticket);

        $this->responseList($result);
    }

    /**
     * 创建卡券二维码
     * @param $card   卡卷ID
     */
    public function card($card)
    {
        $card = $this->request->get('card') or Response::_instance()->callback(1004);

        $result = $this->qrcode->card($card);

        $this->responseList($result);
    }

}