<?php
/**
 * Author: NanQi
 * Date: 20150506 10:20
 */

namespace App\Model;
use Think\Model;

class PrivacyModel extends Model {
    protected $_validate = array(
        array('nickname', '0,50', '昵称长度不正确', self::EXISTS_VALIDATE, 'length'),
        array('sex', '0,6', '性别长度不正确', self::EXISTS_VALIDATE, 'length'),
        array('avatar', '0,100', '头像长度不正确', self::EXISTS_VALIDATE, 'length'),
        array('job', '0,50', '职业长度不正确', self::EXISTS_VALIDATE, 'length'),
        array('signature', '0,500', '签名长度不正确', self::EXISTS_VALIDATE, 'length'),
        array('birthday', 'date', '生日格式不正确', self::EXISTS_VALIDATE, 'regex'),
    );

    /**
     * @author : NanQi
     * @date   : 20150506 10:27
     *
     * @desc     修改隐私信息
     * @param    int $uid 用户ID
     * @return   bool
     */
    public function modifyPrivacy($uid){
        if($this->create()){

            return $this->where("uid = %d", $uid)->save();
        } else {

            return $this->retError();
        }
    }
}