<?php
/**
 * Author: NanQi
 * Date: 20150506 20:31
 */
namespace Admin\Model;
use Think\Model\RelationModel;

class NewsMovieModel extends RelationModel {
    protected $_link = array(
        'movie' => array(
            'mapping_type'      =>  self::HAS_ONE,
            'class_name'        =>  'movie',
            'mapping_name'      =>  'movie',
            'foreign_key'       =>  'id',
            'mapping_key'       =>  'mid',
            'mapping_fields'    =>  'mname',
            'as_fields'         =>  'mname',
        ),
    );

    /* 自动验证规则 */
    protected $_validate = array(
        array('mid', 'require', '电影ID不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('mid', 'number', '电影ID格式不正确', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('starttime', 'require', '开始时间不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('starttime', 'datetime', '开始时间格式不正确', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('endtime', 'require', '结束时间不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('endtime', 'datetime', '结束时间格式不正确', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
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