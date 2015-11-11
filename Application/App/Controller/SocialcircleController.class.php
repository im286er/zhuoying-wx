<?php
/**
 * Author: NanQi
 * Date: 20150427 14:37
 */
namespace App\Controller;

use think\Controller;

class SocialcircleController extends Controller{

    protected $userID = '';

    //必须登录才能调用控制器方法
    function _initialize() {
        $this->userID = authUserID();
    }

    /**
     * @author : NanQi
     * @date   : 20150428 15:19
     *
     * @desc     获取用户对应的圈子信息
     * @return   社交圈信息
     */
    public function circle(){

//        $currentMovieTime = S("user_movietime_".$this->userID);
//
//        if (empty($currentMovieTime)) {
//            $movieList = S('movietime');
//
//            if (empty($movieList)) {
//                $this->retError(201, '没有可加入的社交圈');
//            }
//
//            $pids = M()->query('
//select pid from t_order t where uid = %d and not exists
//(
//  select distinct(pid) from
//  (
//    select t1.pid from t_socialcircle t1, t_socialcircle_user t2 where t1.id = t2.scid and t2.uid = %d and isjoin = 1
//  ) t3 where t.pid = t3.pid
//)
//', $this->userID, $this->userID);
//
//            foreach ($movieList as $movietime) {
//                $openstatus = $movietime['openstatus'];
//                //社交圈开启状态才符合要求
//                if ($openstatus == 1) {
//
//                    $pid = $movietime['pid'];
//                    foreach ($pids as $joinPid) {
//                        //用户购票未加入的活动相符
//                        if ($joinPid['pid'] == $pid) {
//                            $starttime = $movietime['starttime'];
//                            $endtime = $movietime['endtime'];
//
//                            if (time() > $starttime - 60 * 10 - 1
//                                && time() < $endtime + 60 * 10 + 1) {
//                                $currentMovieTime = $movietime;
//
//                                S("user_movietime_".$this->userID, $currentMovieTime, $endtime - time());
//                            }
//                        }
//                    }
//                }
//            }
//        }
//
//        if (is_null($currentMovieTime)) {
//            $this->retError(201, '当前时间没有你可以加入的社交圈');
//        }
//
//        $scid = $currentMovieTime['id'];
//
//        $signin = S("user_".$this->userID.'_scid_'.$scid);
//        if (!$signin) {
//            $this->retError(202, '请持二维码在活动现场进行签到');
//        }
//
//        $interval = S("scid_interval_".$scid);
//        if (!$interval) {
//            $this->retError(203, '不在活动时间内');
//        }
        //$this->retError(203, '不在活动时间内');

        $scid = C('CURRENT_SCID');

        $isjoin = D('SocialcircleUser')->where('scid = %d and uid = %d',
            $scid, $this->userID)->getField('isjoin');

        $movietime['isjoin'] = $isjoin == 1;

        $this->retSuccess($movietime);
    }

    /**
     * @author : NanQi
     * @date   : 20150427 17:41
     *
     * @desc     加入社交圈
     * @return   bool
     */
    public function join(){

//        $movieTime = S("user_movietime_".$this->userID);
//
//        if (empty($movieTime)) {
//            $this->retError(201, '没有可加入的社交圈');
//        }
//
//        $scid = $movieTime['id'];
        $scid = C('CURRENT_SCID');

        $isjoin = D('SocialcircleUser')->where('scid = %d and uid = %d',
            $scid, $this->userID)->getField('isjoin');

        if ($isjoin != 0) {

            $this->retError(202, '用户已在社交圈内');
        }

        $im = D('Im', 'Logic');

        $token = S('user_token_'.$this->userID);
        if (empty($token)) {
            $requ = $im->getToken($this->userID, null, null);
            if ($requ->code != 200) {
                $this->retError($requ->code, json_encode($requ));
            }

            $token = $requ->token;

            $saveData = array(
                'id' => $this->userID,
                'token' => $token,
            );
            D('User')->save($saveData);

            //token有效期为一天
            S('user_token_'.$this->userID, $token, 60 * 60 * 24);
        }

        //加入群组
        $requ = $im->joinCircle($this->userID, $scid);
        //if ($requ->code != 200) {
        //    $this->retError($requ->code, json_encode($requ));
        //}

        D('SocialcircleUser')->add(array(
            'scid' => $scid,
            'uid' => $this->userID,
            'isjoin' => 1
        ));


//        D('SocialcircleUser')->where('scid = %d and uid = %d',
//            $scid, $this->userID)->setField('isjoin', 1);

        $this->retSuccess($token);
    }

    /**
     * @author : NanQi
     * @date   : 20150505 12:58
     *
     * @desc     社交圈中所有用户列表
     */
    public function userList(){
        $countnum = -1;
        $data = null;

//        $movieTime = S("user_movietime_".$this->userID);
//        $scid = $movieTime['id'];
        $scid = C('CURRENT_SCID');

        if (empty($scid)) {

            $this->retError(201, '你还没有加入一个社交圈');
        }

        $bottle = M()->query("select t2.uid,nickname,sex,avatar,job,signature,birthday from t_socialcircle_user t1, t_user t2 where t1.uid = t2.uid and t1.scid = %d and t1.isjoin = 1", $scid);

        $this->retSuccess($bottle);
    }

    /**
     * @author : NanQi
     * @date   : 20150525 14:06
     *
     * @desc     获取融云TOKEN
     */
    public function getToken(){

        $token = S('user_token_'.$this->userID);

        $requ = D('Im', 'Logic')->getToken($this->userID, null, null);
        if ($requ->code == 200) {
            $token = $requ->token;

            $saveData = array(
                'id' => $this->userID,
                'token' => $token,
            );
            D('User')->save($saveData);
        }
        else {
            $this->retError($requ->code, json_encode($requ));
        }

        S('user_token_'.$this->userID, $token, 60 * 60 * 24);

        $this->retSuccess($token);
    }
}