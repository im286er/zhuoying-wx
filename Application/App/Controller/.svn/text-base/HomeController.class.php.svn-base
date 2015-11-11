<?php
/**
 * Author: NanQi
 * Date: 20150508 09:40
 */
namespace App\Controller;

use think\Controller;
use Think\Crypt\Driver\Think;
use Think\Log;
use Think\Model;
class HomeController extends Controller{
    protected $userID = '';
    protected $scid = 0;

    //必须登录才能调用Home控制器方法
    function _initialize() {
        $this->userID = authUserID();

//        $movieTime = S("user_movietime_".$this->userID);
//        $this->scid = $movieTime['id'];
        $this->scid = C('CURRENT_SCID');

        if(empty($this->scid)) {
            $this->scid = 0;
        }

    }

    /**
     * @author : NanQi
     * @date   : 20150508 10:44
     *
     * @desc     分享
     * @param    int type 分享类别
     * @return   是否可以抽奖
     */
    public function share(){
        $share = D("Sharerecord");
        $share->validation(array(
            array('type', 'require', '分享类别不能为空', Model::MUST_VALIDATE, 'regex'),
            array('type', 'integer', '分享类别格式不正确', Model::MUST_VALIDATE, 'regex'),
        ));

        if (I('type') == 6) {

            $arr = array(3, 5, 2);

            foreach ($arr as $a) {
                $cnt = $share
                    ->where('uid = %d and scid = %d and stype = %d', $this->userID, $this->scid, $a)
                    ->setInc('scount'); // 用户的积分加1

                if ($cnt < 1) {
                    $share->addSharp($this->userID, $this->scid, $a);
                }
            }

            $this->retSuccess();
        }

        $cnt = $share
            ->where('uid = %d and scid = %d and stype = %d', $this->userID, $this->scid, I('type'))
            ->setInc('scount'); // 用户的积分加1

        if ($cnt < 1) {
            $share->addSharp($this->userID, $this->scid, I('type'));
        }

        $this->retSuccess();
    }

    /**
     * @author : NanQi
     * @date   : 20150511 09:53
     *
     * @desc     奖品列表
     */
    public function prizeList(){
        if (empty($this->scid)) {

            $this->retError(201, '你还没有加入一个社交圈');
        }

        $prizeList = M('Prize')->field('id,pname,icon,level,sort')->where('scid = %d', $this->scid)->order('sort')->select();
        $this->retSuccess($prizeList);
    }

    /**
     * @author : NanQi
     * @date   : 20150508 12:34
     *
     * @desc     抽奖
     * @return   是否可以抽奖
     */
    public function luckyDraw(){

        $round = S('luckydray_round');
        if (empty($round)) {
            $this->retError(203, '现在不是抽奖时间');
        }

        if (empty($this->scid)) {

            $this->retError(201, '你还没有加入一个社交圈');
        }

        $draw = D('Drawrecord');
        $cnt = $draw->where('uid = %d and scid = %d and round = %d',
            $this->userID, $this->scid, $round)->count();

        //Log::record($cnt, Log::NOTICE);

        if ($cnt) {
            $this->retError(201, '本轮你已经抽过一次奖');
        }
        //TODO 暂时抽奖逻辑写在代码中,以后可以使用配置或者工作流
        if ($round == 1) {
            //1. 分享到微信朋友圈/微博/QQ空间/豆瓣只要达到两个就可以抽奖
            $cnt1 = D("Sharerecord")->where('uid = %d and scid = %d and stype in (%d, %d, %d, %d)',
                $this->userID, $this->scid, 1,2,5,6)->count('scount');

            if ($cnt1 > 1) {
                $this->draw($round);
            }
            else {
                $this->retError(202, '分享到社交圈中次数不足2项，你已经分享了'.$cnt1.'项');
            }
        }
        elseif ($round == 2) {
            //2. 分享到微信好友/QQ好友达到10个就可以抽奖
            $cnt2 = D("Sharerecord")->where('uid = %d and scid = %d and stype in (%d, %d)',
                $this->userID, $this->scid, 3,4)->sum('scount');

            $cnt2 = $cnt2 == null ? 0 : $cnt2;

            if ($cnt2 > 9) {
                $this->draw($round);
            }
            else {
                $this->retError(202, '分享给好友次数不足10个，你已经分享了'.$cnt2.'个');
            }
        }
        elseif ($round >= 3) {
            //3. 使用漂流瓶社交达到20次就可以抽奖

            //这里大奖独立判断
            $jackpotUserID = S('luckydray_jackpot');
            if ($this->userID == $jackpotUserID) {

                $this->draw($round, 4);//大奖暂时写死Index为4
                S('luckydray_jackpot', null);
            }

            //非大奖抽奖
            $cnt3 =  M()->query("SELECT SUM(cnt) AS cnt FROM (SELECT MIN(cnt) AS cnt FROM (SELECT SUID + RUID AS UNIONID, cnt FROM (SELECT SUID, RUID, COUNT( 1 ) AS cnt FROM  t_bottle WHERE SCID = %d AND ((SUID = %d AND RUID <> 0) OR (SUID <> 0 AND RUID = %d)) GROUP BY SUID, RUID ) T1) T2 GROUP BY UNIONID HAVING COUNT(cnt) > 1) T3",
                $this->scid, $this->userID, $this->userID);

            if (is_array($cnt3)) {
                $cnt3 = $cnt3[0];

                $cnt3 = $cnt3['cnt'] == null ? 0 : $cnt3['cnt'];
            }
            else {
                $cnt3 = 0;
            }

            if ($cnt3 > 9) {
                $this->draw($round);
            }
            else {
                $this->retError(202, '漂流瓶互动次数不足10次，你已经使用了'.$cnt3.'次');
            }
        }

        $this->retError();
    }

    private function draw($round, $index = -1){

        $prize = D('Prize')->field('id,pname,icon,level,odds,sort')->where('scid = %d and cntremain > 0', $this->scid)->select();

        $arr = array();

        foreach ($prize as $key => $val) {
            $arr[] = (int)$val['odds'];
        }

        if ($index === -1) {

            $index = get_rand($arr); //根据概率获取奖项id
        }

        //设置获奖物品角度
        $prize[$index]['angle'] = (((int)$prize[$index]['sort']) * 2 - 1) * 22.5;

        $pid = $prize[$index]['id'];

        $this->setPrize($round, $pid);

        $this->retSuccess($prize[$index]);
    }

    private function setPrize($round, $pid){
        M()->startTrans();

        D('Prize')->where('id = %d', $pid)->setDec('cntremain');
        D('Drawrecord')->addDrawrecord($this->userID, $this->scid, $round, $pid);

        M()->commit();
    }


}