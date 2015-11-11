<?php

namespace Weixin\Controller;
use Think\Controller;
use Think\Model;

class UserController extends Controller{
    public function qrcode() {
        
        $code = I('get.code');

        $user = D('User');

        //通过code获得openid
        if (empty($code)) {
            //触发微信返回code码
            $url = $user->createOauthUrlForCode("http://weixin.myline.cc/weixin/user/qrcode");
            header("Location: $url");
            exit;
        }

        $isUserinfo = I('get.userinfo');

        $res_token = $user->access_token($code);
        if ($res_token->errcode == '40029') {
            //触发微信返回code码
            $url = $user->createOauthUrlForCode("http://weixin.myline.cc/weixin/user/qrcode");
            header("Location: $url");
            exit;
        }

        if ($isUserinfo) {

            $userinfo = $user->snsapi_userinfo($res_token->access_token, $res_token->openid);
        }
        else {
            $userinfo = $user->public_userinfo($res_token->openid);

            if ($userinfo['subscribe'] == '0') {
                //触发微信返回code码
                $url = $user->createOauthUrlForCode("http://weixin.myline.cc/weixin/user/qrcode?userinfo=1", 'snsapi_userinfo');
                header("Location: $url");
                exit;
            }
        }

        if ($userinfo['errcode']) {
            echo $userinfo['errcode'];
            exit;
        }

        $unionid = $userinfo['unionid'];

        $account = D('Thirdaccount')->where("atype = 'weixin' and token = '$unionid'")->find();
        $uid = $account['user_id'];
        $user = M('User');
        if (!$uid) {
            //TODO 现在直接创建新帐号,以后可能增加帐号绑定功能
            $data = array(
                'nickname' => $userinfo['nickname'],
                'avatar' => $userinfo['headimgurl'],
                'sex' => $userinfo['sex'],
                'newpasstime' => time(),
                'istempuser' => 1,
                'createtime' => time(),
            );

            if ($user->create($data)) {
                $uid = $user->add();
            }

            $data = array(
                'token' => $unionid,
                'user_id' => $uid,
                'atype' => 'weixin'
            );
            $thirdaccount = D('Thirdaccount');
            if ($thirdaccount->create($data)) {
                $thirdaccount->add();
            }
        }

        $info = $user->cache('user_base_'.$uid, 3600 * 24)->find($uid);

        $this->assign('info', $info);

        $this->display();        
    }

    public function coupons() {
        $aid = I('aid');

        if ($aid) {
            $this->assign('aid', $aid);
            $this->display();
        }
        else {
            echo '参数错误';
        }
    }
}