<?php

namespace App\Controllers;

// +----------------------------------------------------------------------
// | 微信数据统计
// +----------------------------------------------------------------------
// | @Authoer Krlee
// | 通过数据接口，开发者可以获取与公众平台官网统计模块类似但更灵活的数据，还可根据需要进行高级处理。
// |        1. 接口侧的公众号数据的数据库中仅存储了 2014年12月1日之后的数据，将查询不到在此之前的日期，即使有查到，也是不可信的脏数据
// |        2. 请开发者在调用接口获取数据后，将数据保存在自身数据库中，即加快下次用户的访问速度，也降低了微信侧接口调用的不必要损耗。
// |        3. 额外注意，获取图文群发每日数据接口的结果中，只有中间页阅读人数+原文页阅读人数+分享转发人数+分享转发次数+收藏次数 >=3 的结果才会得到统计，过小的阅读量的图文消息无法统计。
// +----------------------------------------------------------------------
use App\Responses\Response;

class Stats extends Common
{
    protected $stats;

    public function __construct()
    {
        parent::__construct();
        $this->stats = $this->app->stats;
    }

    /**
     * 获取用户增减数据, 最大时间跨度：7;
     * @param $from
     * @param $to
     */
    public function userSummary()
    {
        $this->checkInterval(7);

        $result = $this->stats->userSummary();

        $this->responseList($result);
    }

    /**
     * 获取累计用户数据, 最大时间跨度：7;
     * @param $from
     * @param $to
     */
    public function userCumulate()
    {
        $this->checkInterval(7);

        $result = $this->stats->userCumulate();

        $this->responseList($result);
    }

    /**
     * 获取图文群发每日数据, 最大时间跨度：1;
     * @param $from
     * @param $to
     */
    public function articleSummary()
    {
        $this->checkInterval(1);

        $result = $this->stats->articleSummary();

        $this->responseList($result);
    }

    /**
     * 获取图文群发总数据, 最大时间跨度：1;
     * @param $from
     * @param $to
     */
    public function articleTotal()
    {
        $this->checkInterval(1);

        $result = $this->stats->articleTotal();

        $this->responseList($result);
    }

    /**
     * 获取图文统计数据, 最大时间跨度：3;
     * @param $from
     * @param $to
     */
    public function userReadSummary()
    {
        $this->checkInterval(3);

        $result = $this->stats->userReadSummary();

        $this->responseList($result);
    }

    /**
     * 获取图文统计分时数据, 最大时间跨度：1;
     * @param $from
     * @param $to
     */
    public function userReadHourly()
    {
        $this->checkInterval(1);

        $result = $this->stats->userReadHourly();

        $this->responseList($result);
    }

    /**
     * 获取图文分享转发数据, 最大时间跨度：7;
     * @param $from
     * @param $to
     */
    public function userShareSummary()
    {
        $this->checkInterval(7);

        $result = $this->stats->userShareSummary();

        $this->responseList($result);
    }

    /**
     * 获取图文分享转发分时数据, 最大时间跨度：1;
     * @param $from
     * @param $to
     */
    public function userShareHourly()
    {
        $this->checkInterval(1);

        $result = $this->stats->userShareHourly();

        $this->responseList($result);
    }

    /**
     * 获取消息发送概况数据, 最大时间跨度：7;
     * @param $from
     * @param $to
     */
    public function upstreamMesssageSummary()
    {
        $this->checkInterval(7);

        $result = $this->stats->upstreamMesssageSummary();

        $this->responseList($result);
    }

    /**
     * 获取消息发送分时数据, 最大时间跨度：1;
     * @param $from
     * @param $to
     */
    public function upstreamMesssageHourly()
    {
        $this->checkInterval(1);

        $result = $this->stats->upstreamMesssageHourly();

        $this->responseList($result);
    }

    /**
     * 获取消息发送周数据, 最大时间跨度：30;
     * @param $from
     * @param $to
     */
    public function upstreamMesssageWeekly()
    {
        $this->checkInterval(30);

        $result = $this->stats->upstreamMesssageWeekly();

        $this->responseList($result);
    }

    /**
     * 获取消息发送月数据, 最大时间跨度：30;
     * @param $from
     * @param $to
     */
    public function upstreamMesssageMonthly()
    {
        $this->checkInterval(30);

        $result = $this->stats->upstreamMesssageMonthly();

        $this->responseList($result);
    }

    /**
     * 获取消息发送分布数据, 最大时间跨度：15;
     * @param $from
     * @param $to
     */
    public function upstreamMesssageDistSummary()
    {
        $this->checkInterval(15);

        $result = $this->stats->upstreamMesssageDistSummary();

        $this->responseList($result);
    }

    /**
     * 获取消息发送分布周数据, 最大时间跨度：30;
     * @param $from
     * @param $to
     */
    public function upstreamMesssageDistWeekly()
    {
        $this->checkInterval(30);

        $result = $this->stats->upstreamMesssageDistWeekly();

        $this->responseList($result);
    }

    /**
     * 获取消息发送分布月数据, 最大时间跨度：30;
     * @param $from
     * @param $to
     */
    public function upstreamMesssageDistMonthly()
    {
        $this->checkInterval(30);

        $result = $this->stats->upstreamMesssageDistMonthly();

        $this->responseList($result);
    }

    /**
     * 获取接口分析数据, 最大时间跨度：30;
     * @param $from
     * @param $to
     */
    public function interfaceSummary()
    {
        $this->checkInterval(30);

        $result = $this->stats->interfaceSummary();

        $this->responseList($result);
    }

    /**
     * 获取接口分析分时数据, 最大时间跨度：1;
     * @param $from
     * @param $to
     */
    public function interfaceSummaryHourly()
    {
        $this->checkInterval(1);

        $result = $this->stats->interfaceSummaryHourly();

        $this->responseList($result);
    }

    /**
     * 检测时间间隔
     * @param $from          起始日期
     * @param $to            结束日期
     * @param int $interval 时间间隔
     */
    private function checkInterval($interval = 1)
    {
        $from = $this->request->get('from');
        $to = $this->request->get('to');
        $this->checkParam(compact('from', 'to'));
        if ($to - $from < $interval) {
            Response::_instance()->callback(80001, '超过时间间隔了，请适当调整参数日期');
        }
    }
}