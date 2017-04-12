<?php


namespace App\Controllers;


use App\Responses\Response;

class Poi extends Common
{
    protected $poi;

    public function __construct()
    {
        parent::__construct();
        $this->poi = $this->app->poi;
    }

    /**
     * 创建门店
     * @param Request $request
     */
    public function create()
    {
//        $info = array(
//            "sid"             => "33788392",
//            "business_name"   => "麦当劳",
//            "branch_name"     => "艺苑路店",
//            "province"        => "广东省",
//            "city"            => "广州市",
//            "district"        => "海珠区",
//            "address"         => "艺苑路 11 号",
//            "telephone"       => "020-12345678",
//            "categories"      => array("美食,快餐小吃"),
//            "offset_type"     => 1,
//            "longitude"       => 115.32375,
//            "latitude"        => 25.097486,
//            "photo_list"      => array(
//                array("photo_url" => "https://XXX.com"),
//                array("photo_url" => "https://XXX.com"),
//            ),
//            "recommend"       => "麦辣鸡腿堡套餐,麦乐鸡,全家桶",
//            "special"         => "免费 wifi,外卖服务",
//            "introduction"    => "麦当劳是全球大型跨国连锁餐厅,1940 年创立于美国,在世界上大约拥有 3  万间分店。主要售卖汉堡包,以及薯条、炸鸡、汽水、冰品、沙拉、水果等 快餐食品",
//            "open_time"       => "8:00-20:00",
//            "avg_price"       => 35,
//        );
        $info = $this->request->get('info') or Response::_instance()->callback(1004);
        if( !is_array($info)){
            Response::_instance()->callback(1008);
        }

        $result = $this->poi->create($info);

        echo $result;
        exit;
    }

    /**
     * 获取指定门店信息
     * @param $poiId
     * @return mixed
     */
    public function get()
    {
        $poiId = $this->request->get('poi_id') or Response::_instance()->callback(1004);
        $result = $this->poi->get($poiId);

        echo $result;
        exit;
    }

    /**
     * 获取门店列表
     * @param Request $request
     * @return mixed
     */
    public function getList(Request $request)
    {
        $begin = $this->request->get('begin',0);
        $limit = $this->request->get('limit',10);

        $result = $this->poi->lists($begin, $limit);
        if( !isset($result->errcode)){
            Response::_instance()->callback(0,'',JsonToArr($result));
        }

        echo $result;
        exit;
    }

    /**
     * 更新门店信息
     * @param $poiId
     * @param Request $request
     * @return mixed
     */
    public function update($poiId)
    {
//        $data = array(
//            "telephone" => "020-12345678",
//            "recommend" => "麦辣鸡腿堡套餐,麦乐鸡,全家桶",
//            //...
//        );
        $poiId = $this->request->get('poi_id') or Response::_instance()->callback(1004);
        $data  = $this->request->get('data') or Response::_instance()->callback(1004);
        if( !is_array($data)){
            Response::_instance()->callback(1008);
        }

        $result = $this->poi->update($poiId, $data);

        echo $result;
        exit;
    }

    /**
     * 删除门店信息
     * @param $poiId
     * @param Request $request
     * @return mixed
     */
    public function delete($poiId)
    {
        $poiId = $this->request->get('poi_id') or Response::_instance()->callback(1004);

        $result = $this->poi->delete($poiId);

        echo $result;
        exit;
    }
}