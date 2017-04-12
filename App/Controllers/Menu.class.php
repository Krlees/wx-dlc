<?php
namespace App\Controllers;

// +----------------------------------------------------------------------
// | 自定义菜单
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------
use App\Responses\Response;

class Menu extends Common
{

    protected $menu;

    public function __construct()
    {
        parent::__construct();
        $this->menu = $this->app->menu;
    }

    /**
     * [查询菜单]
     * @Author: Krlee
     *
     */
    public function all()
    {
        $result = $this->menu->all();
        if( !isset($result->errcode)){
            Response::_instance()->callback(0,'',JsonToArr($result));
        }

        echo $result;
        exit;
    }

    /**
     * [获取自定义菜单]
     * @Author: Krlee
     *
     */
    public function current()
    {
        $result = $this->menu->current();
        if( !isset($result->errcode)){
            Response::_instance()->callback(0,'',JsonToArr($result));
        }

        echo $result;
        exit;
    }

    /**
     * [添加普通菜单]
     *      传入$matchRule参数代表个性化菜单
     * @Author: Krlee
     *
     * @param array $buttons
     */
    public function create()
    {
        $buttons = $this->request->get('buttons');
        $matchRule = $this->request->get('matchRule',[]);
        if( !$buttons || !is_array($buttons)){
            Response::_instance()->callback(1004);
        }
        elseif( $matchRule && !is_array($matchRule)){
            Response::_instance()->callback(1004);
        }

        $result = $this->menu->add($buttons,$matchRule);

        echo $result;
        exit;
    }

    /**
     * [删除菜单]
     * @Author: Krlee
     *
     */
    public function destroy()
    {
        $menuId = $this->request->get('menuId');

        $result = $this->menu->destroy($menuId);

        echo $result;
        exit;
    }

}