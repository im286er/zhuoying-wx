<?php
/**
 * Author: NanQi
 * Date: 20150507 14:08
 */
namespace Weixin\Model;
use Think\Model\RelationModel;

class OrderrecordModel extends RelationModel {
//    protected $_auto = array (
//        array('vertifycode','buildVertifyCode', self::MODEL_INSERT,'callback'),
//    );

    public function buildVertifyCode(){
        return substr(uniqid('', true), 15).substr(microtime(), 2, 2);
    }
}