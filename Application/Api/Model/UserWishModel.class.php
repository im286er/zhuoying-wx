<?php
/**
 * Created by PhpStorm.
 * User: 元凯
 * Date: 2015/7/30
 * Time: 14:24
 */
namespace Api\Model;
use Think\Model\RelationModel;
class UserWishModel extends RelationModel{
    protected $_link = array(
        'wish'=>array(
            'mapping_type'  => self::HAS_ONE,
            'class_name'    => 'wish',
            'foreign_key'   => 'wishid',
            'as_fields' => '',
        ),
    );
    
    public function addUserWish($uid,$mid){
        $data = array(
            'uid' => $uid,
            'mid' => $mid,
            'createtime' => time(),
        );

        if($this->create($data)){
            $id = $this->add();

            if ($id) {
                D('Movie')->where('id = %d', $mid)->setInc('wish_total_count');
            }

            return $id;
        }

        $this->retError();
    }
}