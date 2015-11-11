<?php
/**
 * Author: NanQi
 * Date: 20150504 17:47
 */
namespace Admin\Model;
use Think\Model;

class MovieModel extends Model {

    /* 自动验证规则 */
    protected $_validate = array(
        array('mname', 'require', '电影名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('mname', '1,200', '电影名称长度不正确', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
        array('pstate', 'require', '上映状态不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('pstate', '0,6', '上映状态不正确', self::MUST_VALIDATE, 'between', self::MODEL_BOTH),
        array('ratingstar', 'currency', '评分类型不正确', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('duration', 'number', '片长类型不正确', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );
}