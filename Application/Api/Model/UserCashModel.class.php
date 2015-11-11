<?php
namespace Api\Model;
use Think\Model;

class UserCashModel extends Model{
    /* 自动完成规则 */
    protected $_auto = array(
        array('status', 1, self::MODEL_INSERT, 'string'),
        array('cash_status', 0, self::MODEL_INSERT, 'string'),
        array('createtime', 'time', self::MODEL_INSERT, 'function'),
    );
}