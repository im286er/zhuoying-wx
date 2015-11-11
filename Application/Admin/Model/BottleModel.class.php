<?php
/**
 * Author: NanQi
 * Date: 20150512 21:54
 */
namespace Admin\Model;
use Think\Model\RelationModel;

class BottleModel extends RelationModel {
    protected $_link = array(
        'suser' => array(
            'mapping_type' => self::HAS_ONE,
            'class_name' => 'user',
            'mapping_name' => 'suser',
            'foreign_key' => 'id',
            'mapping_key' => 'suid',
            'mapping_fields' => 'phonenumber',
            'as_fields' => 'phonenumber:s_phonenumber',
        ),

        'sprivacy' => array(
            'mapping_type' => self::HAS_ONE,
            'class_name' => 'privacy',
            'mapping_name' => 'sprivacy',
            'foreign_key' => 'uid',
            'mapping_key' => 'suid',
            'mapping_fields' => 'nickname,sex',
            'as_fields' => 'nickname:s_nickname,sex:s_sex',
        ),

        'ruser' => array(
            'mapping_type' => self::HAS_ONE,
            'class_name' => 'user',
            'mapping_name' => 'ruser',
            'foreign_key' => 'id',
            'mapping_key' => 'ruid',
            'mapping_fields' => 'phonenumber',
            'as_fields' => 'phonenumber:r_phonenumber',
        ),

        'rprivacy' => array(
            'mapping_type' => self::HAS_ONE,
            'class_name' => 'privacy',
            'mapping_name' => 'rprivacy',
            'foreign_key' => 'uid',
            'mapping_key' => 'ruid',
            'mapping_fields' => 'nickname,sex',
            'as_fields' => 'nickname:r_nickname,sex:r_sex',
        ),
    );
}