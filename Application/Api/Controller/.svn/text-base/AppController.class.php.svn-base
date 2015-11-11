<?php
/**
 * Created by PhpStorm.
 * User: 元凯
 * Date: 2015/8/14
 * Time: 11:20
 */

namespace Api\Controller;

use Think\Controller;
use Think\Model;

class AppController extends Controller
{
    public function uploadImage()
    {
        M()->validation(array(
            array('file', 'require', '图片文件不能为空', Model::MUST_VALIDATE, 'regex'),
            array('timestamp', 'require', '时间戳不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $timestamp = I('timestamp');

        $fileName = $timestamp . '.jpg';

        $fileUrl = D('Qiniu', 'Logic')->upload($fileName, I('file'));

        //$this->retSuccess($fileUrl);
        //图像做居中裁剪
        //$fileUrl = $fileUrl.'?imageView2/1/w/128/h/128';

        $backInfo = array(
            'timestamp' => $timestamp,
            'url' => $fileUrl,
        );

        $this->retSuccess($backInfo);
    }

    public function payment()
    {
        M()->validation(array(
            array('uid', 'require', '用户不能为空', Model::MUST_VALIDATE, 'regex'),
            array('aid', 'require', '活动不能为空', Model::MUST_VALIDATE, 'regex'),
            // array('paynumber', 'require', '报名人数不能为空', Model::MUST_VALIDATE, 'regex'),
            array('paymethod', 'require', '支付方式不能为空', Model::MUST_VALIDATE, 'regex'),
            array('amount', 'require', '总金额不能为空', Model::MUST_VALIDATE, 'regex'),
            array('subject', 'require', '商品名称不能为空', Model::MUST_VALIDATE, 'regex'),
            array('body', 'require', '商品描述不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $body=I("body");
        $subject=I("subject");
        $total=I("amount");
        $aid=I("aid");
        $paymethod=I("paymethod");
        $uid=I("uid");
        $time=time();
        $orderno=$aid."_".$time;

        $activity = D('Activity')->find($aid);

        if ($activity['atype'] != '2' && $activity['anumber'] >= $activity['upper']) {
            $this->retError('活动报名人数已满');
        }

        if ($activity['astatus'] != 2 && $activity['astatus'] != 4) {
            if ($activity['atype'] == '2' && $activity['astatus'] == '3') {
                //排除大型活动人满情况
            }
            else {
                $this->retError('活动状态错误');
            }
        }

        if ($activity['money'] == 0) {
            $this->retError('人均费用必须大于0');
        }

        $cnt = D('ActivityUser')->where("aid = '$aid' and uid = '$uid'")->count();
        if ($cnt) {
            $this->retError('不能重复报名');
        }

        // $cnt=D('order')->where("uid=%d AND aid=%d AND paystatus=1",$uid,$aid)->find();

        // if($cnt){
        //     $this->retError("您已经支付过该订单!");
        // }

        $data=array(
            'orderno' => $orderno,
            'time' => $time,
            'uid' => $uid,
            'subject' => $subject,
            'total' => $total,
            'aid' => $aid,
            'body' => $body,
            'paymethod' => $paymethod,
        );

        if($paymethod=="alipay"){

            $returnData=D('Ali','Pay')->payOrder($data);
        }elseif($paymethod=="wxpay"){
            
            $returnData=D('Wx','Pay')->payOrder($data);
        }

        $this->retSuccess($returnData);
    }

    public function notify()
    {
        $data=I("");

        // setLog(json_encode($data));

        $orderno=$data['out_trade_no'];
        $success=$data['trade_status'];

        if($success=="TRADE_SUCCESS"){

            $orderAlipay = M('OrderAlipay');
            $cnt = $orderAlipay->where("out_trade_no = '$orderno'")->count();
            if ($cnt) {
                echo 'success';
                exit;
            }

            $orderAlipay->add($data);

            $info['paytime']=time();
            $info['orderno']=$orderno;
            $info['money']=$data['total_fee'] * 100;
            $flg = D('Order')->updateOrder($info);
            if ($flg) {
                echo 'success';
            }
        }

        echo 'error';
    }

    public function wx_notify() {
        D('Wx','Pay')->notify();
    }

    public function feedback() {
        M()->validation(array(
            array('uid', 'require', '用户不能为空', Model::MUST_VALIDATE, 'regex'),
            array('otype', 'require', '系统类型不能为空', Model::MUST_VALIDATE, 'regex'),
            array('ptype', 'require', '手机型号不能为空', Model::MUST_VALIDATE, 'regex'),
            array('content', 'require', '反馈内容不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $feedback = D('Feedback');

        if ($feedback->create()) {
            $feedback->add();
        }

        $this->retSuccess();
    }
}