<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/28 0028
 * Time: 下午 2:19
 */

namespace Weixin\Controller;


use Think\Controller;

class KangzhanController extends Controller{

    function resend ()
    {
        $user = D('User');
//        获取用户openid；
        $code = I('get.code');
        //检查token是否有效
        if($code)
        {
            $open = $user->get_userinfo_public($code);
            if(!$open['openid'])
            {
                $url = $user->createOauthUrlForCode('http://weixin.myline.cc/weixin/kangzhan/resend');
                header("Location: $url");
                exit;
            }
            else
            {
                $openid= $open['openid'];
//                $openid= 'oVe75s0I8B7y8wBDPJwDECjq0m2o';
            }
        }
        else
        {
            $url = $user->createOauthUrlForCode('http://weixin.myline.cc/weixin/kangzhan/resend');
            header("Location: $url");
            exit;
        }
        $this->assign('openid',$openid);
//        $this->assign('openid','oVe75s0I8B7y8wBDPJwDECjq0m2o');

//        判断用户是否已经参加过
        $isJoin = M('Kangzhanbufa')->where("openid='$openid' and status=1 and reason=1")->find();
        if($isJoin)
        {
            $this->assign("has",1);
//            $this->assign('id',$isJoin['id']);
//            $this->assign('id',3152);
        }
        else{
           $id = M('Kangzhanbufa')->where("openid='$openid' and status=0 and reason=1")->getField("id");
            $this->assign('id',$id);
        }
        $is = M('Kangzhanbufa')->where("openid='$openid'")->find();
        if(!$is)
        {
            $this->assign("bufa",1);
//            $this->assign('id',3152);
        }
//        else
//        {
//            $this->assign("bufa",1);
//        }
        $this->display();
    }

//    function dati()
//    {
//        $package = D('Jssdk')->getSignPackage();
//        $this->assign('package',$package);
//            $this->display();
//    }
//    function red()
//    {
//        $num = I('num');
////        获取用户openid；
//        $code = I('get.code');
//
//        $user = D('User');
//
//        //通过code获得openid
//        if (empty($code)) {
//            //触发微信返回code码
//            $url = $user->createOauthUrlForCode('http://weixin.myline.cc/weixin/kangzhan/red/num/'.$num);
//            header("Location: $url");
//            exit;
//        }
//
//        $open = $user->get_userinfo_public($code);
//        $openid= $open['openid'];
//        $this->assign('issub',$open['subscribe']);
//        $this->assign('openid',$open['openid']);
//        $package = D('Jssdk')->getSignPackage();
//        $this->assign('package',$package);
//
//
////        保存到数据库
//        if(!$result = M('Kangzhan')->where("openid='$openid'")->find())
//        {
//            $id = M('Kangzhan')->add(array('openid'=>$openid,'create_time'=>time(),'money'=>$num));
//        }
//        else
//        {
//            if($result['is_take'])
//            {
//                $this->assign('has',1);
//            }
//            $id=$result['id'];
//        }
//        $this->assign('id',$id);
//
//
//        $this->assign('num',$num);
//        if($num>=3)
//        {
//            $this->assign('title','你是根正苗红的岛国终结者！');
//        }
//        else{
//            $this->assign('title','还需要加强学习哦！');
//        }
//
//        $this->display();
//    }
//    function math()
//    {
//        $math = mt_rand(0,9);
//        echo 0;
//        if($math>9)
//        {
//            echo 1;
//        }
//        else{
//            $id = I('id');
//            M('Kangzhan')->where("id=$id")->setField(array('money'=>0));
//            echo 0;
//        }
//    }

    function send()
    {
        $id = I('id');
        $openid = I('openid');
        $count = M('Kangzhanbufa')->where("status=1")->count();
        if($count>=990)
        {
            echo json_encode(array('status'=>2,'errmsg'=>"今天的红包已经发完，请关注下次活动！"));
            exit;
        }
        $is = M('Kangzhanbufa')->where("openid='$openid' and status=0 and reason=1")->find();
        if($is)
        {
            $res = D('Redpacket', 'Pay')->send($openid);
            if($res['return_code']=='SUCCESS')
            {
                M('Kangzhanbufa')->where("openid='$openid'")->setField(array('status'=>1));
                echo json_encode(array('status'=>1));

            }
            else
            {
                echo json_encode(array('status'=>0,'errmsg'=>"操作失败，请稍后再试！"));
            }

        }
        else{
            echo json_encode(array('status'=>0,'errmsg'=>"你不符合我们的领取条件哦！"));
        }


    }
}