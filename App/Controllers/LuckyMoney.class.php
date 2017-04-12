<?php

namespace App\Controllers;

// +----------------------------------------------------------------------
// | 微信红包
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------
use App\Responses\Response;

class LuckyMoney extends Common
{
    protected $lucky_money;
    private $parameter; // 通用参数

    public function __construct()
    {
        parent::__construct();
        $this->lucky_money = $this->lucky_money;

        $mch_billno = $this->request->get('mch_billno');    // 商户订单号
        $send_name = $this->request->get('send_name');     // 商户名称
        $re_openid = $this->request->get('openid');        // 用户openid
        $total_num = $this->request->get('total_num', 1);   // 红包发放总人数
        $total_amount = $this->request->get('total_fee');     // 总价钱      不小于300
        $wishing = $this->request->get('wishing');       // 红包祝福语
        $act_name = $this->request->get('act_name');      // 活动名称
        $remark = $this->request->get('remark');        // 备注
        $client_ip = $this->request->get('client_ip', \EasyWeChat\Payment\get_client_ip());
        if (!isset($mch_billno{0}) || !isset($send_name{0}) || !isset($openid{0}) ||
            !isset($wishing{0}) || !isset($act_name{0}) || !isset($remark{0}) || !$total_num
        ) {
            Response::_instance()->callback(1004);
        }

        $this->parameter = compact('mch_billno', 'send_name', 'openid', 'total_num', 'total_fee', 'wishing', 'act_name', 'remark', 'client_ip');
    }

    /**
     * [通用发放红包]
     * @Author: Krlee
     *
     * @return mixed
     */
    public function send()
    {
        $this->__baseCheck();

        $type = $this->request->get('type');
        $type = strtolower($type);
        if ($type == 'normal') {
            $result = $this->lucky_money->sendNormal($this->parameter);
        } elseif ($type == 'group') {
            $this->parameter['amt_type'] = 'ALL_RAND';
            $result = $this->lucky_money->sendGroup($this->parameter);
        } else {
            Response::_instance()->callback(80001, 'type参数错误，只可传(normal/group)');
        }

        return $result;
    }

    /**
     * [发送普通红包接口]
     * @Author: Krlee
     *
     */
    public function sendNormal()
    {
        $this->__baseCheck();

        $result = $this->lucky_money->sendNormal($this->parameter);

        echo $result;
        exit;
    }

    /**
     * [发送裂变红包接口]
     * @Author: Krlee
     *
     */
    public function sendGroup()
    {
        $this->__baseCheck();

        $result = $this->lucky_money->sendGroup($this->parameter);

        echo $result;
        exit;
    }

    /**
     * [合并可选参数]
     * @Author: Krlee
     *
     */
    private function __baseCheck()
    {
        $luckyMoneyData = [];

        // 场景id
        $scene_id = $this->request->get('scene_id');
        if (isset($scene_id{0})) {
            $luckyMoneyData['scene_id'] = $scene_id;
        }

        // 活动信息
        $risk_info = $this->request->get('risk_info');
        if (isset($risk_info{0})) {
            $luckyMoneyData['risk_info'] = $risk_info;
        }

        // 资金授权商户号
        $consume_mch_id = $this->request->get('consume_mch_id');
        if (isset($consume_mch_id{0})) {
            $luckyMoneyData['consume_mch_id'] = $consume_mch_id;
        }

        // 合并参数
        $this->parameter = array_merge($luckyMoneyData, $this->parameter);
    }

}