<?php


namespace App\Controllers;

// +----------------------------------------------------------------------
// | 微信卡券
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------
use App\Responses\Response;

class Card extends Common
{
    protected $card;

    public function __construct()
    {
        parent::__construct();
        $this->card = $this->app->card;
    }

    /**
     * [获取卡卷颜色]
     * @Author Krlee
     *
     * @return mixed
     */
    public function getColors()
    {
        $result = $this->card->getColors();

        echo $result;
        exit;
    }

    /**
     * [创建卡卷]
     * @Author Krlee
     *
     * @param
     * @return mixed
     */
    public function create()
    {

        $cardType = $this->request->get('card_type', 'GROUPON'); // 卡卷类型 【默认团购卷】
        $baseInfo = $this->request->get('base_info');
        $especial = $this->request->get('especial');
        if (!$baseInfo || !$especial) {
            Response::_instance()->callback(1004);
        } elseif (is_array($baseInfo) || is_array($especial)) {
            Response::_instance()->callback(1008);
        }


//        $baseInfo = [
//            'logo_url' => 'http://mmbiz.qpic.cn/mmbiz/2aJY6aCPatSeibYAyy7yct9zJXL9WsNVL4JdkTbBr184gNWS6nibcA75Hia9CqxicsqjYiaw2xuxYZiaibkmORS2oovdg/0',
//            'brand_name' => '测试商户krlee',
//            'code_type' => 'CODE_TYPE_QRCODE',
//            'title' => '测试',
//            'sub_title' => '测试副标题',
//            'color' => 'Color010',
//            'notice' => '测试使用时请出示此券',
//            'service_phone' => '15311931577',
//            'description' => "测试不可与其他优惠同享\n如需团购券发票，请在消费时向商户提出\n店内均可使用，仅限堂食",
//            'date_info' => [
//                'type' => 'DATE_TYPE_FIX_TERM',
//                'fixed_term' => 90, //表示自领取后多少天内有效，不支持填写0
//                'fixed_begin_term' => 0, //表示自领取后多少天开始生效，领取后当天生效填写0。
//            ],
//            'sku' => [
//                'quantity' => 100, //自定义code时设置库存为0
//            ],
//            'location_id_list' => ['461907340'],  //获取门店位置poi_id，具备线下门店的商户为必填
//            'get_limit' => 1,
//            //'use_custom_code' => true, //自定义code时必须为true
//            //'get_custom_code_mode' => 'GET_CUSTOM_CODE_MODE_DEPOSIT',  //自定义code时设置
//            'bind_openid' => false,
//            'can_share' => true,
//            'can_give_friend' => false,
//            'center_title' => '顶部居中按钮',
//            'center_sub_title' => '按钮下方的wording',
//            'center_url' => 'http://www.qq.com',
//            'custom_url_name' => '立即使用',
//            'custom_url' => 'http://www.qq.com',
//            'custom_url_sub_title' => '6个汉字tips',
//            'promotion_url_name' => '更多优惠',
//            'promotion_url' => 'http://www.qq.com',
//            'source' => 'krlee',
//        ];
//        $especial = [
//            'deal_detail' => 'deal_detail',
//        ];

        $result = $this->card->create($cardType, $baseInfo, $especial);

        echo $result;
        exit;
    }

    /**
     * 创建二维码【领取卡卷一张或多张】
     * @param
     */
    public function createQRcode()
    {
        $cards = $this->request->get('cards');
        if (!$cards) {
            Response::_instance()->callback(1004);
        } elseif (is_array($cards)) {
            Response::_instance()->callback(1008);
        }

//        $cards = [
//            'action_name' => 'QR_MULTIPLE_CARD',
//            'action_info' => [
//                'multiple_card' => [
//                    'card_list' => [
//                        ['card_id' => 'p_wtev7zI2xtINbwqcuUUwpxVcyE'],
//                    ],
//                ],
//            ],
//        ];
//        $cards = [
//            'action_name' => 'QR_CARD',
//            'expire_seconds' => 1800,
//            'action_info' => [
//                'card' => [
//                    'card_id' => 'p_wtev7zI2xtINbwqcuUUwpxVcyE',
//                    'is_unique_code' => false,
//                    'outer_id' => 1,
//                ],
//            ],
//        ];

        $result = $this->card->QRCode($cards);

        echo $result;
        exit;
    }

    /**
     * 创建货架接口
     * @param $banner      页面的 banner 图;
     * @param $pageTitle   页面的 title
     * @param $canShare    页面是不是可以分享，true 或 false
     * @param $scene       投放页面的场景值，具体值请参考下面的 example
     *      SCENE_NEAR_BY    附近
     *      SCENE_MENU       自定义菜单
     *      SCENE_QRCODE     二维码
     *      SCENE_ARTICLE    公众号文章
     *      SCENE_H5         h5页面*       SCENE_IVR                 自动回复
     *      SCENE_CARD_CUSTOM_CELL 卡券自定义cell
     * @param $cards       卡券列表，每个元素有两个字段
     */
    public function createLandingPage()
    {
        $banner = 'http://mmbiz.qpic.cn/mmbiz/iaL1LJM1mF9aRKPZJkmG8xXhiaHqkKSVMMWeN3hLut7X7hicFN';
        $pageTitle = '惠城优惠大派送';
        $canShare = true;


        $scene = 'SCENE_NEAR_BY';
//        $cardList = [
//            [
//                'card_id' => 'p_wtev1iZG0daoHy1IcqSshlMnHI',
//                'thumb_url' => 'http://test.digilinx.cn/wxApi/Uploads/test.png'
//            ],
//        ];

        $banner = $this->request->get('banner');
        $pageTitle = $this->request->get('page_title');
        $canShare = $this->request->get('can_share');
        $scene = $this->request->get('scene', 'SCENE_NEAR_BY');
        $cards = $this->request->get('cards');
        if (!isset($banner{0}) || !isset($pageTitle{0}) || !$cards) {
            Response::_instance()->callback(1004);
        } elseif (!is_array($cards)) {
            Response::_instance()->callback(1008);
        } elseif (!in_array($scene, ['SCENE_NEAR_BY', 'SCENE_MENU', 'SCENE_QRCODE', 'SCENE_ARTICLE', 'SCENE_H5', 'SCENE_CARD_CUSTOM_CELL'])) {
            Response::_instance()->callback(1008);
        }


        $result = $this->card->createLandingPage($banner, $pageTitle, $canShare, $scene, $cards);

        echo $result;
        exit;
    }

    /**
     * 导入code接口
     * @param $cardId
     * @param
     * @return mixed
     */
    public function deposit()
    {
        $cardId = $this->request->get('card_id');
        $code = $this->request->get('code');
        if (!isset($cardId{0}) || !isset($code{0})) {
            Response::_instance()->callback(1004);
        }

        $result = $this->card->deposit($cardId, $code);

        echo $result;
        exit;
    }

    /**
     * 查询导入code数目
     * @param $cardId
     * @return mixed
     */
    public function getDeposit()
    {
        $cardId = $this->request->get('card_id') or Response::_instance()->callback(1004);
        $result = $this->card->getDepositedCount($cardId);

        echo $result;
        exit;
    }

    /**
     * 查询导入code数目
     * @param $cardId
     * @return mixed
     */
    public function checkDepositCode()
    {
        $cardId = $this->request->get('card_id');
        $code = $this->request->get('code');
        if (!isset($cardId{0}) || !isset($code{0})) {
            Response::_instance()->callback(1004);
        }

        $result = $this->card->checkCode($cardId, $code);

        echo $result;
        exit;
    }

    /**
     * 图文消息群发卡券
     * @param $cardId
     */
    public function getVoiceHtml($cardId)
    {
        $cardId = $this->request->get('card_id') or Response::_instance()->callback(1004);
        $result = $this->card->getHtml($cardId);

        echo $result;
        exit;
    }

    /**
     * 设置测试白名单
     */
    public function setTestWhitelist()
    {
        $type = $this->request->get('type', 1);
        $data = $this->request->get('data') or Response::_instance()->callback(1004);

        if ($type == 1) {
            // openid方式
            $result = $this->card->setTestWhitelist($data);
        } elseif ($type == 2) {
            $result = $this->card->setTestWhitelistByUsername($data);
        } else {
            Response::_instance()->callback(1008);
        }

        echo $result;
        exit;
    }

    /**
     * 查询Code接口【校验code核销状态】
     */
    public function getCode()
    {
        $cardId = $this->request->get('card_id');
        $code = $this->request->get('code');
        $checkConsume = $this->request->get('check');

        // 检测必须参数
        $this->checkParam(compact('cardId', 'code', 'checkConsume'));

        $result = $this->card->getCode($code, $checkConsume, $cardId);

        echo $result;
        exit;
    }

    /**
     * 核销Code接口
     * @param $cardId
     * @param
     * @return mixed
     */
    public function consume()
    {
        $cardId = $this->request->get('card_id');
        $code = $this->request->get('code');

        $result = $this->card->consume($code, $cardId);

        echo $result;
        exit;
    }

    /**
     * Code解码接口
     * @param $encryptedCode
     * @return mixed
     */
    public function decryptCode()
    {
        $encryptedCode = $this->request->get('encrypted_code') or Response::_instance()->callback(1004);

        $result = $this->card->decryptCode($encryptedCode);

        echo $result;
        exit;
    }

    /**
     * 获取用户已领取卡券接口
     * @param
     * @return array
     */
    public function getUserCards()
    {
        $openid = $this->request->get('openid') or Response::_instance()->callback(1004);
        $cardId = $this->request->get('card_id', ''); //卡券ID。不填写时默认查询当前appid下的卡券。


        $result = $this->card->getUserCards($openid, $cardId);

        echo $result;
        exit;
    }

    /**
     * 查看卡券详情
     */
    public function getDetail()
    {
        $cardId = $this->request->get('card_id') or Response::_instance()->callback(1004);

        $result = $this->card->getCard($cardId);

        echo $result;
        exit;
    }

    /**
     * 批量查询卡列表
     */
    public function getCardList()
    {
        $offset = $this->request->get('offset', 0);
        $count = $this->request->geti('count', 10);
        $statusList = $this->request->get('status', 'CARD_STATUS_VERIFY_OK');
        //CARD_STATUS_NOT_VERIFY,待审核；
        //CARD_STATUS_VERIFY_FAIL,审核失败；
        //CARD_STATUS_VERIFY_OK，通过审核；
        //CARD_STATUS_USER_DELETE，卡券被商户删除；
        //CARD_STATUS_DISPATCH，在公众平台投放过的卡券；

        $result = $this->card->lists($offset, $count, $statusList);

        echo $result;
        exit;
    }

    /**
     * 设置微信买单接口
     * @param $cardId
     * @param $isOpen
     * @return mixed
     */
    public function setPayCell()
    {
        $cardId = $this->request->get('card_id') or Response::_instance()->callback(1004);
        $isOpen = $this->request->get('is_open', true);

        $result = $this->card->setPayCell($cardId, boolval($isOpen));

        echo $result;
        exit;
    }

    /**
     * [更新卡劵库存]
     */
    public function updateStock()
    {
        $cardId = $this->request->get('card_id') or Response::_instance()->callback(1004);
        $num = (int)$this->request->get('num');
        $type = $this->request->get('type', 'add');
        if ($type == 'add') {
            $result = $this->card->increaseStock($cardId, $num); // 增加库存
        } elseif ($type == 'reduce') {
            $result = $this->card->reduceStock($cardId, $num); // 减少库存
        } else {
            Response::_instance()->callback(1008, "type参数格式只有[add/reduce],请检查参数");
        }

        echo $result;
        exit;
    }

    /**
     * 更改Code接口
     * @return mixed
     */
    public function updateCode()
    {
        $code = $this->request->get('code');
        $newCode = $this->request->get('newCode');
        $cardId = $this->request->get('cardId', []);

        $this->checkParam(compact('code', 'newCode'));

        $result = $this->card->updateCode($code, $newCode, $cardId);

        echo $result;
        exit;
    }

    /**
     * 更改卡券信息接口
     * @param $cardId
     * @param
     * @return array
     */
    public function update()
    {

//        $cardId = 'pdkJ9uCzKWebwgNjxosee0ZuO3Os';
//        $type = 'groupon';
//        $baseInfo = [
//            'logo_url' => 'http://mmbiz.qpic.cn/mmbiz/2aJY6aCPatSeibYAyy7yct9zJXL9WsNVL4JdkTbBr184gNWS6nibcA75Hia9CqxicsqjYiaw2xuxYZiaibkmORS2oovdg/0',
//            'center_title' => '顶部居中按钮',
//            'center_sub_title' => '按钮下方的wording',
//            'center_url' => 'http://www.baidu.com',
//            'custom_url_name' => '立即使用',
//            'custom_url' => 'http://www.qq.com',
//            'custom_url_sub_title' => '6个汉字tips',
//            'promotion_url_name' => '更多优惠',
//            'promotion_url' => 'http://www.qq.com',
//        ];

        $cardId = $this->request->get('card_id');
        $type = $this->request->get('type', 'groupon');
        $baseInfo = $this->request->get('base_info');

        // 检查参数
        $this->checkParam(compact('cardId', 'baseInfo'));
        if (!is_array($baseInfo)) {
            Response::_instance()->callback(1008);
        }

        $result = $this->card->update($cardId, $type, $baseInfo);

        echo $result;
        exit;
    }

    /**
     * 删除卡劵接口
     * @param $cardId
     */
    public function delete()
    {
        $cardId = $this->request->get('card_id') or Response::_instance()->callback(1004);
        $result = $this->card->delete($cardId);

        echo $result;
        exit;
    }

    /**
     * 设置卡券失效
     * @param $cardId
     * @param $code
     * @return mixed
     */
    public function setCardDisable()
    {
        $cardId = $this->request->get('card_id');
        $code = $this->request->get('code');

        $this->checkParam(compact('cardId', 'code'));

        $result = $this->card->disable($code, $cardId);

        echo $result;
        exit;
    }


    /**
     * 激活会员卡
     * @return mixed
     */
    public function setMemberActivate()
    {
        $activate = $this->request->get('activate') or Response::_instance()->callback(1004);
        if (!is_array($activate)) {
            Response::_instance()->callback(1008);
        }
        //        $activate = [
        //            'membership_number'        => '357898858', //会员卡编号，由开发者填入，作为序列号显示在用户的卡包里。可与Code码保持等值。
        //            'code'                     => '916679873278', //创建会员卡时获取的初始code。
        //            'activate_begin_time'      => '1397577600', //激活后的有效起始时间。若不填写默认以创建时的 data_info 为准。Unix时间戳格式
        //            'activate_end_time'        => '1422724261', //激活后的有效截至时间。若不填写默认以创建时的 data_info 为准。Unix时间戳格式。
        //            'init_bonus'               => '持白金会员卡到店消费，可享8折优惠。', //初始积分，不填为0。
        //            'init_balance'             => '持白金会员卡到店消费，可享8折优惠。', //初始余额，不填为0。
        //            'init_custom_field_value1' => '白银', //创建时字段custom_field1定义类型的初始值，限制为4个汉字，12字节。
        //            'init_custom_field_value2' => '9折', //创建时字段custom_field2定义类型的初始值，限制为4个汉字，12字节。
        //            'init_custom_field_value3' => '200', //创建时字段custom_field3定义类型的初始值，限制为4个汉字，12字节。
        //        ];

        $result = $this->card->activate($activate);

        echo $result;
        exit;
    }

    /**
     * 设置开卡字段接口
     */
    public function setActivateUserForm()
    {
        $cardId = $this->request->get('card_id') or Response::_instance()->callback(1004);
        $requiredForm = $this->request->get('requiredForm',[]);
        $optionalForm = $this->request->get('optionalForm',[]);
//        $requiredForm = [
//            'required_form' => [
//                'common_field_id_list' => [
//                    'USER_FORM_INFO_FLAG_MOBILE',
//                    'USER_FORM_INFO_FLAG_LOCATION',
//                    'USER_FORM_INFO_FLAG_BIRTHDAY',
//                ],
//                'custom_field_list' => [
//                    '喜欢的食物',
//                ],
//            ],
//        ];
//        $optionalForm = [
//            'optional_form' => [
//                'common_field_id_list' => [
//                    'USER_FORM_INFO_FLAG_EMAIL',
//                ],
//                'custom_field_list' => [
//                    '喜欢的食物',
//                ],
//            ],
//        ];

        $result = $this->card->activateUserForm($cardId, $requiredForm, $optionalForm);

        echo $result;
        exit;
    }

    /**
     * 拉取会员信息接口
     * @param $cardId
     * @param $code
     * @return mixed
     */
    public function getMemberCardUser($cardId, $code)
    {
        $cardId = $this->request->get('card_id') or Response::_instance()->callback(1004);
        $code = $this->request->get('code') or Response::_instance()->callback(1004);

        $result = $this->card->getMemberCardUser($cardId, $code);

        echo $result;
        exit;
    }

    /**
     * 更新会员信息
     * @param
     * @return mixed
     */
    public function updateMemberCardUser()
    {
        $info = $this->request->get('info') or Response::_instance()->callback(1004);
        if( !is_array($info)){
            Response::_instance()->callback(1008);
        }

    //        $updateUser = [
    //            'code'                => '916679873278', //卡券Code码。
    //            'card_id'             => 'pbLatjtZ7v1BG_ZnTjbW85GYc_E8', //卡券ID。
    //            'record_bonus'        => '消费30元，获得3积分', //商家自定义积分消耗记录，不超过14个汉字。
    //            'bonus'               => '100', //需要设置的积分全量值，传入的数值会直接显示，如果同时传入add_bonus和bonus,则前者无效。
    //            'balance'             => '持白金会员卡到店消费，可享8折优惠。', //需要设置的余额全量值，传入的数值会直接显示，如果同时传入add_balance和balance,则前者无效。
    //            'record_balance'      => '持白金会员卡到店消费，可享8折优惠。', //商家自定义金额消耗记录，不超过14个汉字。
    //            'custom_field_value1' => '100', //创建时字段custom_field1定义类型的最新数值，限制为4个汉字，12字节。
    //            'custom_field_value2' => '200', //创建时字段custom_field2定义类型的最新数值，限制为4个汉字，12字节。
    //            'custom_field_value3' => '300', //创建时字段custom_field3定义类型的最新数值，限制为4个汉字，12字节。
    //        ];
        $result = $this->card->updateMemberCardUser($info);

        echo $result;
        exit;
    }

    /**
     * 添加子商户
     */
    public function createSubMerchant()
    {
        $info = $this->request->get('info') or Response::_instance()->callback(1004);
        if( !is_array($info)){
            Response::_instance()->callback(1008);
        }
    //        $info = [
    //            'brand_name' => 'overtrue',
    //            'logo_url' => 'http://mmbiz.qpic.cn/mmbiz/iaL1LJM1mF9aRKPZJkmG8xXhiaHqkKSVMMWeN3hLut7X7hicFNjakmxibMLGWpXrEXB33367o7zHN0CwngnQY7zb7g/0',
    //            'protocol' => 'qIqwTfzAdJ_1-VJFT0fIV53DSY4sZY2WyhkzZzbV498Qgdp-K5HJtZihbHLS0Ys0',
    //            'end_time' => '1438990559',
    //            'primary_category_id' => 1,
    //            'secondary_category_id' => 101,
    //            'agreement_media_id' => '',
    //            'operator_media_id' => '',
    //            'app_id' => '',
    //        ];

        $result = $this->card->createSubMerchant($info);

        echo $result;
        exit;
    }

    /**
     * 更新子商户
     * @param
     * @return mixed
     */
    public function updateSubMerchant()
    {
        $merchantId = $this->request->get('merchantId') or Response::_instance()->callback(1004);
        $info = $this->request->get('info',[]);

        $result = $this->card->updateSubMerchant($merchantId, $info);

        echo $result;
        exit;
    }

    /**
     * 卡券开放类目查询接口
     * @return mixed
     */
    public function getCategories()
    {
        $result = $this->card->getCategories();

        echo $result;
        exit;
    }

    /**
     * [ticket 换取二维码链接]
     * @Author: Krlee
     *
     * @param string $ticket 卡券id
     * @return string
     */
    public function showQRCode()
    {
        $ticket = $this->request->get('ticket') or Response::_instance()->callback(1004);

        $result = $this->card->getQRCodeUrl($ticket);

        echo $result;
        exit;
    }


    /**
     * [暂时停用]
     * JSAPI 卡券批量下发到用户
     */
    public function cardAssign()
    {
        $cards = [
            ['card_id' => 'pdkJ9uLRSbnB3UFEjZAgUxAJrjeY', 'outer_id' => 2],
            ['card_id' => 'pdkJ9uJ37aU-tyRj4_grs8S45k1c', 'outer_id' => 3],
        ];
        $json = $this->card->jsConfigForAssign($cards); // 返回 json 格式

        //return view('card_assign', ['json' => $json]);
    }
}