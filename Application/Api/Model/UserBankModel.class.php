<?php
namespace Api\Model;
use Think\Model;

class UserBankModel extends Model{
    /* 自动完成规则 */
    protected $_auto = array(
        array('status', 1, self::MODEL_INSERT, 'string'),
        array('createtime', 'time', self::MODEL_INSERT, 'function'),
    );
}