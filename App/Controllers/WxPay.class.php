<?php

namespace App\Controllers;

// 东莞同富学校专用支付
use EasyWeChat\Foundation\Application;
use EasyWeChat\Payment\Order;


class WxPay
{
    public function pay()
    {
        $options = [
            // 前面的appid什么的也得保留哦
            'app_id' => 'xxxx',
            // ...
            // payment
            'payment' => [
                'merchant_id'        => 'your-mch-id',
                'key'                => 'key-for-signature',
                'cert_path'          => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
                'key_path'           => 'path/to/your/key',      // XXX: 绝对路径！！！！
                'notify_url'         => '默认的订单回调地址',       // 你也可以在下单时单独设置来想覆盖它
                // 'device_info'     => '013467007045764',
                // 'sub_app_id'      => '',
                // 'sub_merchant_id' => '',
                // ...
            ],
        ];
        $app = new Application($options);
        $payment = $app->payment;

        // 逻辑处理
        $attributes = [
            'trade_type' => 'JSAPI', // JSAPI，NATIVE，APP...
            'body' => $body,
            'detail' => $detail,
            'out_trade_no' => $this->create_order_sn(),
            'total_fee' => $total_fee, // 单位：分
            'notify_url' => $notify_url, // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'openid' => $openId, // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
            // ...
        ];
        $order = new Order($attributes);
        $result = $this->payment->prepare($order);
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS') {

            // 微信支付配置
            $config = $this->payment->configForJSSDKPayment($result->prepay_id);

            $js = $this->app->js->config([
                'checkJsApi',
                'chooseWXPay',
            ], true);

            include '../Views/wxpay.php';
        }
    }

    private function create_order_sn()
    {
        return '';
    }
}