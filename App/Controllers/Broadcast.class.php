<?php
namespace App\Controllers;

use App\Responses\Response;

// +----------------------------------------------------------------------
// | 群发消息模块
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------
class Broadcast extends Common
{
    protected $broadcast;
    private $to;  // 用户组或openid用户集合 array

    public function __construct()
    {
        parent::__construct();
        $this->broadcast = $this->app->broadcast;
        $this->to = $this->request->get('to') or Response::_instance()->callback(1004);
    }

    /**
     * [群发文本消息给指定的用户或组]
     * @Author: Krlee
     */
    public function sendText()
    {
        $message = $this->request->get('message');
        $result = $this->broadcast->sendText($message, $this->to);

        echo $result;
        exit;
    }

    /**
     * [群发图文消息给指定的用户或组]
     * @Author: Krlee
     */
    public function sendNews()
    {
        $mediaId = $this->request->get('media_id');
        $result = $this->broadcast->sendNews($mediaId, $this->to);

        echo $result;
        exit;
    }

    /**
     * [群发语音消息给指定的用户或组]
     * @Author: Krlee
     */
    public function sendVoice()
    {
        $mediaId = $this->request->get('media_id');
        $result = $this->broadcast->sendVoice($mediaId, $this->to);

        echo $result;
        exit;
    }

    /**
     * [群发图片消息给指定的用户或组]
     * @Author: Krlee
     */
    public function sendImage()
    {
        $mediaId = $this->request->get('media_id');
        $result = $this->broadcast->sendImage($mediaId, $this->to);

        echo $result;
        exit;
    }

    /**
     * [群发视频消息给指定的用户或组]
     * @Author: Krlee
     * @param array $message  => 如：['MEDIA_ID', 'TITLE', 'DESCRIPTION']
     */
    public function sendVideo()
    {
        $message = $this->request->get('message');
        $result = $this->broadcast->sendVideo($message, $this->to);

        echo $result;
        exit;
    }

    /**
     * [群发卡券消息给指定的用户或组]
     * @Author: Krlee
     */
    public function sendCard()
    {
        $mediaId = $this->request->get('media_id');
        $result = $this->broadcast->sendCard($mediaId, $this->to);

        echo $result;
        exit;
    }

    /**
     * [群发预览文本消息给指定的用户或组]
     * @Author: Krlee
     */
    public function sendPreviewText()
    {
        $message = $this->request->get('message');
        $result = $this->broadcast->previewText($message, $this->to);

        echo $result;
        exit;
    }

    /**
     * [群发预览图文消息给指定的用户或组]
     * @Author: Krlee
     */
    public function sendPreviewNews()
    {
        $mediaId = $this->request->get('media_id');
        $result = $this->broadcast->previewNews($mediaId, $this->to);

        echo $result;
        exit;
    }

    /**
     * [群发预览语音消息给指定的用户或组]
     * @Author: Krlee
     */
    public function sendPreviewVoice()
    {
        $mediaId = $this->request->get('media_id');
        $result = $this->broadcast->previewVoice($mediaId, $this->to);

        echo $result;
        exit;
    }

    /**
     * [群发预览图片消息给指定的用户或组]
     * @Author: Krlee
     */
    public function sendPreviewImage()
    {
        $mediaId = $this->request->get('media_id');
        $result = $this->broadcast->previewImage($mediaId, $this->to);

        echo $result;
        exit;
    }

    /**
     * [群发预览视频消息给指定的用户或组]
     * @Author: Krlee
     * @param array $message  => 如：['MEDIA_ID', 'TITLE', 'DESCRIPTION']
     */
    public function sendPreviewVideo()
    {
        $message = $this->request->get('message');
        $result = $this->broadcast->previewVideo($message, $this->to);

        echo $result;
        exit;
    }

    /**
     * [群发预览卡券消息给指定的用户或组]
     * @Author: Krlee
     */
    public function sendPreviewCard()
    {
        $mediaId = $this->request->get('media_id');
        $result = $this->broadcast->previewCard($mediaId, $this->to);

        echo $result;
        exit;
    }

    /**
     * [删除群发消息任务]
     * @Author: Krlee
     * @param $msgId
     */
    public function delete()
    {
        $msgId = $this->request->get('media_id');
        $result = $this->broadcast->delete($msgId);

        echo $result;
        exit;
    }

}
