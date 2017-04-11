<?php
namespace App\Controllers;

use App\Responses\Response;

// +----------------------------------------------------------------------
// | 微信用户组
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------
class UserGroup extends Common
{
    protected $group;

    public function __construct()
    {
        parent::__construct();
        $this->group = $this->app->user_group;
    }

    /**
     * [获取所有分组]
     * @Author: Krlee
     *
     */
    public function lists()
    {
        $result = $this->group->lists();

        if (!isset($result->errcode)) {
            Response::_instance()->callback(0, '', JsonToArr($result));
        }

        echo $result;
        exit;
    }

    public function create()
    {
        $name = $this->request->get('name') or Response::_instance()->callback(1004);
        $result = $this->group->create($name);

        echo $result;
        exit;
    }

    public function update()
    {
        $name = $this->request->get('name') or Response::_instance()->callback(1004);
        $groupId = $this->request->get('group_id') or Response::_instance()->callback(1004);

        $result = $this->group->update($groupId, $name);

        echo $result;
        exit;
    }

    public function delete()
    {
        $groupId = $this->request->get('group_id') or Response::_instance()->callback(1004);
        $result = $this->group->delete($groupId);

        echo $result;
        exit;
    }

    /**
     * [移动单个用户到指定分组]
     * @Author: Krlee
     *
     */
    public function moveUser()
    {
        $openId = $this->request->get('open_id') or Response::_instance()->callback(1004);
        $groupId = $this->request->get('group_id') or Response::_instance()->callback(1004);

        $result = $this->group->moveUser($openId, $groupId);

    }

    /**
     * [批量移动用户到指定分组]
     * @Author: Krlee
     *
     */
    public function moveUsers()
    {
        $openId = $this->request->get('open_id') or Response::_instance()->callback(1004);
        $groupId = $this->request->get('group_id');
        if (!$groupId && !is_array($groupId)) {
            Response::_instance()->callback(1004);
        }

        $result = $this->group->moveUser($openId, $groupId);

        echo $result;
        exit;
    }

}