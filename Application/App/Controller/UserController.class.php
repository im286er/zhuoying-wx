<?php
/**
 * @author : Comer
 * @date   : 2015/4/16 15:56
 * @desc   : 用户注册、登录
 */
namespace App\Controller;
use think\Controller;
use Think\Model;

class UserController extends Controller{
    /**
     * @author : Comer
     * @date   : 2015/4/16 16:43
     *
     * @desc     :用户注册
     * @param int phonenumber 手机号
     * @param String password 密码
     * @param String registrationid 设备注册ID
     * @return  int 注册用户的ID
     */
    /*public function register(){

        $User = D("User");

        $User->validation(array(
            array('password', 'require', '密码不能为空', Model::MUST_VALIDATE, 'regex'),
            array('phonenumber', 'require', '手机号不能为空', Model::MUST_VALIDATE, 'regex'),
            //array('registrationid', 'require', '注册号不能为空', Model::MUST_VALIDATE, 'regex'),
            array('password', '6,50', '密码长度不正确', Model::MUST_VALIDATE, 'length'),
            //array('registrationid', '10,50', '注册号长度不正确', Model::MUST_VALIDATE, 'length'),
            array('phonenumber', 'phonenumber', '手机号格式不正确', Model::MUST_VALIDATE, 'regex'),
            array('phonenumber', '', '该手机号已存在', Model::EXISTS_VALIDATE, 'unique'),
        ));

        $db = M();
        $db->startTrans();

        $uid = $User->register(I('phonenumber'), I('password'));

        M('privacy')->add(array('uid' => $uid));

        $requ = D('Im', 'Logic')->getToken($uid, null, null);
        $token = '';
        if ($requ->code == 200) {
            $token = $requ->token;

            $saveData = array(
                'id' => $uid,
                'token' => $token,
            );
            $User->save($saveData);
        }

        $regid = I('registrationid');
        if (!empty($regid)) {
            D('registration')->bindRegisterUser(I('registrationid'), $uid);

            //JPush设置别名，并分组到为分组中
            D('JPush', 'Logic')->updateDeviceTagAlias(I('registrationid'), C('PUSH_ALIAS_PREFIX').$uid, array(C('UNKNOWN_TAG')));
        }

        if (!$db->commit()) {
            return $this->retError();
        }

        $retData = array(
            'id' => $uid,
            'token' => $token,
        );

        $this->retSuccess($retData);
    }*/

    /**
     * @author : NanQi
     * @date   : 20150416 21:57
     *
     * @desc     用户登录
     * @param    String name 用户名、手机、邮箱其中一种
     * @param    String password 密码
     * @param    String token 使用第三方账号登录返回的token
     * @param    String registrationid 设备注册ID
     * @return   int 用户ID
     */
    /*public function login(){

        $User = D("User"); // 实例化User对象

        $userData = null;

        if (I("token") != null) {

            $User->validation(array(
                array('token', 'require', '令牌不能为空', Model::MUST_VALIDATE, 'regex'),
                //array('registrationid', 'require', '注册号不能为空', Model::MUST_VALIDATE, 'regex'),
                array('token', '10,50', '令牌长度不正确', Model::MUST_VALIDATE, 'length'),
                //array('registrationid', '10,50', '注册号长度不正确', Model::MUST_VALIDATE, 'length'),
            ));

            $userData = $User->relation('privacy')->where("id = (select uid from t_thirdaccount where token = '%s')", I("token"))->field("id,token")->find();
            if (empty($userData)) {

                $this->retError(403, '没有对应的token');
            }
        }
        else {

            $User->validation(array(
                array('name', 'require', '手机号不能为空', Model::MUST_VALIDATE, 'regex'),
                array('password', 'require', '密码不能为空', Model::MUST_VALIDATE, 'regex'),
                //array('registrationid', 'require', '注册号不能为空', Model::MUST_VALIDATE, 'regex'),
                array('password', '6,50', '密码长度不正确', Model::MUST_VALIDATE, 'length'),
                //array('registrationid', '10,50', '注册号长度不正确', Model::MUST_VALIDATE, 'length'),
                array('name', 'phonenumber', '手机号格式不正确', Model::MUST_VALIDATE, 'regex'),
            ));

            $userData = $User->relation('privacy')->where("phonenumber='%s' and password = '%s'", I("name"), md5(I('password')))->field("id,token")->find();
            if (empty($userData)) {

                $this->retError(201, "帐号或密码错误");
            }
        }

        $regid = I('registrationid');
        if (!empty($regid)) {
            D('registration')->bindRegisterUser(I('registrationid'), $userData['id']);

            //设置JPush别名
            D('JPush', 'Logic')->setAlias(I('registrationid'), C('PUSH_ALIAS_PREFIX').$userData['id']);
        }

        $this->retSuccess($userData);
    }*/

    /**
     * @author : NanQi
     * @date   : 20150417 12:51
     *
     * @desc     发送短信验证码
     * @param    int phonenumber 手机号
     * @param    int operator 操作 0 注册 1 忘记密码
     * @return bool
     */
    /*public function sendsmsverify(){

        M()->validation(array(
            array('phonenumber', 'require', '手机号不能为空', Model::MUST_VALIDATE, 'regex'),
            array('phonenumber', 'phonenumber', '手机号格式不正确', Model::MUST_VALIDATE, 'regex'),
            array('operator', 'require', '操作方式不能为空', Model::MUST_VALIDATE, 'regex'),
            array('operator', 'number', '操作方式格式不正确', Model::MUST_VALIDATE, 'regex'),
        ));

        $cnt = D('User')->where('phonenumber = %s', I('phonenumber'))->count();

        if (I('operator') == '1' && !$cnt) {

            $this->retError(202, '该手机号还未注册');
        }
        elseif(I('operator') == '0' && $cnt) {

            $this->retError(202, '该手机号已经注册');
        }


        $cacheKey = "smsverify_".I("phonenumber");
        $intervalCacheKey = "interval_".I("phonenumber");

        $interval = S($intervalCacheKey);
        if (!empty($interval)) {
            $this->retError(201, '请60秒后尝试重新发送验证码');
        }

        //TODO 暂时自动生成,以后使用短信验证平台
        //$code = substr(I("phonenumber"), strlen(I("phonenumber")) - 6);
        $code = get_mobile_code();
        $ret = D('Sms', 'Logic')
            ->sendSMSVerify(I('phonenumber'), $code);

        if ($ret != null) {
            $this->retError(701, $ret);
        }

        S($cacheKey, $code, 60 * 5);
        S($intervalCacheKey, $code, 60);
        $this->retSuccess("发送成功");
    }*/

    /**
     * @author : NanQi
     * @date   : 20150420 10:43
     *
     * @desc     验证手机验证码
     * @param    int phonenumber 需要验证的手机号
     * @param    String code 收到的短信验证码
     * @return   是否验证成功
     */
    /*public function verifysms(){
        M()->validation(array(
            array('phonenumber', 'require', '手机号不能为空', Model::MUST_VALIDATE, 'regex'),
            array('code', 'require', '短信验证码不能为空', Model::MUST_VALIDATE, 'regex'),
            array('phonenumber', 'phonenumber', '手机号格式不正确', Model::MUST_VALIDATE, 'regex'),
        ));

        $cacheKey = "smsverify_".I("phonenumber");
        $code = S($cacheKey);
        if (empty($code)) {
            $this->retError(402, "短信验证码已过期");
        }

        if ($code != I("code")) {
            $this->retError(201, "短信验证码错误");
        }

        $this->retSuccess("验证成功");
    }*/

    /**
     * @author : NanQi
     * @date   : 20150420 12:57
     *
     * @desc     修改密码
     * @param    int phonenumber 手机号
     * @param    String code 收到的短信验证码
     * @param    String oldpassword 原密码（需要登录）
     * @param    String newpassword 新密码
     * @return   bool 是否修改成功
     */
    /*public function modifypassword(){

        //忘记密码
        if (I("oldpassword") == null) {

            M()->validation(array(
                array('phonenumber', 'require', '手机号不能为空', Model::MUST_VALIDATE, 'regex'),
                array('newpassword', 'require', '新密码不能为空', Model::MUST_VALIDATE, 'regex'),
                array('phonenumber', 'phonenumber', '手机号格式不正确', Model::MUST_VALIDATE, 'regex'),
                array('newpassword', '6,50', '密码长度不正确', Model::MUST_VALIDATE, 'length'),
            ));

            $user = D("User")->where("phonenumber='%s'", I("phonenumber"))->field('id,password')->find();
            if (empty($user)) {
                $this->retError(201, '该手机号没有对应的帐号信息');
            }

            if ($user['password'] == md5(I('newpassword'))) {
                $this->retError(202, '新密码不能和原密码相同');
            }

            $cacheKey = "smsverify_".I("phonenumber");
            $code = S($cacheKey);
            if (empty($code)) {
                $this->retError(402, "短信验证码已过期");
            }

            D('User')->modifyPassword($user['id'], I('newpassword'));
        }
        //修改密码
        else {
            $uid = authUserID();
            if (!$uid) {
                $this->retError(200, '帐号未登录');
            }

            M()->validation(array(
                array('oldpassword', 'require', '旧密码不能为空', Model::MUST_VALIDATE, 'regex'),
                array('newpassword', 'require', '新密码不能为空', Model::MUST_VALIDATE, 'regex'),
                array('oldpassword', '6,50', '旧密码长度不正确', Model::MUST_VALIDATE, 'length'),
                array('newpassword', '6,50', '新密码长度不正确', Model::MUST_VALIDATE, 'length'),
            ));

            $user = D('User')->field('id,password')->find($uid);
            if (empty($user)) {
                $this->retError(203, '不存在该帐号');
            }

            if ($user['password'] != md5(I('oldpassword'))) {
                $this->retError(204, '原密码错误');
            }

            if (I('oldpassword') == I('newpassword')) {
                $this->retError(202, '新密码不能和原密码相同');
            }

            D('User')->modifyPassword($user['id'], I('newpassword'));
        }

        $this->retSuccess("密码修改成功");
    }*/
    
    /**
     * @author : NanQi
     * @date   : 20150420 11:23
     *
     * @desc     对于登录用户进行第三方帐号绑定操作
     * @param    String token 第三方帐号登录或邀请生成的token
     * @param    String atype 帐号类型
     * @return   bool 绑定结果
     */
    /*public function bindUser(){
        $uid = authUserID();

        M()->validation(array(
            array('token', 'require', '令牌不能为空', Model::MUST_VALIDATE, 'regex'),
            array('atype', 'require', '帐号类型不能为空', Model::MUST_VALIDATE, 'regex'),
            array('token', '10,50', '令牌长度不正确', Model::MUST_VALIDATE, 'length'),
            array('atype', '1,10', '帐号类型长度不正确', Model::MUST_VALIDATE, 'length'),
        ));
    }*/

    /**
     * @author : NanQi
     * @date   : 20150504 18:14
     *
     * @desc     获取用户隐私
     */
    /*public function privacy(){
        $uid = authUserID();

        //$user = M()->query("SELECT t1.id,nickname,sex,avatar,job,signature,birthday FROM t_user t1 LEFT JOIN t_privacy t2 ON t1.id = t2.uid");

        $user = D('Privacy')->cache('privacy_'.$uid, 60 * 60 * 24)->find($uid);
        if (empty($user)) {
            $this->retError(201, '没有对应的用户ID');
        }
        else {
            $this->retSuccess($user);
        }
    }*/

    /**
     * @author : NanQi
     * @date   : 20150506 10:03
     *
     * @desc     修改用户隐私
     * @param    privacy privacy 隐私信息
     * @return   bool
     */
    /*public function modifyPrivacy(){
        $uid = authUserID();

        D('Privacy')->modifyPrivacy($uid);

        S('privacy_'.$uid, null);

        $this->retSuccess();
    }*/

    /**
     * @author : NanQi
     * @date   : 20150512 14:45
     *
     * @desc     修改头像
     * @param    String file base64编码头像
     * @return   bool
     */
   /* public function modifyAvatar(){
        $uid = authUserID();

        M()->validation(array(
            array('file', 'require', '头像文件不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $fileName = 'avatar_'.$uid.'_'.date('YmdHis').'.jpg';

        $fileUrl = D('Qiniu', 'Logic')->upload($fileName, I('file'));

        //图像做居中裁剪
        //$fileUrl = $fileUrl.'?imageView2/1/w/128/h/128';

        D('Privacy')->where('uid = %d', $uid)->save(array(
            'avatar' => $fileUrl
        ));

        S('privacy_'.$uid, null);

        $this->retSuccess($fileUrl);
    }*/
    /**
     * 登录
     */
    public  function  login(){
        $User = D("User"); // 实例化User对象

        $userData = null;

        if (I("token") != null) {

            $User->validation(array(
                array('token', 'require', '令牌不能为空', Model::MUST_VALIDATE, 'regex'),
                array('token', '10,50', '令牌长度不正确', Model::MUST_VALIDATE, 'length'),
            ));

            $userData = $User->relation('privacy')->where("id = (select uid from t_thirdaccount where token = '%s')", I("token"))->field("id,token")->find();
            if (empty($userData)) {

                $this->retError(403, '没有对应的token');
            }
        }
        else {

            $User->validation(array(
                array('phonenumber', 'require', '手机号不能为空', Model::MUST_VALIDATE, 'regex'),
                array('password', 'require', '密码不能为空', Model::MUST_VALIDATE, 'regex'),
                array('password', '6,50', '密码长度不正确', Model::MUST_VALIDATE, 'length'),
                array('phonenumber', 'phonenumber', '手机号格式不正确', Model::MUST_VALIDATE, 'regex'),
            ));
            \Think\Log::record(I("phonenumber"));
            \Think\Log::record(md5(I('password')));
            \Think\Log::save();

            setLog(I("phonenumber"));
            $userData = $User->where("phonenumber='%s' and password = '%s'", I("phonenumber"), md5(I('password')))->field("id,token")->find();
            if (empty($userData)) {
                $this->retError(201, "帐号或密码错误");
            }
        }


        $this->retSuccess($userData);
    }

    /**
     * 注册
     */
    public  function register(){
        $User = D("User");

        $User->validation(array(
            array('phonenumber', 'require', '手机号不能为空', Model::MUST_VALIDATE, 'regex'),
            array('password', 'require', '密码不能为空', Model::MUST_VALIDATE, 'regex'),
            array('code','require','验证码不能为空',Model::MUST_VALIDATE,'regex'),
            array('phonenumber', 'phonenumber', '手机号格式不正确', Model::MUST_VALIDATE, 'regex'),
            array('phonenumber', '', '该手机号已存在', Model::EXISTS_VALIDATE, 'unique'),
            array('password', '6,50', '密码长度不正确', Model::MUST_VALIDATE, 'length'),
            array('code','6','验证码长度不正确',Model::MUST_VALIDATE,'length'),
        ));



        $uid = $User->register(I('phonenumber'), I('password'), I('code'));
        \Think\Log::record($uid.'============');
        if($uid){
            $this->retSuccess("注册成功！");
        }else{
            $this->retError($uid);
        }
    }

    /**
     * 修改密码
     */

    public  function  modifyPass(){
        $User = D("User");

        $User->validation(array(
            array('phonenumber', 'require', '手机号不能为空', Model::MUST_VALIDATE, 'regex'),
            array('password', 'require', '密码不能为空', Model::MUST_VALIDATE, 'regex'),
            array('code','require','验证码不能为空',Model::MUST_VALIDATE,'regex'),
            array('phonenumber', 'phonenumber', '手机号格式不正确', Model::MUST_VALIDATE, 'regex'),
            array('password', '6,50', '密码长度不正确', Model::MUST_VALIDATE, 'length'),
            array('code','6','验证码长度不正确',Model::MUST_VALIDATE,'length'),
        ));

        $uid = $User->modifyPass(I('phonenumber'), I('password'), I('code'));
        \Think\Log::record($uid.'============');
        if($uid){
            $this->retSuccess("修改成功！");
        }else{
            $this->retError($uid);
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
    public function sendvertifycode(){

        M()->validation(array(
            array('phonenumber', 'require', '手机号不能为空', Model::MUST_VALIDATE, 'regex'),
            array('phonenumber', 'phonenumber', '手机号格式不正确', Model::MUST_VALIDATE, 'regex'),
            array('operator', 'require', '操作方式不能为空', Model::MUST_VALIDATE, 'regex'),
            array('operator', 'number', '操作方式格式不正确', Model::MUST_VALIDATE, 'regex'),
        ));

        $cnt = D('User')->where('phonenumber = %s', I('phonenumber'))->count();

        if (I('operator') == '1' && !$cnt) {

            $this->retError(202, '该手机号还未注册');
        }
        elseif(I('operator') == '0' && $cnt) {

            $this->retError(202, '该手机号已经注册');
        }


        $cacheKey = "smsverify_".I("phonenumber");
        $intervalCacheKey = "interval_".I("phonenumber");

        $interval = S($intervalCacheKey);
        if (!empty($interval)) {
            $this->retError(201, '请60秒后尝试重新发送验证码');
        }

        //TODO 暂时自动生成,以后使用短信验证平台
        $code = substr(I("phonenumber"), strlen(I("phonenumber")) - 6);

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
    public function getvertifycode(){

        M()->validation(array(
            array('phonenumber', 'require', '手机号不能为空', Model::MUST_VALIDATE, 'regex'),
            array('phonenumber', 'phonenumber', '手机号格式不正确', Model::MUST_VALIDATE, 'regex'),
            array('operator', 'require', '操作方式不能为空', Model::MUST_VALIDATE, 'regex'),
            array('operator', 'number', '操作方式格式不正确', Model::MUST_VALIDATE, 'regex'),
        ));

        $cnt = D('User')->where('phonenumber = %s', I('phonenumber'))->count();

        if (I('operator') == '1' && !$cnt) {

            $this->retError(202, '该手机号还未注册');
        }


        $cacheKey = "smsverify_".I("phonenumber");
        $intervalCacheKey = "interval_".I("phonenumber");

        $interval = S($intervalCacheKey);
        if (!empty($interval)) {
            $this->retError(201, '请60秒后尝试重新发送验证码');
        }

        //TODO 暂时自动生成,以后使用短信验证平台
        $code = substr(I("phonenumber"), strlen(I("phonenumber")) - 6);

        S($cacheKey, $code, 60 * 5);
        S($intervalCacheKey, $code, 60);
        $this->retSuccess("发送成功");
    }

    /**
     * 注册时，先判断手机号是否存在
     */
    public function existPhone(){
        $cnt = D('User')->where("phonenumber = '%s'",I(phonenumber))->count();
        if($cnt){
            $this->retError("该手机号已存在");
        }else{
            $this->retSuccess("手机号不存在");
        }
    }

    /**
     * 第三方账号注册和登录
     */
    public  function loginBythird(){
        \Think\Log::record(I("token"));
        M()->validation(array(
            array('token', 'require', '令牌不能为空', Model::MUST_VALIDATE, 'regex'),
            array('atype', 'require', '帐号类型不能为空', Model::MUST_VALIDATE, 'regex'),
            array('token', '2,500', '令牌长度不正确', Model::MUST_VALIDATE, 'length'),
            array('atype', '1,10', '帐号类型长度不正确', Model::MUST_VALIDATE, 'length'),
        ));
        //\Think\Log::write(I("token"));
        //\Think\Log::write(I("atype"));
        //\Think\log::write(I("nickname"));
        $user =D("User")->relation("tokens")->where("id = (select uid from t_thirdaccount where token ='%s')",I(token))->field("id,token")->find();
        if (empty($user)) {
            $data = Array(
                'token' => I("token"),
                'newpasstime' => time(),
                'istempuser' => '1'
            );
            //给用户表保存临时用户token
            if(D("User")->create($data)){
                $uid = D("User")->add();
                $dat = Array(
                    'token' => I("token"),
                    'uid' => $uid,
                    'atype' => I("atype")
                );
                if(D("Thirdaccount")->create($dat)){
                    D("Thirdaccount")->add();
                    $datareturn = Array(
                        'token' => I("token"),
                        'id' => $uid
                    );
                    $this->retSuccess(json_encode($datareturn));
                }else{
                    $this->retError();
                }

            }else{
                $this->retError();
            }
        }else{
            $this->retSuccess($user);
        }

    }

}

