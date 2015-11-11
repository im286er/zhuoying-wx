<?php
/**
 * @author : Comer
 * @date   : 2015/4/16 15:56
 * @desc   : 用户注册、登录
 */
namespace Api\Controller;

use think\Controller;
use Think\Model;

class UserController extends Controller
{
    /**
     * 登录
     */
    public function  login()
    {
        $User = D("User"); // 实例化User对象

        $userData = null;

        if (I("token") != null) {

            $User->validation(array(
                array('token', 'require', '令牌不能为空', Model::MUST_VALIDATE, 'regex'),
                array('token', '10,50', '令牌长度不正确', Model::MUST_VALIDATE, 'length'),
            ));

            $userData = $User->field("id,nickname,phonenumber,sex,avatar,signature,role")->where("id = (select uid from t_thirdaccount where token = '%s')", I("token"))->find();
            if (empty($userData)) {

                $this->retError('没有对应的token');
            }
        } else {

            $User->validation(array(
                array('phonenumber', 'require', '手机号不能为空', Model::MUST_VALIDATE, 'regex'),
                array('password', 'require', '密码不能为空', Model::MUST_VALIDATE, 'regex'),
                array('password', '6,50', '密码长度不正确', Model::MUST_VALIDATE, 'length'),
                array('phonenumber', 'phonenumber', '手机号格式不正确', Model::MUST_VALIDATE, 'regex'),
            ));

            $userData = $User->field("id,nickname,phonenumber,sex,avatar,signature,role")->where("phonenumber='%s' and password = '%s'", I("phonenumber"), md5(I('password')))->find();

            if (empty($userData)) {
                $this->retError("帐号或密码错误");
            }

            $deviceid=I('deviceid');

            if($deviceid){
                $uid = $userData['id'];
                D('device')->bindDevice($deviceid, $uid);
            }

            $User->saveLogInfo(I('phonenumber'), I('city'));
        }

        $this->retSuccess($userData);
    }

    /**
     * 注册
     */
    public function register()
    {
        $User = D("User");

        $User->validation(array(
            array('phonenumber', 'require', '手机号不能为空', Model::MUST_VALIDATE, 'regex'),
            array('password', 'require', '密码不能为空', Model::MUST_VALIDATE, 'regex'),
            array('code', 'require', '验证码不能为空', Model::MUST_VALIDATE, 'regex'),
            array('phonenumber', 'phonenumber', '手机号格式不正确', Model::MUST_VALIDATE, 'regex'),
            array('phonenumber', '', '该手机号已存在', Model::EXISTS_VALIDATE, 'unique'),
            array('password', '6,50', '密码长度不正确', Model::MUST_VALIDATE, 'length'),
            array('code', '6', '验证码长度不正确', Model::MUST_VALIDATE, 'length'),
        ));

        $cnt = $User->where("phonenumber='%s'", I("phonenumber"))->find();
        if($cnt){
            $this->retError("该手机已被注册!");
        }



        $uid = $User->register(I('phonenumber'), I('password'), I('code'));

        $deviceid=I('deviceid');

        if ($uid) {
            if($deviceid) {
                D('device')->bindDevice($deviceid, $uid);
            }

            $this->retSuccess($uid);
        } 

        $this->retError('注册失败');
    }

    public function editUser(){
        $User = D("user");

        $User->validation(array(
            array('id', 'require', '用户ID不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $data = array();
        $nickname = I('nickname');
        $avatar = I('avatar');
        $signature = I('signature');

        if ($nickname) $data['nickname'] = $nickname;
        if ($avatar) $data['avatar'] = $avatar;
        if ($signature) $data['signature'] = $signature;

        $flg = $User->where("id = %d", I('id'))->save($data);
        if (!$flg) {
            $this->retError();
        }

        S('user_base_'.I('id'), null);
        $this->retSuccess();
    }

    /**
     * 修改密码
     */

    public function  modifyPass()
    {
        $User = D("User");

        $User->validation(array(
            array('phonenumber', 'require', '手机号不能为空', Model::MUST_VALIDATE, 'regex'),
            array('password', 'require', '密码不能为空', Model::MUST_VALIDATE, 'regex'),
            array('phonenumber', 'phonenumber', '手机号格式不正确', Model::MUST_VALIDATE, 'regex'),
            array('password', '6,50', '密码长度不正确', Model::MUST_VALIDATE, 'length'),
        ));

        $cnt = $User->where("phonenumber='%s'", I("phonenumber"))->find();
        if(!$cnt){
            $this->retError("不存在该手机号!");
        }

        $oldpassword=I("oldpassword");
        $code=I("code");

        if(!$oldpassword&&!$code){
            $this->retError("验证码或旧密码不能为空!");
        }

        if($oldpassword){
            $cnt = $User->where("phonenumber='%s' AND password = '%s'", I("phonenumber"), md5($oldpassword))->find();
            if(!$cnt){
                $this->retError("旧密码错误!");
            }
            $User->modifyPassByOld(I('phonenumber'), I('password'));
            $this->retSuccess("修改成功！");
        }

        if($code){
            $uid = $User->modifyPass(I('phonenumber'), I('password'), I('code'));
            \Think\Log::record($uid . '============');
            if ($uid) {
                $this->retSuccess("修改成功！");
            } else {
                $this->retError($uid);
            }
        }
    }

    /**
     * @author : comer
     * @date   : 20150720 12:51
     *
     * @desc     发送短信验证码
     * @param    int phonenumber 手机号
     * @param    int operator 操作 0 注册 1 忘记密码
     * @return bool
     */
    public function sendvertifycode()
    {

        M()->validation(array(
            array('phonenumber', 'require', '手机号不能为空', Model::MUST_VALIDATE, 'regex'),
            array('phonenumber', 'phonenumber', '手机号格式不正确', Model::MUST_VALIDATE, 'regex'),
        ));

        $phoneNumber = I('phonenumber');

        $cnt = D('User')->where('phonenumber = %s', $phoneNumber)->count();

        if ($cnt) {
            $this->retError("该手机号已被注册!");
        }


        $cacheKey = "smsverify_".$phoneNumber;
        $intervalCacheKey = "interval_".$phoneNumber;

        $interval = S($intervalCacheKey);
        if (!empty($interval)) {
            $this->retError('请60秒后尝试重新发送验证码');
        }

        //TODO 暂时自动生成,以后使用短信验证平台
        // $code = substr($phoneNumber, strlen($phoneNumber) - 6);
        $code = get_mobile_code();
        $ret = D('Sms', 'Logic')->sendSMSVerify($phoneNumber, $code);

        if ($ret != null) {
            $this->retError(701, $ret);
        }

        S($cacheKey, $code, 60 * 5);
        S($intervalCacheKey, $code, 60);
        $this->retSuccess("发送成功");
    }

    /**
     * @author : michael
     * @date   : 20150720 12:51
     *
     * @desc     发送修改密码短信验证码
     * @param    int phonenumber 手机号
     * @param    int operator 操作 0 注册 1 忘记密码
     * @return bool
     */
    public function getvertifycode()
    {

        M()->validation(array(
            array('phonenumber', 'require', '手机号不能为空', Model::MUST_VALIDATE, 'regex'),
            array('phonenumber', 'phonenumber', '手机号格式不正确', Model::MUST_VALIDATE, 'regex'),
        ));

        $phoneNumber = I('phonenumber');

        $cnt = D('User')->where('phonenumber = %s', $phoneNumber)->count();

        if (!$cnt) {
            $this->retError('该手机号还未注册');
        }


        $cacheKey = "smsverify_".$phoneNumber;
        $intervalCacheKey = "interval_".$phoneNumber;

        $interval = S($intervalCacheKey);
        if (!empty($interval)) {
            $this->retError('请60秒后尝试重新发送验证码');
        }

        //TODO 暂时自动生成,以后使用短信验证平台
        // $code = substr(I("phonenumber"), strlen(I("phonenumber")) - 6);
        $code = get_mobile_code();
        $ret = D('Sms', 'Logic')->sendSMSVerify($phoneNumber, $code);

        if ($ret != null) {
            $this->retError(701, $ret);
        }

        S($cacheKey, $code, 60 * 5);
        S($intervalCacheKey, $code, 60);
        $this->retSuccess("发送成功");
    }

    /**
     * 发送用户绑定验证码
     */
    public function send_vertify_code_by_bind()
    {

        M()->validation(array(
            array('uid', 'require', '用户不能为空', Model::MUST_VALIDATE, 'regex'),
            array('phonenumber', 'require', '手机号不能为空', Model::MUST_VALIDATE, 'regex'),
            array('phonenumber', 'phonenumber', '手机号格式不正确', Model::MUST_VALIDATE, 'regex'),
        ));

        $uid = I('uid');

        $phoneNumber = I('phonenumber');

        $user = D('User')->find($uid);
        if ($user['phonenumber']) {

            $cnt = D('User')->where('phonenumber = %s', $phoneNumber)->count();

            if ($cnt) {
                $this->retError("该手机号已被注册!");
            }
        }
       
        $cacheKey = "smsverify_".$phoneNumber;
        $intervalCacheKey = "interval_".$phoneNumber;

        $interval = S($intervalCacheKey);
        if (!empty($interval)) {
            $this->retError('请60秒后尝试重新发送验证码');
        }

        //TODO 暂时自动生成,以后使用短信验证平台
        // $code = substr(I("phonenumber"), strlen(I("phonenumber")) - 6);
        $code = get_mobile_code();
        $ret = D('Sms', 'Logic')->sendSMSVerify($phoneNumber, $code);

        if ($ret != null) {
            $this->retError(701, $ret);
        }

        S($cacheKey, $code, 60 * 5);
        S($intervalCacheKey, $code, 60);
        $this->retSuccess("发送成功");
    }


    /**
     * 注册时，先判断手机号是否存在
     */
    /*public function existPhone()
    {
        $cnt = D('User')->where("phonenumber = '%s'", I(phonenumber))->count();
        if ($cnt) {
            $this->retError("该手机号已存在");
        } else {
            $this->retSuccess("手机号不存在");
        }
    }*/

    /**
     * 第三方账号注册和登录
     */
    public function loginBythird() {
        \Think\Log::record(I("token"));
        M()->validation(array(
            array('token', 'require', '令牌不能为空', Model::MUST_VALIDATE, 'regex'),
            array('token', '2,500', '令牌长度不正确', Model::MUST_VALIDATE, 'length'),
            array('tokentype', 'require', '令牌类型不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $userModel = D("User");
        $user = $userModel->where("id = (select user_id from t_thirdaccount where token ='%s' and atype = '%s')", I('token'), I('tokentype'))
                          ->field("id,nickname,phonenumber,sex,avatar,signature,role")
                          ->find();
        // dump($user);
        // exit;

        if (empty($user)) {
            $data = Array(
                'nickname' => I('nickname'),
                'sex' => I('sex'),
                'avatar' => I('avatar'),
                'newpasstime' => time(),
                'istempuser' => '1'
            );
            //给用户表保存临时用户token
            if ($userModel->create($data)) {
                $uid = $userModel->add();
                
                if(I('deviceid')){

                    D('device')->bindDevice(I("deviceid"), $uid);
                }

                $thirdAccount = D("Thirdaccount");

                $atype = I("tokentype");
                $token = I("token");

                $data_thirdAccount = array(
                    'token' => $token,
                    'user_id' => $uid,
                    'atype' => $atype
                );

                if ($thirdAccount->create($data_thirdAccount)) {
                    $thirdAccount->where("token = '$token'")->delete();
                    $thirdAccount->add();
                    $datareturn = array(
                        'id' => $uid,
                        'nickname' => I('nickname'),
                        'sex' => I('sex'),
                        'phonenumber' => I('phonenumber'),
                        'avatar' => I('avatar'),
                        'role' => '0',
                    );
                    $this->retSuccess($datareturn);
                } else {
                    $this->retError();
                }

            } else {
                $this->retError();
            }
        }
        else {

            if(I('deviceid')){

                D('device')->bindDevice(I("deviceid"), $user['id']);
            }
            $this->retSuccess($user);
        }

    }

    public function userBind(){
        M()->validation(array(
            array('uid', 'require', '用户不能为空', Model::MUST_VALIDATE, 'regex'),
            array('phonenumber', 'require', '手机号不能为空', Model::MUST_VALIDATE, 'regex'),
            array('phonenumber', 'phonenumber', '手机号格式不正确', Model::MUST_VALIDATE, 'regex'),
            array('code', 'require', '验证码不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $phonenumber=I('phonenumber');
        $codenew=I('code');

        $cacheKey = "smsverify_".$phonenumber;
        $code = S($cacheKey);
        if (empty($code)) {
            $this->retError("短信验证码已过期");
        }

        if ($code != $codenew) {
            $this->retError("短信验证码错误");
        }

        $uid = I('uid');

        $user = D('User')->find($uid);
        if (!$user['phonenumber']) {

            $ret = D('User')->field("id,nickname,phonenumber,sex,avatar,signature,role")->where("phonenumber = '$phonenumber'")->find();

            if ($ret) {
                $rows = M()->execute("UPDATE t_thirdaccount set user_id = (select id from t_user where phonenumber = '$phonenumber') where user_id = '$uid'");

                if ($rows) {
                    $this->retSuccess($ret);
                }
                else {
                    $this->retError();
                }
            }
        }

        $data=array(
            'phonenumber' => $phonenumber
        );

        D("user")->where('id=%d',I('uid'))->save($data);

        $this->retSuccess("绑定成功!");
    }

    //判断用户是否存在场地
    public function exists_site() {
        $uid = I('uid');

        if ($uid) {
            $cnt = D('Site')->where("uid = $uid")->count();
            if ($cnt) {
                $this->retSuccess('用户有场地');
            }
        }

        $this->retError('用户没有场地');
    }

    //判断用户是否存在场地
    public function exists_password() {
        $uid = I('uid');

        if ($uid) {
            $password = D('User')->where("id = '$uid'")->getField('password');
            if ($password) {
                $this->retSuccess('用户有密码');
            }
            else {
                $this->retError('用户没有密码');
            }
        }

        $this->retError();
    }

    //获取用户信息
    public function get_userinfo() {
        M()->validation(array(   
            array('uid', 'require', '用户ID不能为空', Model::MUST_VALIDATE, 'regex'),
        )); 

        $uid = I('uid');

        $userData = D('User')->field("id,nickname,phonenumber,sex,avatar,signature,role")->find($uid);

        if ($userData) {
            $this->retSuccess($userData);
        }
        else {
            $this->retError('未找到该用户');
        }
    }

    //组织者认证
    public function auth_host() {
        $authHost = D('AuthHost');
        $authHost->validation(array(   
            array('uid', 'require', '用户不能为空', Model::MUST_VALIDATE, 'regex'),
            array('uid', '', '该用户已认证', Model::EXISTS_VALIDATE, 'unique'),
            array('uname', 'require', '姓名不能为空', Model::MUST_VALIDATE, 'regex'),
            array('idcard', 'require', '身份证号不能为空', Model::MUST_VALIDATE, 'regex'),
            array('img_up', 'require', '身份证正面不能为空', Model::MUST_VALIDATE, 'regex'),
            array('img_down', 'require', '身份证反面不能为空', Model::MUST_VALIDATE, 'regex'),
            array('uname', '1,16', '姓名长度不正确', Model::MUST_VALIDATE, 'length'),
            array('idcard', '15,18', '身份证号长度不正确', Model::MUST_VALIDATE, 'length'),
        ));

        if ($authHost->create()) {
            $flg = $authHost->add();

            if ($flg) {
                $uid = I("uid");
                D('User')->where("id = '$uid'")->setField('role', '-1');
                S('user_base_'.$uid, null); 
                $this->retSuccess();
            }
        }

        $this->retError();
    }

    //场地提供者认证
    public function auth_site() {
        $authSite = D('AuthSite');
        $authSite->validation(array(   
            array('uid', 'require', '用户不能为空', Model::MUST_VALIDATE, 'regex'),
            array('uid', '', '该用户已认证', Model::EXISTS_VALIDATE, 'unique'),
            array('uname', 'require', '姓名不能为空', Model::MUST_VALIDATE, 'regex'),
            array('idcard', 'require', '身份证号不能为空', Model::MUST_VALIDATE, 'regex'),
            array('img_up', 'require', '身份证正面不能为空', Model::MUST_VALIDATE, 'regex'),
            array('img_down', 'require', '身份证反面不能为空', Model::MUST_VALIDATE, 'regex'),
            array('sitename', 'require', '场地名称不能为空', Model::MUST_VALIDATE, 'regex'),
            array('sitetype', 'require', '场地类型不能为空', Model::MUST_VALIDATE, 'regex'),
            array('address', 'require', '场地地址不能为空', Model::MUST_VALIDATE, 'regex'),
            array('address_location', 'require', '定位地址不能为空', Model::MUST_VALIDATE, 'regex'),
            array('longitude', 'require', '经度不能为空', Model::MUST_VALIDATE, 'regex'),
            array('latitude', 'require', '纬度不能为空', Model::MUST_VALIDATE, 'regex'),
            array('legalname', 'require', '法人姓名不能为空', Model::MUST_VALIDATE, 'regex'),
            array('tel', 'require', '联系电话不能为空', Model::MUST_VALIDATE, 'regex'),
            array('registration', 'require', '工商注册号不能为空', Model::MUST_VALIDATE, 'regex'),
            array('longitude', 'double', '经度格式不能为空', Model::MUST_VALIDATE, 'regex'),
            array('latitude', 'double', '纬度格式不能为空', Model::MUST_VALIDATE, 'regex'),
            array('uname', '1,16', '姓名长度不正确', Model::MUST_VALIDATE, 'length'),
            array('idcard', '15,18', '身份证号长度不正确', Model::MUST_VALIDATE, 'length'),
            array('legalname', '1,16', '法人姓名长度不正确', Model::MUST_VALIDATE, 'length'),
            array('tel', '1,16', '联系电话长度不正确', Model::MUST_VALIDATE, 'length'),
            array('registration', '1,16', '工商注册号长度不正确', Model::MUST_VALIDATE, 'length'),
        ));

        if ($authSite->create()) {
            $flg = $authSite->add();

            if ($flg) {
                $uid = I("uid");
                D('User')->where("id = '$uid'")->setField('role', '-2');
                S('user_base_'.$uid, null); 
                $this->retSuccess();
            }
        }

        $this->retError();
    }

    //获取场地认证信息
    public function get_auth_site() {
        M()->validation(array(
            array('uid','require','用户ID不能为空',Model::MUST_VALIDATE,'regex'),
            ));

        $uid = I('uid');

        $auth_site = D('AuthSite')->where("uid = '$uid' and status = 1")->field("uid,uname,idcard,img_up,img_down,sitename,sitetype,address,address_location,longitude,latitude,legalname,tel,registration")->find();      

        if ($auth_site) {
            $this->retSuccess($auth_site);
        }
        else {
            $this->retError('未找到场地认证信息');
        }
    }


    //设置推送开关
    public function set_push_toggle() {
        M()->validation(array(   
            array('uid', 'require', '用户不能为空', Model::MUST_VALIDATE, 'regex'),
            array('is_open', 'require', '是否开启不能为空', Model::MUST_VALIDATE, 'regex'),
        )); 

        $uid = I("uid");
        $is_open = I("is_open");

        $flg = D('Device')->where("uid = '$uid'")->setField('is_open', $is_open);
        if ($flg) {
            S('device_'.$uid, null);

            $this->retSuccess();
        }

        $this->retError();
    }
}

