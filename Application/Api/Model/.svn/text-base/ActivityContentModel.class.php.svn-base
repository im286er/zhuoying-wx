<?php
/**
 * Created by PhpStorm.
 * User: 元凯
 * Date: 2015/8/14
 * Time: 16:12
 */
namespace Api\Model;

use Think\Model\RelationModel;

class ActivityContentModel extends RelationModel
{
    protected $_link = array(
        'images' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'images',
            'foreign_key' => 'picture',
            'as_fields' => 'url',
        ),
    );
}