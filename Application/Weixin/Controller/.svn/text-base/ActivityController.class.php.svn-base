<?php

namespace Weixin\Controller;
use Think\Controller;
use Think\Model;

class ActivityController extends Controller{
    public function index() {
        $aid = I('id');
        $code = I('get.code');

        //判断是否微信端
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') !== false) {
            $user = D('User');
           //通过code获得openid
            if (empty($code)) {
                //触发微信返回code码
                $url = $user->createOauthUrlForCode("http://weixin.myline.cc/weixin/activitys/$aid");
                header("Location: $url");
                exit;
            } 

            $isUserinfo = I('get.userinfo');

            $res_token = $user->access_token($code);
            if ($res_token->errcode == '40029') {
                //触发微信返回code码
                $url = $user->createOauthUrlForCode("http://weixin.myline.cc/weixin/activitys/$aid");
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
                    $url = $user->createOauthUrlForCode("http://weixin.myline.cc/weixin/activitys/$aid?userinfo=1", 'snsapi_userinfo');
                    header("Location: $url");
                    exit;
                }
            }

            $subscribe = $userinfo['subscribe'];

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

            $package = D('Jssdk')->getSignPackage();
            $this->assign('package', $package);

            $this->assign('wxData', $userinfo);
            $this->assign('uid', $uid);
            
        }

        $activity = D('activity')->relation(true)->find($aid);

        if(!$activity) {
            $this->retError();
        }

        $mid = $activity['mid'];

        $button_title = '我&nbsp;要&nbsp;报&nbsp;名';
        $button_enable = 1;

        $is_finish = 0;

        $astatus = $activity['astatus'];
        if ($astatus == 3) {
            $button_title = '活&nbsp;动&nbsp;已&nbsp;满&nbsp;员';
            $button_enable = 0;
        }
        else if ($astatus == 5 || $astatus == 6 || $astatus == -3 || $astatus == -2 || $astatus == -1) {
            $button_title = '活&nbsp;动&nbsp;已&nbsp;结&nbsp;束';
            $button_enable = 0;
        }

        if ($uid) {
            $info = D('ActivityUser')->where("aid = $aid and uid = $uid")->find();
            if ($info) {

                if ($activity['uid'] == '10012') {
                   if ($info['signintime'] == 0) {
                        $button_title = '分&nbsp;享&nbsp;获&nbsp;取&nbsp;优&nbsp;惠&nbsp;券';
                        $button_enable = 0;
                    }
                    else {
                        $button_title = '查&nbsp;看&nbsp;优&nbsp;惠&nbsp;券';
                        $is_finish = 1;
                    } 
                }
                else {
                        $button_title = '查&nbsp;看&nbsp;二&nbsp;维&nbsp;码';
                        $is_finish = 2;
                }
            }
        }

        $same_activitys = M()->query("SELECT t1.*, t2.picture FROM (
            SELECT a.id as aid, a.uid, a.mid, a.title, a.money, a.starttime, a.phone, u.nickname, u.avatar, m.title as movie_name, s.address, 
                   s.latitude, s.longitude, a.astatus
            FROM t_activity a, t_movie m, t_user u, t_site s
            where a.mid = m.id and a.uid = u.id and a.sid = s.id and a.mid = $mid and a.id <> $aid and a.astatus = 2 limit 5
            ) t1 LEFT JOIN (select aid, min(picture) as picture from t_activity_content group by aid) t2 on t1.aid = t2.aid");

        $join_users = M()->query("SELECT t1.id, avatar, nickname from t_user t1, t_activity_user t2 where t1.id = t2.uid and t2.aid = $aid order by t2.createtime desc limit 10");

        $this->assign('activity', $activity);
        $this->assign('same_activitys', $same_activitys);
        $this->assign('join_users', $join_users);
        $this->assign('join_users_count', count($join_users));
        $this->assign('button_title', $button_title);
        $this->assign('button_enable', "$button_enable");
        $this->assign('is_finish', "$is_finish");
        $this->assign('subscribe', "$subscribe");

        //判断是否微信端
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') !== false) {
            $this->display();
        }
        else {
            $this->display("index2");
        }
    }

    public function share() {
        $aid = I('aid');
        $uid = I('uid');

        $activity = D('activity')->relation(true)->find($aid);

        if(!$activity) {
            $this->retError();
        }

        $package = D('Jssdk')->getSignPackage();
        $this->assign('package', $package);

        $join_users = M()->query("select t1.id, avatar, nickname from t_user t1, t_activity_user t2 where t1.id = t2.uid and t2.aid = $aid");

        $this->assign('uid', $uid);
        $this->assign('activity', $activity);
        $this->assign('join_users', $join_users);
        $this->display();
    }

    public function sendCoupons() {
        M()->validation(array(
            array('openid', 'require', 'openid不能为空', Model::MUST_VALIDATE, 'regex'),
            array('unionid', 'require', 'unionid不能为空', Model::MUST_VALIDATE, 'regex'),
            array('aid', 'require', 'aid不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $aid = I('aid');
        $openid = I('openid');
        $unionid = I('unionid');

        $activity = D('Activity')->find($aid);
        if ($activity['uid'] != '10012') {
            $this->retError("分享成功");
        }

        $account = D('Thirdaccount')->where("atype = 'weixin' and token = '$unionid'")->find();
        $uid = $account['user_id'];
        if (!$uid) {
            $this->retError('没有对应的帐号信息');
        }

        $info = D('ActivityUser')->where("aid = '$aid' and uid = '$uid'")->find();
        if ($info) {
            if ($info['signintime'] == 0) {

                $id = $info['id'];

                D('ActivityUser')->where("id = '$id'")->setField('signintime', time());

                $activity = D('Activity')->find($aid);

                $user = D('User')->cache('user_base_'.$uid, 3600 * 24)->find($uid);

                $sid = $activity['sid'];
                $site = D('Site')->cache('site_base_'.$sid, 3600 * 24)->find($sid);

                $order = D('Order')->where("aid = '$aid' and uid = '$uid' and paystatus = 1")->find();

                $res = D('Template')->new_enroll2($openid, $user['nickname'], $activity['title'], $order['orderno'], $activity['starttime'], $activity['endtime'], $site['address'], $aid);
                $this->retSuccess();
            }
            else {
                $this->retSuccess();
            }
        }
        else {
            $this->retError('请报名活动后分享');
        }
    }

    public function getPayParameters() {
        M()->validation(array(
            array('openid', 'require', 'openid不能为空', Model::MUST_VALIDATE, 'regex'),
            array('unionid', 'require', 'unionid不能为空', Model::MUST_VALIDATE, 'regex'),
            array('aid', 'require', 'aid不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $aid = I('aid');
        $openid = I('openid');
        $unionid = I('unionid');

        $jsApi = D('Jsapi', 'Pay');
        //=========步骤2：使用统一支付接口，获取prepay_id============
        //使用统一支付接口
        $unifiedOrder = D('Unified', 'Pay');

        $activity = D('Activity')->find($aid);

        if ($activity['astatus'] != 2 && $activity['astatus'] != 4) {
            $this->retError('活动状态错误');
        }

        if ($activity['money'] == 0) {
            $this->retError('人均费用不能为0');
        }

        $account = D('Thirdaccount')->where("atype = 'weixin' and token = '$unionid'")->find();
        $uid = $account['user_id'];
        if (!$uid) {
        }

        $cnt = D('ActivityUser')->where("aid = '$aid' and uid = '$uid'")->count();
        if ($cnt) {
            $this->retError('不能重复报名');
        }

        //设置统一支付接口参数
        //设置必填参数
        //appid已填,商户无需重复填写
        //mch_id已填,商户无需重复填写
        //noncestr已填,商户无需重复填写
        //spbill_create_ip已填,商户无需重复填写
        //sign已填,商户无需重复填写

//        A('App/Order')

        $unifiedOrder->setParameter("openid", "$openid");//用户openid
        $unifiedOrder->setParameter("body", '捉影-'.$activity['title']);//商品描述
        //自定义订单号，此处仅作举例
        $unifiedOrder->setParameter("out_trade_no", buildOrderNo());//商户订单号             $unifiedOrder->setParameter("product_id","$product_id");//商品ID

        $unifiedOrder->setParameter("total_fee", $activity['money']);//总金额
        $unifiedOrder->setParameter("notify_url", C('WX_NOTIFY_URL'));//通知地址
        $unifiedOrder->setParameter("trade_type", "JSAPI");//交易类型
        //非必填参数，商户可根据实际情况选填
        //$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号
        //$unifiedOrder->setParameter("device_info","XXXX");//设备号

        $attach = array(
            'uid' => $uid,
            'aid' => $aid
        );

        $attach = json_encode($attach);

        $currentTime = time();

        $unifiedOrder->setParameter("attach",$attach);//附加数据
        $unifiedOrder->setParameter("time_start", date('YmdHis', $currentTime));//交易起始时间
        $unifiedOrder->setParameter("time_expire",date('YmdHis', $currentTime + 60 * 5));//交易结束时间
        //$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记
        //$unifiedOrder->setParameter("openid","XXXX");//用户标识

        $prepay_id = $unifiedOrder->getPrepayId();
        //=========步骤3：使用jsapi调起支付============
        $jsApi->setPrepayId($prepay_id);

        $jsApiParameters = $jsApi->getParameters();
        $this->retSuccess($jsApiParameters);
    }

    public function notify(){
        /*
         *
<xml> <appid><![CDATA[wx4e81aacd38a27424]]></appid> <attach><![CDATA[{"uid":"74","openid":"oVe75s2v_gh8E_ed5-MS57NVVXhg","aid":"1"}]]></attach> <bank_type><![CDATA[CFT]]></bank_type> <cash_fee><![CDATA[1]]></cash_fee> <fee_type><![CDATA[CNY]]></fee_type> <is_subscribe><![CDATA[Y]]></is_subscribe> <mch_id><![CDATA[1247136201]]></mch_id> <nonce_str><![CDATA[z616xlvfxant9iej7xx7cxnxhw7l4bl1]]></nonce_str> <openid><![CDATA[oVe75s2v_gh8E_ed5-MS57NVVXhg]]></openid> <out_trade_no><![CDATA[2015092150101971]]></out_trade_no> <result_code><![CDATA[SUCCESS]]></result_code> <return_code><![CDATA[SUCCESS]]></return_code> <sign><![CDATA[181D406FF7A8D2F23F78EEF46DD46FF7]]></sign> <time_end><![CDATA[20150921212944]]></time_end> <total_fee>1</total_fee> <trade_type><![CDATA[JSAPI]]></trade_type> <transaction_id><![CDATA[1000040089201509210951012566]]></transaction_id> </xml>
         */

        //使用通用通知接口
        $notify = D('Server', 'Pay');

        //存储微信的回调
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        setLog($xml);
        $notify->saveData($xml);

        //验证签名，并回应微信。
        //对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
        //微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
        //尽可能提高通知的成功率，但微信不保证通知最终能成功。
        if($notify->checkSign() == FALSE){
            $notify->setReturnParameter("return_code","FAIL");//返回状态码
            $notify->setReturnParameter("return_msg","签名失败");//返回信息
        }else{
            $notify->setReturnParameter("return_code","SUCCESS");//设置返回码
        }

        if($notify->checkSign() == TRUE)
        {
            if ($notify->data["return_code"] == "FAIL") {
                //通信出错
            }
            elseif($notify->data["result_code"] == "FAIL"){
                //业务出错
            }
            else{
                //支付成功
                $data = $notify->getData();

                $attach = $data['attach'];
                $attach = json_decode($attach, true);

                $aid = $attach['aid'];
                $uid = $attach['uid'];
                

                if (!$aid || !$uid) {
                    $notify->setReturnParameter("return_code","FAIL");//返回状态码
                    $notify->setReturnParameter("return_msg","parameter error");//返回信息
                    $returnXml = $notify->returnXml();
                    echo $returnXml;
                    exit;
                }

                $activity = D('Activity')->find($aid);

                $user = D('User')->cache('user_base_'.$uid, 3600 * 24)->find($uid);

                $sid = $activity['sid'];
                $site = D('Site')->cache('site_base_'.$sid, 3600 * 24)->find($sid);

                if (!$user || !$site || $activity['money'] != $data['total_fee']) {
                    $notify->setReturnParameter("return_code","FAIL");//返回状态码
                    $notify->setReturnParameter("return_msg","user or site or money error");//返回信息
                    $returnXml = $notify->returnXml();
                    echo $returnXml;
                    exit;
                }

                $orderno = $data['out_trade_no'];

                $order = D('Order');
                $cnt = $order->where("orderno = '$orderno'")->count();
                if ($cnt) {
                    $notify->setReturnParameter("return_code","FAIL");//返回状态码
                    $notify->setReturnParameter("return_msg","order exists");//返回信息
                    $returnXml = $notify->returnXml();
                    echo $returnXml;
                    exit;
                }

                $orderData = array(
                    'orderno' => $orderno,
                    'aid' => $aid,
                    'uid' => $uid,
                    'ordertime' => time(),
                    'paytime' => tounix($data['time_end']),
                    'paymethod' => '__weixin__',
                    'orderprice' => $data['total_fee'],
                    'quantity' => 1,
                    'paystatus' => 1,
                );
                
                if ($order->create($orderData)) {
                    $order->add();
                }

                D("ActivityUser")->addJoin($uid, $aid);

                $openid = $data['openid']; 

                //捉影万圣节活动
                if ($activity['uid'] != '10012') {
                    $res = D('Template')->new_enroll($openid, $user['nickname'], $activity['title'], $orderno, $activity['starttime'], $site['address']);
                }
                
                $returnXml = $notify->returnXml();
                echo $returnXml;
                exit;
            }

            //商户自行增加处理流程,
            //例如：更新订单状态
            //例如：数据库操作
            //例如：推送支付完成信息
        }

        $returnXml = $notify->returnXml();
        echo $returnXml;
    }

    //加入活动
    public function join() {
        M()->validation(array(
            array('openid', 'require', 'openid不能为空', Model::MUST_VALIDATE, 'regex'),
            array('uid', 'require', '用户不能为空', Model::MUST_VALIDATE, 'regex'),
            array('aid', 'require', '活动不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $aid = I('aid');
        $uid = I('uid');


        $activity = D('Activity')->field('id,sid,title,starttime,astatus,money')->find($aid);

        $user = D('User')->cache('user_base_'.$uid, 3600 * 24)->find($uid);

        $sid = $activity['sid'];
        $site = D('Site')->cache('site_base_'.$sid, 3600 * 24)->find($sid);

        if (!$activity || !$user || !$site) {
            $this->retError('信息不完整');
        }

        if ($activity['astatus'] != 2 && $activity['astatus'] != 4) {
            $this->retError('活动状态错误');
        }

        if ($activity['money'] != 0) {
            $this->retError('人均费用必须为0');
        }

        $cnt = D('ActivityUser')->where("aid = '$aid' and uid = '$uid'")->count();
        if ($cnt) {
            $this->retError('不能重复报名');
        }

        D("ActivityUser")->addJoin($uid, $aid);

        $openid = I('openid');
        $orderno = 'N'.buildOrderNo();

        $res = D('Template')->new_enroll($openid, $user['nickname'], $activity['title'], $orderno, $activity['starttime'], $site['address']);


        $this->retSuccess();
    }

}