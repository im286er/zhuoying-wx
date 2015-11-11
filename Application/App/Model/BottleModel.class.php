<?php
/**
 * Author: NanQi
 * Date: 20150430 11:16
 */
namespace App\Model;
use Think\Model\RelationModel;

class BottleModel extends RelationModel {

    protected $_link = array(
        'privacy' => array(
            'mapping_type'              =>  self::BELONGS_TO,
            'class_name'                =>  'privacy',
            'mapping_name'              =>  'privacy',
            'foreign_key'               =>  'suid',
            'relation_foreign_key'      =>  'uid',
            'mapping_fields'            =>  'nickname,sex,avatar,job,signature,birthday',
            'as_fields'                 =>  'nickname,sex,avatar,job,signature,birthday',
        ),
        'rprivacy' => array(
            'mapping_type'              =>  self::BELONGS_TO,
            'class_name'                =>  'privacy',
            'mapping_name'              =>  'rprivacy',
            'foreign_key'               =>  'ruid',
            'relation_foreign_key'      =>  'uid',
            'mapping_fields'            =>  'nickname,sex,avatar,job,signature,birthday',
            'as_fields'                 =>  'nickname:r_nickname,sex:r_sex,avatar:r_avatar,job:r_job,signature:r_signature,birthday:r_birthday',
        ),
    );

    /**
     * @author : NanQi
     * @date   : 20150430 14:51
     *
     * @desc     扔瓶子
     * @param    int $suid 发送用户ID
     * @param    int $scontent 发送内容
     * @param    int $ruid 接受用户ID
     * @param    int $scid 社交圈ID
     * @return   int msgid
     */
    public function throwBottle($suid, $scontent, $scid, $ruid = null){

        $data = array(
            'suid' => $suid,
            'scontent' => $scontent,
            'ruid' => $ruid,
            'scid' => $scid,
            'ispublic' => empty($ruid) ? 1 : 0,
            'ispickup' => empty($ruid) ? 0 : 1,
            'createtime' => time(),
        );

        if($this->create($data)){

            return $this->add();
        } else {

            return $this->retError();
        }
    }

    /**
     * @author : NanQi
     * @date   : 20150504 21:22
     *
     * @desc     捡瓶子
     * @param    int $bid 瓶子ID
     * @param    int $ruid 捡起瓶子用户ID
     * @return   bool
     */
    public function pickupBottle($bid, $ruid) {
        $data = array(
            'id' => $bid,
            'ruid' => $ruid,
            'ispickup' => 1,
        );

        if($this->create($data)){

            return $this->save();
        } else {

            return $this->retError();
        }
    }

    /**
     * @author : NanQi
     * @date   : 20150505 13:46
     *
     * @desc     扔回大海
     */
    public function throwBack($bid) {
        $data = array(
            'id' => $bid,
            'ruid' => 0,
            'ispickup' => 0,
        );

        if($this->create($data)){

            return $this->save();
        } else {

            return $this->retError();
        }
    }
}