<?php
/**
 * Author: NanQi
 * Date: 20150508 15:37
 */
namespace App\Model;
use Think\Model;

class DrawrecordModel extends Model {

    /**
     * @author : NanQi
     * @date   : 20150508 10:49
     *
     * @desc     添加抽奖记录
     */
    public function addDrawrecord($uid, $scid, $round, $pid) {
        $data = array(
            'uid' => $uid,
            'scid' => $scid,
            'round' => $round,
            'pid' => $pid,
            'createtime' => time(),
        );

        if($this->create($data)){

            $this->add();
        } else {

            return $this->retError();
        }
    }
}