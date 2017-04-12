<?php

namespace App\Controllers;



use App\Responses\Response;

class Staff extends Common
{
    protected $staff;

    public function __construct()
    {
        parent::__construct();
        $this->staff = $this->app->staff;
    }

    /**
     * 获取所有客服账号列表
     * @return mixed
     */
    public function getList()
    {
        $result = $this->staff->lists();

        $this->responseList($result);
    }

    /**
     * 获取所有在线的客服账号列表
     * @return mixed
     */
    public function getOnlines()
    {
        $result = $this->staff->onlines();

        $this->responseList($result);
    }

    /**
     * 添加客服帐号
     * @param $account
     * @param $nickname
     * @return mixed
     */
    public function create()
    {
        $account = $this->request->get('account');
        $nickname = $this->request->get('nickname');
        $this->checkParam(compact('account','nickname'));

        $result = $this->staff->create($account, $nickname);

        echo $result;
        exit;
    }

    /**
     * 修改客服帐号
     * @param $account
     * @param $nickname
     * @return mixed
     */
    public function update($account,$nickname)
    {
        $account = $this->request->get('account');
        $nickname = $this->request->get('nickname');
        $this->checkParam(compact('account','nickname'));

        $result = $this->staff->update($account, $nickname);

        echo $result;
        exit;
    }

    /**
     * 删除客服帐号
     * @param $account
     * @return mixed
     */
    public function delete($account)
    {
        $account = $this->request->get('account') or Response::_instance()->callback(1004);
        $result = $this->staff->delete($account);

        echo $result;
        exit;
    }

    /**
     * 设置客服头像
     * @param $account
     * @param $avatarPath   本地图片路径
     * @return mixed
     */
    public function setAvatar($account, $avatarPath)
    {
        $account = $this->request->get('account');
        $avatarPath = $this->request->get('avatar_path');
        $this->checkParam(compact('account','avatarPath'));

        $result = $this->staff->avatar($account, $avatarPath); // $avatarPath 为本地图片路径，非 URL

        echo $result;
        exit;
    }

    /**
     * 获取客服聊天记录
     * @param Request $request
     * @return array
     */
    public function getRecords()
    {
        $startTime = $this->request->get('start_time');
        $endTime   = $this->request->get('end_time');
        $pageIndex = $this->request->get('page_index',1);
        $pageSize  = $this->request->get('page_size',20);
        $this->checkParam(compact('startTime','endTime'));

        $result = $this->staff->records($startTime, $endTime, $pageIndex, $pageSize);

        echo $result;
        exit;
    }

    /**
     * 主动发送消息给用户
     * @param Request $request
     * @return array
     */
    public function sendToUser()
    {
        $message = $this->request->get('message'); //只支持字符串
        $openId  = $this->request->get('open_id');

        $this->checkParam(compact('message','openId'));

        $result = $this->staff->message($message)->to($openId)->send();

        echo $result;
        exit;
    }

    /**
     * 指定客服发送消息
     * @param Request $request
     * @return array
     */
    public function setStaffToUser()
    {
        $account = $this->request->get('account');
        $message = $this->request->get('message'); //只支持字符串
        $openId  = $this->request->get('open_id');
        $this->checkParam(compact('account','message','openId'));

        $result = $this->staff->message($message)->by($account)->to($openId)->send();

        echo $result;
        exit;
    }

}

