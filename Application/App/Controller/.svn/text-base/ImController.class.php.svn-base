<?php
/**
 * Author: NanQi
 * Date: 20150429 10:48
 */
namespace App\Controller;

use think\Controller;
use Think\Model;

class ImController extends Controller{

    protected $userID = '';
    protected $im = null;

    protected $scid = '';

    //必须登录才能调用Movie控制器方法
    function _initialize() {
        $this->userID = authUserID();
        $this->im = D('Im', 'Logic');

//        $movieTime = S("user_movietime_".$this->userID);
//        $this->scid = $movieTime['id'];
        $this->scid = C('CURRENT_SCID');

        if (empty($this->scid)) {

            $this->retError(201, '你还没有加入一个社交圈');
        }
    }

    public function test(){
        $ret = $this->im->sendGroupMessage('pickup', array(
            'pmsgid' => I('msgid'),
        ), $this->scid);

        $req = json_decode($ret);

        dump($req->code);
    }



    /**
     * @author : NanQi
     * @date   : 20150504 17:01
     *
     * @desc     获取我的漂流瓶
     */
    public function bottleList(){
        $data = M()->query('
select t2.uid,cnt,nickname,sex,avatar,job,signature,birthday from (
select case uid when 0 then %d else uid end as uid,
min(cnt) as cnt from (
select ruid as uid, count(1) as cnt
from t_bottle
where scid = %d and suid = %d
GROUP BY ruid
union
select suid as uid, count(1) as cnt
from t_bottle
where scid = %d and ruid = %d
GROUP BY suid
) t1
group by uid) t2, t_privacy t3 where t2.uid = t3.uid
', $this->userID, $this->scid, $this->userID, $this->scid, $this->userID);

        $this->retSuccess($data);
    }

    /**
     * @author : NanQi
     * @date   : 20150429 20:13
     *
     * @desc     扔瓶子
     * @param    String content 发送内容
     * @param    String privacy 隐私信息
     * @return   bool
     */
    public function throwBottle(){

        M()->validation(array(
            array('content', 'require', '发送内容不能为空', Model::MUST_VALIDATE, 'regex'),
            array('content', '4,500', '发送内容长度不正确', Model::MUST_VALIDATE, 'length'),
            array('content', 'checkSensitiveWord', '[222]回复内容中包含敏感词汇',Model::MUST_VALIDATE, 'function'),
        ));

        $this->im->throwBottle($this->userID, I('content'), $this->scid, I('privacy'), '');

        $this->retSuccess();
    }

    /**
     * @author : NanQi
     * @date   : 20150429 20:13
     *
     * @desc     捡瓶子
     * @param    int msgid 瓶子ID
     * @return   bool
     */
    public function pickupBottle(){

        M()->validation(array(
            array('msgid', 'require', '瓶子ID不能为空', Model::MUST_VALIDATE, 'regex'),
            array('msgid', 'number', '瓶子ID格式不正确', Model::MUST_VALIDATE, 'regex'),
        ));

        $data = D('Bottle')->relation('privacy')->find(I('msgid'));
        if (empty($data)) {
            $this->retError(201, '没有对应的瓶子ID');
        }

        if ($data['ispickup'] == '1') {
            $this->retError(202, '该瓶子已被拾取');
        }

        if ($data['ispublic'] == '0' && $data['ruid'] != '0' && $data['ruid'] != $this->userID) {
            $this->retError(203, '不能拾取扔给其他人的瓶子');
        }

        if ($data['suid'] == $this->userID) {
            $this->retError(204, '不能捡自己的瓶子');
        }

        D('Bottle')->pickupBottle(I('msgid'), $this->userID);

        $this->im->sendGroupMessage('pickup', array(
            'puid' => $this->userID,
            'pmsgid' => I('msgid'),
        ), $this->scid);

        $this->retSuccess($data);
    }

    /**
     * @author : NanQi
     * @date   : 20150429 20:13
     *
     * @desc     添加朋友
     * @param    int $uid 需要添加的用户ID
     * @return   bool
     */
    public function addFriend($uid){

        if ($uid == $this->userID) {
            $this->retError(210, '不能和自己成为朋友');
        }

        $cnt = D('Friend')->where("suid = '%d' or ruid = '%d'", $uid, $uid)->count();
        if (!$cnt) {
            $flg = D('Friend')->add(array(
                'suid' => $uid,
                'ruid' => $this->userID,
                'createtime' => time(),
            ));

            if (!$flg) {
                $this->retError(501, '添加好友异常');
            }
        }

        $this->retSuccess();
    }

    /**
     * @author : NanQi
     * @date   : 20150429 20:13
     *
     * @desc     回复消息
     * @param    int id 用户ID
     * @param    string content 回复内容
     * @param    String privacy 隐私信息
     * @return   bool
     */
    public function revert(){
        M()->validation(array(
            array('id', 'require', '用户ID不能为空', Model::MUST_VALIDATE, 'regex'),
            array('id', 'number', '用户ID格式不正确', Model::MUST_VALIDATE, 'regex'),
            array('content', 'require', '发送内容不能为空', Model::MUST_VALIDATE, 'regex'),
            array('content', '1,500', '发送内容长度不正确', Model::MUST_VALIDATE, 'length'),
            array('content', 'checkSensitiveWord', '[222]回复内容中包含敏感词汇',Model::MUST_VALIDATE, 'function'),
        ));

        if (I('id') == $this->userID) {
            $this->retError(204, '不能回复自己的瓶子');
        }

        $this->im->throwBottle($this->userID, I('content'), $this->scid, I('privacy'), I('id'));

        if (I('id') == 2) {
            $url = 'http://www.tuling123.com/openapi/api?key=02cda105656bd5bd4837b9c463d6046d&info='.I('content').'&userid='.$this->userID;
            $ret = $this->curl($url);
            $ret = json_decode($ret);

            $this->im->throwBottle(I('id'), $ret->text, $this->scid, I('privacy'), $this->userID);
        }

        //TODO 这里添加互动次数,如果对性能影响较大,抽奖时即时统计互动次数
        M()->execute("UPDATE t_socialcircle_user SET CNT = (SELECT SUM(COUNT) AS COUNT FROM (SELECT MIN(COUNT) AS COUNT FROM (SELECT SUID + RUID AS UNIONID, COUNT FROM (SELECT SUID, RUID, COUNT( 1 ) AS COUNT FROM  t_bottle WHERE SCID = %d AND ((SUID = %d AND RUID <> 0) OR (SUID <> 0 AND RUID = %d)) GROUP BY SUID, RUID ) T1) T2 GROUP BY UNIONID HAVING COUNT(COUNT) > 1) T3) WHERE SCID = %d AND UID = %d",
            $this->scid, $this->userID, $this->userID, $this->scid, $this->userID);

        M()->execute("UPDATE t_socialcircle_user SET CNT = (SELECT SUM(COUNT) AS COUNT FROM (SELECT MIN(COUNT) AS COUNT FROM (SELECT SUID + RUID AS UNIONID, COUNT FROM (SELECT SUID, RUID, COUNT( 1 ) AS COUNT FROM  t_bottle WHERE SCID = %d AND ((SUID = %d AND RUID <> 0) OR (SUID <> 0 AND RUID = %d)) GROUP BY SUID, RUID ) T1) T2 GROUP BY UNIONID HAVING COUNT(COUNT) > 1) T3) WHERE SCID = %d AND UID = %d",
            $this->scid, I('id'), I('id'), $this->scid, I('id'));

        $this->addFriend(I('id'));
        //$this->retSuccess();
    }

    /**
     * @author : NanQi
     * @date   : 20150505 13:33
     *
     * @desc     扔回大海
     * @param    int msgid 瓶子ID
     */
    public function throwBack(){
        M()->validation(array(
            array('msgid', 'require', '瓶子ID不能为空', Model::MUST_VALIDATE, 'regex'),
            array('msgid', 'number', '瓶子ID格式不正确', Model::MUST_VALIDATE, 'regex'),
        ));

        $data = D('Bottle')->find(I('msgid'));
        if (empty($data)) {
            $this->retError(201, '没有对应的瓶子ID');
        }

        if ($data['ispickup'] == '0') {
            $this->retError(202, '瓶子拾取状态异常');
        }

        if ($data['ispublic'] == '0') {
            $this->retError(203, '定向瓶子不能扔回大海');
        }

        if ($data['suid'] == $this->userID) {
            $this->retError(204, '瓶子所有者异常');
        }

        $uid = $data['suid'];

        $user = D('Privacy')->cache('privacy_'.$uid, 60 * 60 * 24)->find($uid);

        D('Bottle')->throwBack($data['id']);

        $this->im->sendGroupMessage('normal', array(
            'msgid'    => $data['id'],
            'suid'     => $uid,
            'snickname'=> $user['nickname'],
            'savatar'  => $user['avatar'],
            'scid'     => $this->scid,
            'ruid'     => 0,
            'ispublic' => 0,
            'content'  => $data['scontent'],
        ), $this->scid);

        $this->retSuccess();
    }

    /**
     * @author : NanQi
     * @date   : 20150429 20:14
     *
     * @desc     好友列表
     */
    public function friends(){
        $friends = M()->query("SELECT suid as uid, createtime FROM t_friend WHERE ruid = %d and scid = %d union SELECT ruid as uid, createtime FROM t_friend WHERE suid = %d and scid = %d",
            $this->userID, $this->scid, $this->userID, $this->scid);

        $this->retSuccess($friends);
    }

    /**
     * @author : NanQi
     * @date   : 20150505 16:02
     *
     * @desc     瓶子聊天记录
     * @param    int id 对方用户ID
     */
    public function bottleRecord(){
        M()->validation(array(
            array('id', 'require', '用户ID不能为空', Model::MUST_VALIDATE, 'regex'),
            array('id', 'number', '用户ID格式不正确', Model::MUST_VALIDATE, 'regex'),
        ));

        $countnum = -1;
        $data = null;

        if (I('page') != null && I('limit') != null) {

            $countnum = D('Bottle')->field('id,suid,scontent,createtime')
                ->where('scid = %d and ((suid = %d and ruid = %d) or (suid = %d and ruid = %d))'
                    , $this->scid, $this->userID, I('id'), I('id'), $this->userID)
                ->count();

            $data = D('Bottle')->field('id,suid,scontent,createtime')
                ->relation('privacy')
                ->where('scid = %d and ((suid = %d and ruid = %d) or (suid = %d and ruid = %d))'
                    , $this->scid, $this->userID, I('id'), I('id'), $this->userID)
                ->order('createtime desc')
                ->limit(I('limit'))
                ->page(I('page'))
                ->select();
        }
        else {

            $data = D('Bottle')->field('id,suid,scontent,createtime')
                ->relation('privacy')
                ->order('createtime desc')
                ->where('scid = %d and ((suid = %d and ruid = %d) or (suid = %d and ruid = %d))'
                    , $this->scid, $this->userID, I('id'), I('id'), $this->userID)
                ->select();

            $countnum = count($data);
        }

        $this->retPager($countnum, $data);
    }

    protected function curl($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}