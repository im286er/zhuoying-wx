<?php
/**
 * Author: NanQi
 * Date: 20150423 10:15
 */

namespace App\Model;
use Think\Model;

class RegistrationModel extends Model {

    protected $_validate = array(
        array('regid', '10,50', '注册号长度不正确', self::EXISTS_VALIDATE, 'length'), //注册号长度不正确
        array('uid', 'number', '用户ID格式不正确', self::EXISTS_VALIDATE, 'regex'), //用户ID格式不正确
    );

    /**
     * @author : NanQi
     * @date   : 20150423 10:18
     *
     * @desc     绑定注册用户,即给注册ID设置用户ID
     * @param    string $regid 注册ID
     * @param    string $uid 用户ID
     * @return   bool
     */
    public function bindRegisterUser($regid, $uid){
        $data = array(
            'regid' => $regid,
            'uid' => $uid,
        );

        if($this->create($data)){
            $this->delete($regid);
            $this->add();
        } else {

            return $this->retError();
        }
    }
}