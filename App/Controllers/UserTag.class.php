<?php
namespace App\Controllers;

// +----------------------------------------------------------------------
// | 微信用户标签
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------
use App\Responses\Response;

class UserTag extends Common
{
    protected $userTag;

    public function __construct()
    {
        parent::__construct();
        $this->userTag = $this->app->user_tag;
    }

    public function lists()
    {
        $result = $this->userTag->lists();
        if (!isset($result->errcode)) {
            Response::_instance()->callback(1004);
        }

        echo $result;
        exit;
    }

    public function create()
    {
        $name = $this->request->get('name');
        $result = $this->userTag->create($name);

        echo $result;
        exit;
    }

    public function update()
    {
        $tagId = $this->request->get('tagId');
        $name = $this->request->get('name');

        $result = $this->userTag->update($tagId, $name);

        echo $result;
        exit;
    }

    public function delete()
    {
        $tagId = $this->request->get('tagId');

        $result = $this->userTag->delete($tagId);

        echo $result;
        exit;
    }

    /**
     * [获取指定【openid】用户身上的标签]
     * @Author: Krlee
     *
     */
    public function userTags()
    {
        $openId = $this->request->get('open_id') or Response::_instance()->callback(1004);

        $result = $this->userTag->userTags($openId);
        if (!isset($result->errcode)) {
            Response::_instance()->callback(0, '', JsonToArr($result));
        }

        echo $result;
        exit;
    }

    /**
     * [获取标签下的用户列表]
     * @Author: Krlee
     *
     */
    public function getUserOfTag()
    {
        $tagId = $this->request->get('tagId') or Response::_instance()->callback(1004);
        $nextOpenId = $this->request->get('nextOpenId');

        $result = $this->userTag->usersOfTag($tagId, $nextOpenId);
        if (!isset($result->errcode)) {
            Response::_instance()->callback(0, '', JsonToArr($result));
        }

        echo $result;
        exit;
    }

    /**
     * [批量为用户打标签]
     * @Author: Krlee
     *
     */
    public function batchTagUsers()
    {
        $openIds = $this->request->get('open_ids');
        if (!$openIds || !is_array($openIds)) {
            Response::_instance()->callback(1004);
        }

        $tagId = $this->request->get('tagId') or Response::_instance()->callback(1004);

        $result = $this->userTag->batchTagUsers($openIds, $tagId);

        echo $result;
        exit;
    }

    /**
     * [批量为用户取消标签]
     * @Author: Krlee
     *
     */
    public function batchUntagUsers()
    {
        $openIds = $this->request->get('open_ids');
        if (!$openIds || !is_array($openIds)) {
            Response::_instance()->callback(1004);
        }
        $tagId = $this->request->get('tagId') or Response::_instance()->callback(1004);

        $result = $this->userTag->batchTagUsers($openIds, $tagId);

        echo $result;
        exit;
    }

}