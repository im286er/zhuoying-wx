<?php
/**
 * Author: NanQi
 * Date: 20150428 11:16
 */
namespace App\Model;
use Think\Model\RelationModel;

class OrderModel extends RelationModel {
    protected $_link = array(
        'record' => array(
            'mapping_type'          =>  self::HAS_ONE,
            'class_name'            =>  'orderrecord',
            'mapping_name'          =>  'record',
            'foreign_key'           =>  'orderno',
            'mapping_fields'        =>  'paytime,vertifycode,paymethod,chargeid',
            'as_fields'             =>  'paytime,vertifycode,paymethod,chargeid',
        ),

        'party' => array(
            'mapping_type'          =>  self::HAS_ONE,
            'class_name'            =>  'party',
            'mapping_name'          =>  'party',
            'foreign_key'           =>  'id',
            'mapping_key'           =>  'pid',
            'mapping_fields'        =>  'title',
            'as_fields'             =>  'title',
        ),
    );

    protected $_auto = array (
        array('ordertime','time', self::MODEL_INSERT,'function'),
    );

    /**
     * @author : NanQi
     * @date   : 20150428 13:48
     *
     * @desc     创建订单
     */
    public function createOrder($uid, $pid, $quantity){
        $party = D('Party')->getFullInfo($pid);
        if (empty($party)) {
            $this->retError(201, '未能找到所对应的活动');
        }

        $orderNo = buildOrderNo();

        $data = array(
            'orderno' => $orderNo,
            'uid' => $uid,
            'pid' => $pid,
            'orderprice' => floatval($party['price']) * $quantity,
            'quantity' => $quantity,
            'ordertime' => time(),
        );

        if($this->create($data)){

            $this->add();

            return $orderNo;
        } else {

            return $this->retError();
        }
    }

    /**
     * @author : NanQi
     * @date   : 20150429 19:00
     *
     * @desc     取消订单
     * @param    String $id 订单编号
     * @return   bool
     */
    public function modifyPayStatus($id, $paystatus){
        $data = array(
            'orderno' => $id,
            'paystatus' => $paystatus,
        );

        if($this->create($data)){

            return $this->save();
        } else {

            return $this->retError();
        }
    }
}