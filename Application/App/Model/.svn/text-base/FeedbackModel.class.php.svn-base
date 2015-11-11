<?php
/**
 * Author: NanQi
 * Date: 20150515 10:11
 */
namespace App\Model;
use Think\Model;

class FeedbackModel extends Model {

    public function addFeedback($uid, $otype, $ptype, $content){
        $data = array(
            'uid' => $uid,
            'otype' => $otype,
            'ptype' => $ptype,
            'content' => $content,
        );

        if($this->create($data)){
            return $this->add();
        } else {

            $this->retError();
        }
    }
}