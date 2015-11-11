<?php
/**
 * Author: NanQi
 * Date: 20150512 21:39
 */
namespace Admin\Model;
use Think\Model\RelationModel;

class SocialcircleUserModel extends RelationModel {
    protected $_link = array(
        'user' => array(
            'mapping_type' => self::HAS_ONE,
            'class_name' => 'user',
            'mapping_name' => 'user',
            'foreign_key' => 'id',
            'mapping_key' => 'uid',
            'mapping_fields' => 'id,phonenumber',
            'as_fields' => 'id:uid,phonenumber',
        ),

        'privacy' => array(
            'mapping_type' => self::HAS_ONE,
            'class_name' => 'privacy',
            'mapping_name' => 'privacy',
            'foreign_key' => 'uid',
            'mapping_key' => 'uid',
            'mapping_fields' => 'nickname,sex',
            'as_fields' => 'nickname,sex',
        ),
    );
}