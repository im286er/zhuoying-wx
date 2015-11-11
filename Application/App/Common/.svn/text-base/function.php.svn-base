<?php
/**
 * Author: NanQi
 * Date: 20150420 12:43
 */
use Think\Model;
function authUserID(){
    $userID = trim(I('authuserid'));
    if (empty($userID)) {
        M()->retError(298, '帐号未登录');
    }
    else{
        M()->validation(array(
            array('authuserid', 'number', '用户ID格式不正确', Model::EXISTS_VALIDATE, 'regex'),
        ));

        return $userID;
    }
}

/** 获取当前时间
 * @return bool|string
 */
function getNowDateTime(){
    return date("Y-m-d H:i:s");
}

/** 判断字符串中是否有敏感词汇
 * @param $str 要检查的字符串
 * @return bool
 */
function checkSensitiveWord($str) {
    $trie = D('Trie', 'Logic');
    return !$trie->contain($str);
}

/** 手机验证码生成函数
 *  考虑移动和电信的数字黑名单
 */
function get_mobile_code() {
    $forbidden_num = "1989:10086:12590:1259:10010:10001:10000:";
    do
    {
        $mobile_code = substr(microtime(), 2, 6);
    }
    while (preg_match($mobile_code.':', $forbidden_num));
    return $mobile_code;
}

/**
 * @author : Comer
 * @date   : 2015/7/24 17:27
 * @desc   :
 */

/**
 * 给日志表插入信息
 */
function setLog($msg){
    $data = Array(
        'clientip' => get_client_ip(),
        'time' => date('Y-m-d H:i:s',time()),
        'msg' => $msg
    );
    if(M("Log")->create($data)){
        M("Log")->add();
    }



}