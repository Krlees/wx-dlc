<?php
namespace App\Controllers;

use App\Responses\Response;

// +----------------------------------------------------------------------
// | 微信用户
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------
class User extends Common
{
    protected $userService;

    public function __construct()
    {
        parent::__construct();
        $this->userService = $this->app->user;
    }

    /**
     * [拉取单个用户的信息]
     * @Author: Krlee
     *
     * @param $openId
     */
    public function get()
    {
        $openId = $this->request->get('open_id') or Response::_instance()->callback(1004);
        $result = $this->userService->get($openId);

        echo $result;
        exit;
    }

    /**
     * [拉取多个用户的信息]
     * @Author: Krlee
     *
     * @param array $openIds
     */
    public function batchGet()
    {
        $openIds = $this->request->get('open_ids') or Response::_instance()->callback(1004);
        $result = $this->userService->batchGet($openIds);
    }

    /**
     * [拉取用户列表]
     * @Author: Krlee
     *
     * @param null $nextOpenId
     */
    public function lists()
    {
        $nextOpenId = $this->request->get('nextOpenId') or Response::_instance()->callback(1004);
        $result = $this->userService->lists();
        if (!isset($result->errcode)) {
            Response::_instance()->callback(0, '', JsonToArr($result));
        }

        echo $result;
        exit;
    }

    /**
     * [修改用户备注]
     * @Author: Krlee
     *
     * @param $openId
     * @param $remark
     */
    public function updateRemark()
    {
        $openId = $this->request->get('open_id') or Response::_instance()->callback(1004);
        $remark = $this->request->get('remark') or Response::_instance()->callback(1004);
        $result = $this->userService->remark($openId, $remark);

        echo $result;
        exit;
    }

    /**
     * [获取用户所在的用户组]
     * @Author: Krlee
     *
     * @param $openId
     */
    public function group()
    {
        $openId = $this->request->get('open_id') or Response::_instance()->callback(1004);
        $result = $this->group($openId);

        echo $result;
        exit;
    }

    /**
     * [获取黑名单列表]
     * @Author: Krlee
     *
     * @param null $beginOpenId
     */
    public function getBlackList()
    {
        $beginOpenId = $this->request->get('begin_open_id') or Response::_instance()->callback(1004);
        $result = $this->userService->blacklist($beginOpenId);

        echo $result;
        exit;
    }

    /**
     * [拉黑用户]
     * @Author: Krlee
     *
     * @param array $openidList
     */
    public function batchBlock()
    {
        $openidList = $this->request->get('openidList') or Response::_instance()->callback(1004);
        $result = $this->userService->batchBlock($openidList);

        echo $result;
        exit;
    }

    /**
     * [取消拉黑用户]
     * @Author: Krlee
     *
     * @param array $openidList
     */
    public function batchUnblock(array $openidList)
    {
        $openidList = $this->request->get('openid_list') or Response::_instance()->callback(1004);
        $result = $this->userService->batchUnblock($openidList);

        echo $result;
        exit;
    }


}