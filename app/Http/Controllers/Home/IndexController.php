<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use EasyWeChat\Factory;

class IndexController extends Controller
{

    /* 首页入口
     * @return page
     */
    public function index()
    {
        //获取配置信息
        $app = app('wechat.official_account');
        $oauth = $app->oauth;
        //如果没有openid则调起网页授权
        if (empty(session('nickname')) || empty(session('openid'))) {
            session(['target_url' => '/']);
            return $oauth->redirect();
        } else {
            return view('home.index.index', ['openid' => session('openid'), 'nickname' => session('nickname')]);
        }
    }

    /* 微信公众号菜单设置
         * @return page
         */
    public function menu()
    {
        return view('home.index.menu');
    }

    /* 微信公众号菜单设置保存
         * @return page
         */
    public function saveButton(Request $request)
    {
        //获取配置信息
        /** @var \EasyWeChat\OfficialAccount\Application $app */
        $app = app('wechat.official_account');
        $post = $request->post();
        $buttons = json_decode($post['menu'], JSON_UNESCAPED_UNICODE)['menu'];
        $menu = [];
        $buttons= array_filter($buttons['button'],function($v){
            if ($v==="" || is_null($v))   //当数组中存在空值和php值时，换回false，也就是去掉该数组中的空值和php值
            {
                return false;
            }
            return true;
        });
        foreach ($buttons as $btn) {
            $btn['sub_button'] = array_filter($btn['sub_button']);
            if (empty($btn['sub_button'])) {
                unset($btn['sub_button']);
            }
            $menu[] = $btn;
        }

        return $app->menu->create($menu);
    }
}
