<?php
/**
 * Author: NanQi
 * Date: 20150506 10:57
 */
namespace Admin\Model;
use Think\Model;

class NewsModel extends Model {
    /* 自动验证规则 */
    protected $_validate = array(
        array('title', 'require', '标题不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', '1,200', '标题长度不正确', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
        array('atype', 'require', '沙龙类型不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('atype', '0,1', '沙龙类型不正确', self::MUST_VALIDATE, 'between', self::MODEL_BOTH),
        array('recommend', 'require', '推荐语不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('recommend', '1,200', '推荐语长度不正确', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
        array('pushcontent', 'require', '推送内容不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('pushcontent', '1,200', '推送内容长度不正确', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('status', 1, self::MODEL_INSERT, 'string'),
        array('createtime', 'time', self::MODEL_BOTH, 'function'),
        array('createuid', 'session', self::MODEL_INSERT, 'function', 'user_auth.uid'),
        array('sendstate', 0, self::MODEL_INSERT, 'string'),
    );


}