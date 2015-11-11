<?php

namespace Weixin\Controller;
use Think\Controller;
use Think\Model;

class RedpacketController extends Controller{
    public function index() {
        $code = I('get.code');

        $user = D('User');

        //通过code获得openid
        if (empty($code)) {
            //触发微信返回code码
            $url = $user->createOauthUrlForCode('http://weixin.myline.cc/weixin/Redpacket');
            header("Location: $url");
            exit;
        }

        $userinfo = $user->get_userinfo_public($code);

        if ($userinfo['errcode']) {
            //触发微信返回code码
            $url = $user->createOauthUrlForCode('http://weixin.myline.cc/weixin/Redpacket');
            header("Location: $url");
            exit;
        }

        $openid = $userinfo['openid'];

        //dump($openid);

        $res = D('Redpacket', 'Pay')->send($openid);

        $this->assign('res', $res);
        $this->assign('userinfo', $userinfo);
        $this->display();
    }
}