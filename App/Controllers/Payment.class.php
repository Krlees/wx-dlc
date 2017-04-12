<?php


namespace App\Controllers;

// +----------------------------------------------------------------------
// | 微信支付
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------
use App\Cache\MemcacheOperate;
use App\Responses\Response;
use EasyWeChat\Payment\Order;
use EasyWeChat\QRCode\QRCode;

class Payment extends Common
{
    private $payment;

    public function __construct()
    {
        parent::__construct();
        $this->payment = $this->app->payment;
    }

    /**
     * [支付下单--正常模式]
     *      通知url必须为直接可访问的url，不能携带参数。示例：notify_url：“https://pay.weixin.qq.com/wxpay/pay
     * @Author: Krlee
     *
     */
    public function order()
    {
        $out_trade_no = $this->request->get('out_trade_no');
        $total_fee = $this->request->get('total_fee');
        $openId = $this->request->get('open_id');
        $body = $this->request->get('body');
        if (!isset($out_trade_no{0}) || !isset($openid{0}) || !isset($body{0}) || !$total_fee) {
            Response::_instance()->callback(1004);
        }

        $detail = $this->request->get('detail');
        $notify_url = $this->request->get('notify_url');
        if (!$notify_url) {
            $options = MemcacheOperate::getInstance()->get($this->token);
            $notify_url = $options['payment']['notify_url'];
        }

        /* 逻辑处理 */
        $attributes = [
            'trade_type' => 'JSAPI', // JSAPI，NATIVE，APP...
            'body' => $body,
            'detail' => $detail,
            'out_trade_no' => $out_trade_no,
            'total_fee' => $total_fee, // 单位：分
            'notify_url' => $notify_url, // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'openid' => $openId, // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
            // ...
        ];
        $order = new Order($attributes);
        $result = $this->payment->prepare($order);
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS') {
            $prepayId = $result->prepay_id;

            // 微信支付配置
            $config = $this->payment->configForJSSDKPayment($result->prepay_id);

            $js = $this->app->js->config([
                'checkJsApi',
                'chooseWXPay',
            ], true);

            //return view('pay',['js' => $js, 'config'=>$config ]);
        } else {
            echo $result;
            exit;
        }

    }

    /**
     * 调起【二维码】微信支付
     * @Author: Krlee
     *
     * @param $orderNo  订单编号
     * @return mixed
     */
    public function nativePay(Request $request)
    {
        $out_trade_no = $this->request->get('out_trade_no');
        $body = $this->request->get('body');
        $detail = $this->request->get('detail');
        $total_fee = $this->request->get('total_fee');
        $notify_url = $this->request->get('notify_url');
        if (!isset($out_trade_no{0}) || !isset($body{0}) || !$total_fee) {
            Response::_instance()->callback(1004);
        }

        $notify_url = $this->request->get('notify_url');
        if (!$notify_url) {
            $options = MemcacheOperate::getInstance()->get($this->token);
            $notify_url = $options['payment']['notify_url'];
        }

        /* 逻辑处理 */
        $attributes = [
            'trade_type' => 'NATIVE', // JSAPI，NATIVE，APP...
            'body' => $body,
            'detail' => $detail,
            'out_trade_no' => $out_trade_no,
            'total_fee' => $total_fee,
            'notify_url' => $notify_url, // 支付结果通知网址，如果不设置则会使用配置里的默认地址
        ];

        $order = new Order($attributes);

        $result = $this->payment->prepare($order);

        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS') {

        } else {
            // -- 二维码支付
//            if (isset($result['code_url']{0})) {
//                $qrcode = QRCode::size(400)->color(255, 0, 255)->generate($result['code_url']);
//            }

            echo $result;
            exit;
        }

    }

    /**
     * 关闭订单【订单生成后不能马上调用关单接口，最短调用时间间隔为5分钟。】
     * @Author: Krlee
     *
     * @param $out_trade_no
     * @return mixed
     */
    public function close()
    {
        $out_trade_no = $this->request->get('out_trade_no') or Response::_instance()->callback(1004);
        $result = $this->payment->close($out_trade_no);

        echo $result;
        exit;
    }

    /**
     * 撤销订单【建议支付后至少15s后再调用撤销订单接口】
     * @Author: Krlee
     *
     * @param $out_trade_no  订单编号
     * @return mixed
     */
    public function reverse()
    {
        $out_trade_no = $this->request->get('out_trade_no') or Response::_instance()->callback(1004);
        $result = $this->payment->reverse($out_trade_no);

        echo $result;
        exit;
    }

    /**
     * 查询订单
     * @Author: Krlee
     *
     * @param $transactionId  微信支付单号
     * @return mixed
     */
    public function query()
    {
        $transactionId = $this->request->get('transaction_id') or Response::_instance()->callback(1004);
        $result = $this->payment->queryByTransactionId($transactionId);

        echo $result;
        exit;
    }

    /**
     * 退款
     * @Author: Krlee
     *
     * @param $transactionId   微信支付单号
     * @return mixed
     */
    public function refund()
    {
        $transactionId = $this->request->get('transaction_id');
        $total_fee = $this->request->get('total_fee');     // 订单总价
        $refund_fee = $this->request->get('refund_fee');    // 退款价钱
        $refundNo = $this->request->get('refund_no');     // 退款单号, 为商户退款单号，自己生成用于自己识别即可。
        $open_user = $this->request->get('open_user');  // 操作员
        if (!isset($transactionId{0}) || !$total_fee || !$refund_fee || !isset($refundNo{0}) || !isset($open_user{0})) {
            Response::_instance()->callback(1004);
        } elseif ($refund_fee > $total_fee) {
            Response::_instance()->callback(80001, "退款金额不可大于订单总金额");
        }

        //$refund_account = $this->request->get('refund_account','REFUND_SOURCE_UNSETTLED_FUNDS');  // 支付账户

        $result = $this->payment->refundByTransactionId($transactionId, $refundNo, $total_fee, $refund_fee, $open_user);

        echo $result;
        exit;
    }

    /**
     * 查询退款单号详情
     * @Author: Krlee
     *
     * @param $transactionId  微信支付单号
     * @return mixed
     */
    public function queryRefund()
    {
        $transactionId = $this->request->get('transaction_id') or Response::_instance()->callback(1004);
        $result = $this->payment->queryRefundByTransactionId($transactionId);

        echo $result;
        exit;
    }

    /**
     * 下载对账单
     * @Author: Krlee
     *
     * @param  $bill_date 【账单日期 20140708】
     * @param  $bill_type  ALL/SUCCESS/REFUND/REVOKED
     *      ALL：返回当日所有订单信息（默认值）
     *      SUCCESS：返回当日成功支付的订单
     *      REFUND：返回当日退款订单
     *      REVOKED：已撤销的订单
     */
    public function downloadBill()
    {
        $billDate = $this->request->get('bill_date') or Response::_instance()->callback(1004);
        $billType = $this->request->get('bill_type') or Response::_instance()->callback(1004);

        $result = $this->payment->downloadBill($billDate, $billType);
        //file_put_contents(public_path('kk.csv'), $result);
        echo $result;
        exit;
    }

    /**
     * [转出短链接]
     * @Author: Krlee
     *
     * @return array|\EasyWeChat\Support\Collection
     */
    public function shortUrl()
    {
        $url = $this->request->get('url') or Response::_instance()->callback(1004);

        $result = $this->payment->urlShorten($url);

        echo $result;
        exit;
    }

    /**
     * [企业付款]
     * @Author: Krlee
     *
     */
    public function merchantPay()
    {
        $partner_trade_no = $this->request->get('partner_trade_no');
        $openid = $this->request->get('openid');
        $re_user_name = $this->request->get('re_user_name');
        $total_fee = $this->request->get('total_fee');
        $desc = $this->request->get('desc');
        $create_ip = $this->request->get('create_ip', \EasyWeChat\Payment\get_client_ip());
        if(
            !isset($partner_trade_no{0}) || !isset($openid{0}) || !isset($re_user_name{0}) ||
            !isset($desc{0}) || !$total_fee
        ){
            Response::_instance()->callback(1004);
        }

        $merchantPayData = [
            'partner_trade_no' => $partner_trade_no, //随机字符串作为订单号，跟红包和支付一个概念。
            'openid' => $openid, //收款人的openid
            'check_name' => 'NO_CHECK',  //文档中有三种校验实名的方法 NO_CHECK OPTION_CHECK FORCE_CHECK
            're_user_name' => $re_user_name,     //OPTION_CHECK FORCE_CHECK 校验实名的时候必须提交
            'amount' => $total_fee,  //单位为分
            'desc' => $desc,
            'spbill_create_ip' => $create_ip,  //发起交易的IP地址
        ];
        $result = $this->app->merchant_pay->send($merchantPayData);

        echo $result;
        exit;
    }

    /**
     * [查询企业付款信息]
     * @Author: Krlee
     *
     * @param $partnerTradeNo 商户系统内部的订单号
     * @return mixed
     */
    public function queryMchPay()
    {
        $partnerTradeNo = $this->request->get('partner_trade_no') or Response::_instance()->callback(1004);

        $result = $this->app->merchant_pay->query($partnerTradeNo);

        echo $result;
        exit;
    }


}