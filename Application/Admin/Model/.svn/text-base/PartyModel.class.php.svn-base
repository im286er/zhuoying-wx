<?php
/**
 * Author: NanQi
 * Date: 20150423 15:32
 */
namespace Admin\Model;
use Think\Model\RelationModel;

class PartyModel extends RelationModel {
    protected $_link = array(
        'fares' => array(
            'mapping_type'      =>  self::HAS_ONE,
            'class_name'        =>  'fares',
            'mapping_name'      =>  'fares',
            'foreign_key'       =>  'id',
            'mapping_key'       =>  'fid',
            'mapping_fields'    =>  'price',
            'as_fields'         =>  'price',
        ),

        'cinema' => array(
            'mapping_type'      =>  self::HAS_ONE,
            'class_name'        =>  'cinema',
            'mapping_name'      =>  'cinema',
            'foreign_key'       =>  'id',
            'mapping_key'       =>  'cid',
            'mapping_fields'    =>  'cname',
            'as_fields'         =>  'cname',
        ),
    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('starttime', 'getStartTime', self::MODEL_BOTH,'callback'),
        array('endtime', 'getEndTime', self::MODEL_BOTH,'callback'),
    );

    /**
     * 开始时间不写则取当前时间
     * @return int 时间戳
     * @author NanQi
     */
    protected function getStartTime(){
        $time    =   I('post.starttime');
        return $time?strtotime($time):NOW_TIME;
    }

    /**
     * 开始时间不写则取当前时间
     * @return int 时间戳
     * @author NanQi
     */
    protected function getEndTime(){
        $time    =   I('post.endtime');
        return $time?strtotime($time):NOW_TIME;
    }
}