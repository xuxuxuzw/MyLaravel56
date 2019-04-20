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
            return view('home.index.index', ['openid' => session('openid'),'nickname'=>session('nickname')]);
        }
    }


}
