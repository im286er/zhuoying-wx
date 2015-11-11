<?php
/**
 * @author : Comer
 * @date   : 2015/7/20 17:00
 * @desc   :
 */
namespace App\Model;
use Think\Model\RelationModel;
class UserModel extends RelationModel{
    protected $_link = array(
        'tokens' => array(
            'mapping_type'      =>  self::HAS_MANY,
            'class_name'        =>  'thirdaccount',
            'mapping_name'      =>  'tokens',
            'foreign_key'       =>  'uid',
            'mapping_fields'    =>  'token',
        )

    );
    /**
     * 注册一个新用户
     * @param  string $phonenumber   用户手机号码
     * @param  string $password 用户密码
     * @return integer          注册成功-用户信息，注册失败-错误编号
     */
    public function register($phonenumber, $password, $codenew){

        $cacheKey = "smsverify_".$phonenumber;
        $code = S($cacheKey);
        if (empty($code)) {
            $this->retError(402, "短信验证码已过期");
        }

        if ($code != $codenew) {
            $this->retError(201, "短信验证码错误");
        }

        $data = array(
            'phonenumber' => $phonenumber,
            'password' => md5($password),
            'newpasstime' => time(),
            'istempuser' => '0'
        );

        if($this->create($data)){

            return $this->add();
        } else {

            return $this->retError();
        }
    }
    /**
     * 修改密码
     * @param  string $phonenumber   用户手机号码
     * @param  string $password 用户密码
     * @return integer          修改成功-用户信息，修改失败-错误编号
     */
    public function modifyPass($phonenumber, $password, $codenew){

        $cacheKey = "smsverify_".$phonenumber;
        $code = S($cacheKey);
        if (empty($code)) {
            $this->retError(402, "短信验证码已过期");
        }

        if ($code != $codenew) {
            $this->retError(201, "短信验证码错误");
        }

        $data = array(
            'password' => md5($password),
            'newpasstime' => time(),
            'istempuser' => '0'
        );

        return $this->where('phonenumber=%s',$phonenumber)->save($data);
    }

}