<?php
/**
 * Created by PhpStorm.
 * User: 元凯
 * Date: 2015/8/14
 * Time: 10:17
 */

namespace Api\Model;

use Think\Model\RelationModel;

class ActivityUserModel extends RelationModel
{
    protected $_link = array(
        'host_user' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'user',
            'foreign_key' => 'uid',
            'as_fields' => 'phonenumber',
        ),

        'activity' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'activity',
            'foreign_key' => 'aid',
            'as_fields' => 'title',
        ),
    );

    public function addJoin($uid, $aid) {
        
        $data = array(
            'uid' => $uid,
            'aid' => $aid,
            'issignin' => 0,
            'createtime' => time(),
        );

        if ($this->create($data)) {
            $this->add();
        }

        D('Activity')->where('id = %d', $aid)->setInc('anumber');

        $activity = D('Activity')->find($aid);

        D('Push', 'Logic')->reminderUser($uid, 3, '你已成功报名['.$activity['title'].']活动。');

        if ($activity['anumber'] >= $activity['upper']) {
            D('Activity')->where('id = %d', $aid)->setField('astatus', '3');
        }
        elseif ($activity['anumber'] >= $activity['floor']) {
            D('Push', 'Logic')->reminderUser($activity['uid'], 4, '你发起的['.$activity['title'].']活动已达到下限人数，可以开始准备活动了。');
        }
    }
}