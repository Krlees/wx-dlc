<html>
<header class="login-header">
    <a class="back" href="javascript:window.history.go(-1);">返回</a>
    微信支付即时到账交易接口
</header>
<body>
<div>将使用您的微信付款，请确认。</div>
</body>
</html>
<script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>

    wx.config({
        debug: false,
        appId: "<?php echo $js['appId'] ?>",
        timestamp: "<?php echo $wx_config['timestamp'] ?>",
        nonceStr: "<?php echo $wx_config['noncestr'] ?>",
        signature: "<?php echo $wx_config['signature'] ?>",
        jsApiList: [
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQQ',
            'onMenuShareWeibo',
            'chooseImage',
            'previewImage',
            'hideMenuItems',
            'showMenuItems',
            'getNetworkType',
            'hideOptionMenu',
            'showOptionMenu',
            'chooseWXPay',
        ]
    });
    
	
    wx.ready(function() {
        wx.chooseWXPay({
            timestamp: "<?php echo $js['timeStamp'] ?>",
            nonceStr: "<?php echo $js['nonceStr'] ?>",
            package: "<?php echo $js['package'] ?>",
            signType: "<?php echo $js['signType'] ?>",
            paySign: "<?php echo $js['paySign'] ?>",
            success: function (res) {
                window.location.href = 'http://easy.krlee.com';
            },
            cancel: function(res) {
                window.history.go(-1);
            }
        });
    });
</script>
