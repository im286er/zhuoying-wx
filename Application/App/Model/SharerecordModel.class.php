<?php
/**
 * Author: NanQi
 * Date: 20150508 10:36
 */
namespace App\Model;
use Think\Model;

class SharerecordModel extends Model {

    /**
     * @author : NanQi
     * @date   : 20150508 10:49
     *
     * @desc     添加分享
     */
    public function addSharp($uid, $scid, $stype) {
        $data = array(
            'uid' => $uid,
            'scid' => $scid,
            'stype' => $stype,
            'scount' => 1,
        );

        if($this->create($data)){

            $this->add();
        } else {

            return $this->retError();
        }
    }
}