<?php
/**
 * Author: NanQi
 * Date: 20150513 13:40
 */
namespace Admin\Model;
use Think\Model\RelationModel;

class OrderModel extends RelationModel {
    protected $_link = array(
        'party' => array(
            'mapping_type' => self::HAS_ONE,
            'class_name' => 'party',
            'mapping_name' => 'party',
            'foreign_key' => 'id',
            'mapping_key' => 'pid',
            'mapping_fields' => 'title',
            'as_fields' => 'title',
        ),

        'user' => array(
            'mapping_type' => self::HAS_ONE,
            'class_name' => 'user',
            'mapping_name' => 'user',
            'foreign_key' => 'id',
            'mapping_key' => 'uid',
            'mapping_fields' => 'phonenumber',
            'as_fields' => 'phonenumber',
        ),
    );
}