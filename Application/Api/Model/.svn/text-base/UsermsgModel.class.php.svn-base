<?php
/**
 * Created by PhpStorm.
 * User: 元凯
 * Date: 2015/8/18
 * Time: 16:54
 */

namespace Api\Model;

use Think\Model\RelationModel;

class UsermsgModel extends RelationModel{

    protected $_link = array(
        'user' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'user',
            'foreign_key' => 'uid',
            'as_fields' => 'avatar,nickname',
        )
    );
}