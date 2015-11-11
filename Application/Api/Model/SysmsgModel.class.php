<?php
/**
 * Created by PhpStorm.
 * User: 元凯
 * Date: 2015/8/18
 * Time: 17:41
 */


namespace Api\Model;

use Think\Model\RelationModel;

class SysmsgModel extends RelationModel{

    protected $_link = array(
        'user' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'user',
            'foreign_key' => 'uid',
            'as_fields' => 'avatar,nickname',
        )
    );
}