<?php
/**
 * Created by PhpStorm.
 * User: 元凯
 * Date: 2015/8/20
 * Time: 16:34
 */

namespace Api\Model;

use Think\Model\RelationModel;

class OrderModel extends RelationModel{
    public function addOrder($info){
        $data=array(
            'orderno' => $info['orderno'],
            'uid' => $info['uid'],
            'orderprice' => $info['total'],
            'aid' => $info['aid'],
            'paymethod' => $info['paymethod'],
            'ordertime' => $info['time'],
            'paystatus' => 0,
        );

        return $this->add($data);
    }

    public function updateOrder($info){
        
        $order=$this->where("orderno='%s'",$info['orderno'])->find();

        if ($order['paystatus'] == 1) {
            return true;
        }

        if (!$order || $order['orderprice'] != $info['money']) {
            return false;
        }
        $aid = $order['aid'];
        $uid = $order['uid'];

        D("ActivityUser")->addJoin($uid, $aid);

        $data=array(
            'paytime' => $info['paytime'],
            'paystatus' => 1,
        );

        $activity = D('Activity')->cache("activity_base_$aid")->find($aid);
        if ($activity['atype'] == '1') {
            $code = buildVerifyCode();
            $data['verify_code'] = $code;

            $user = D('User')->cache("user_base_$uid")->find($uid);

            $time = date('Y-m-d', $activity['endtime']);

            D('Sms', 'Logic')->sendOrderVerify($user['phonenumber'], $activity['title'], $code, $time);
        }

        $this->where("orderno='%s'",$info['orderno'])->save($data);


        return true;
    }
}