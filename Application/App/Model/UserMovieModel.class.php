<?php
/**
 * Author: NanQi
 * Date: 20150424 13:58
 */
namespace App\Model;
use Think\Model;

class UserMovieModel extends Model {

    const LIKE_MOVIE          =   1;
    const DISLIKE_MOVIE        =   -1;

    protected $_validate = array(
        array('mid', 'number', '电影ID格式不正确', self::EXISTS_VALIDATE, 'regex'),
        array('uid', 'number', '用户ID格式不正确', self::EXISTS_VALIDATE, 'regex'),
    );

    public function addLikeState($uid, $mid, $likeState){
        $data = array(
            'uid' => $uid,
            'mid' => $mid,
            'likestate' => $likeState,
        );

        if($this->create($data)){

            $this->add();
        } else {

            return $this->retError();
        }
    }

    public function modifyLikeState($uid, $mid, $likeState){
        if($this->create(func_get_args())){

            $this->where("uid = '%d' and mid = '%d'", $uid, $mid)->save(array('likestate' => $likeState));
        } else {

            return $this->retError();
        }
    }
}