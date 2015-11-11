<?php
/**
 * Author: NanQi
 * Date: 20150428 15:36
 */
namespace App\Model;
use Think\Model\RelationModel;

class SocialcircleModel extends RelationModel {
    protected $_link = array(
        'party' => array(
            'mapping_type'      =>  self::BELONGS_TO,
            'class_name'        =>  'party',
            'mapping_name'      =>  'party',
            'foreign_key'       =>  'pid',
            'mapping_fields'    =>  'starttime,endtime',
            'as_fields'         =>  'starttime,endtime',
        ),
    );
}