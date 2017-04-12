<?php


namespace App\Controllers;


use App\Responses\Response;

class StaffSession extends Common
{
    protected $staff_session;

    public function __construct()
    {
        parent::__construct();
        $this->staff_session = $this->app->staff_session;
    }

    /**
     * 创建客服会话
     * @param $account
     * @param $openId
     * @return mixed
     */
    public function create()
    {
        $account = $this->request->get('account');
        $openId = $this->request->get('open_id');
        $this->checkParam(compact('account','openId'));

        $result = $this->staff_session->create($account, $openId);

        echo $result;
        exit;
    }

    /**
     * 关闭会话
     * @param $account
     * @param $openId
     * @return mixed
     */
    public function close($account, $openId)
    {
        $account = $this->request->get('account');
        $openId = $this->request->get('open_id');
        $this->checkParam(compact('account','openId'));

        $result = $this->staff_session->close($account, $openId);

        echo $result;
        exit;
    }

    /**
     * 获取当前会话状态【判断是否已关闭】
     * @param $openId
     * @return mixed
     */
    public function checkClose()
    {
        $openId = $this->request->get('open_id') or Response::_instance()->callback(1004);

        $result = $this->staff_session->close($openId);

        echo $result;
        exit;
    }

    /**
     * 获取所有会话列表
     * @param $account
     * @return mixed
     */
    public function getList($account)
    {
        $account = $this->request->get('account') or Response::_instance()->callback(1004);

        $result = $this->staff_session->lists($account);

        echo $result;
        exit;
    }

    /**
     * 获取所有未接入会话列表
     * @return mixed
     */
    public function waiters()
    {
        $result = $this->staff_session->waiters();

        $this->responseList($result);
    }
}