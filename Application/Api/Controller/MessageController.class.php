<?php
/**
 * Created by PhpStorm.
 * User: 元凯
 * Date: 2015/8/18
 * Time: 16:54
 */

namespace Api\Controller;
use Think\Controller;
use Think\Model;

class MessageController extends Controller{
    /**
     * @param uid int 用户ID
     * return 用户消息列表
     */
    public function getSysMsg(){
        M()->validation(array(
            array('uid', 'require', '用户不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $uid = I('uid');

        $list=D("sysmsg")->where("uid = '$uid' and status > 0")->select();

        $this->retSuccess($list);
    }

    public function getUserMsg(){
        M()->validation(array(
            array('uid', 'require', '用户不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $uid=I("uid");

        $list = M()->query("SELECT t4.uid as oid, t6.nickname, t6.avatar, t5.content, t5.sendtime from (
                    select uid, max(id) as id from (
                    select id, oid as uid from (
                    SELECT oid,max(id) as id FROM t_usermsg 
                    where uid = '$uid'
                    group by oid) t1
                    UNION
                    select id, uid as uid from (
                    SELECT uid,max(id) as id FROM t_usermsg 
                    where oid = '$uid' 
                    group by uid) t2) t3
                    group by uid) t4, t_usermsg t5, t_user t6 where t4.id = t5.id and t4.uid = t6.id");

        $data = array(
            'Ouser' => $list
        );

        $this->retSuccess($data);
    }

    /**
     * @param uid int 用户ID
     * return 用户消息列表
     */
    public function getHistoryMsg(){
        M()->validation(array(
            array('uid', 'require', '用户不能为空', Model::MUST_VALIDATE, 'regex'),
            array('oid', 'require', '对方用户不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $uid=I("uid");
        $oid=I("oid");
        $cnt=D("user")->where("id=%d",$uid)->find();
        $Ouser=D("user")->where("id=%d",$oid)->field("id as oid,nickname,avatar")->find();
        if(!$cnt){
            $this->retError("该用户不存在!");
        }
        if(!$Ouser){
            $this->retError("对方用户不存在!");
        }
        $list=D("usermsg")->where("(uid=%d AND oid=%d) OR (uid=%d AND oid=%d) ",$uid,$oid,$oid,$uid)->field("uid,sendtime,content")->select();

        $Ouser['message']=$list;

        $this->retSuccess($Ouser);
    }

    //发送用户消息
    public function send_message() {
        M()->validation(array(
            array('uid', 'require', '用户不能为空', Model::MUST_VALIDATE, 'regex'),
            array('oid', 'require', '对方用户不能为空', Model::MUST_VALIDATE, 'regex'),
            array('content', 'require', '发送内容不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $uid = I('uid');
        $oid = I('oid');
        $content = I('content');
        $sendtime = time();

        $info = D('User')->cache('user_base_'.$uid, 3600 * 24)->find($uid);
        if (!$info) {
            $this->retError('发送用户不正确');
        }

        $usermsg = D('usermsg');

        $data = array(
            'uid' => $uid,
            'oid' => $oid,
            'content' => $content,
            'sendtime' => $sendtime,
        );

        if ($usermsg->create($data)) {
            $id = $usermsg->add();

            if ($id) {
                
                $sendData = array();
                $sendData['t'] = 1;
                $sendData['d'] = array(
                    'id' => $id,
                    'uid' => $uid,
                    'nickname' => $info['nickname'],
                    'avatar' => $info['avatar'],
                    'content' => $content,
                    'sendtime' => $sendtime,
                );

                D('Push', 'Logic')->sendUser($oid, '', json_encode($sendData));
                $this->retSuccess();
            }
        }

        $this->retError();
    }

    public function delete_user_message() {
        M()->validation(array(
            array('id', 'require', '消息不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $id = I('id');

        $flg = delete_data('usermsg', $id);

        if ($flg) {
            $this->retSuccess();
        }

        $this->retError();
    }

    public function delete_system_message() {
        M()->validation(array(
            array('id', 'require', '消息不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $id = I('id');

        $flg = delete_data('sysmsg', $id);

        if ($flg) {
            $this->retSuccess();
        }

        $this->retError();
    }
}