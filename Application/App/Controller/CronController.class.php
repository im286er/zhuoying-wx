<?php
/**
 * Author: NanQi
 * Date: 20150422 13:59
 */
namespace App\Controller;

use think\Controller;
use Think\Crypt\Driver\Think;
use Think\Log;

class CronController extends Controller{

    public function joke(){
        D('JPush', 'Logic')->pushNotificationAll('起床上班啦!!!');
    }

    public function analysis(){

    }

    public function pushNews(){
        //定时发送资讯
        $news = D("News")->field('id,pushcontent')->where("sendstate = 1 and createtime = (select max(createtime) from t_news)")->find();

        if (!empty($news)) {
            $ret = D('JPush', 'Logic')->pushNotificationByTags(C('UNKNOWN_TAG'), $news['pushcontent'],
                null, array(
                    "redirect" => "recommend",
                    "recommend_id" => $news['id'],
                ));

            $news['createtime'] = time();
            $news['sendstate']  = 2;
            $news->save();

            S('movietime', null);
        }
    }

    /**
     * @author : NanQi
     * @date   : 20150428 17:22
     *
     * @desc     每天需要执行的一个任务，做系统优化和一些缓存的建立
     * @return   bool
     */
    public function optimization(){

        //电影时间(暂时是社交时间)做缓存处理
        $movieList = $this->getMovieList();
        dump($movieList);

        //缓存敏感词汇
        $trie = D('Trie', 'Logic');
        $wordlist = M('sensitiveword')->field('oldword')->select();

        foreach ($wordlist as $word) {

            $trie->insert($word['oldword']);
        }

        S('sensitiveword', $trie->export(), 60 * 60 * 24);
    }

    public function schedule5(){

        if(S('schedule_order')) {
            //订单过期
            $cnt = D('Order')
                ->where('paystatus = 0 and ordertime < %d', time() - 60 * 15)
                ->save(array(
                    'paystatus' => -2
                ));

            if ($cnt) {

                S('schedule_order', null);
            }
        }

        $this->movieStartAndEnd();
    }

    public function movieStartAndEnd(){

        $movieList = S('movietime');
        if (empty($movieList)) {
            $movieList = $this->getMovieList();
        }

        foreach ($movieList as $movietime) {
            $starttime = $movietime['starttime'];
            $endtime = $movietime['endtime'];
            $openstatus = $movietime['openstatus'];

            if ($openstatus == 0 && $starttime > time() && $starttime - time() < 60 * 10 + 1) {

                S("scid_interval_".$movietime['id'], true, $endtime - $starttime);
                $this->movieStart($movietime);
            }

            if ($openstatus == 1 && time() > $endtime && time() - $endtime < 60 * 10 + 1) {

                $this->movieEnd($movietime);
            }
        }
    }

    function movieStart($movietime){
        \Think\Log::record('开场提示:'.$movietime['pid'].'-'.$movietime['title']);

        //推送开场提醒
        D('JPush', 'Logic')->pushNotificationByTags(C('PUSH_PARTY_PREFIX').$movietime['pid'],
            '你参加的'.$movietime['title'].'活动即将开始，摇一摇加入社交圈，开启捉影之旅。',
            null, array(
                "redirect" => "circleentry",
            ));

        //向融云服务器发送创建群组的请求
        $im = D('Im', 'Logic');
        $im->createCircle($movietime['id']);

        M('Socialcircle')->where('id = %d', $movietime['id'])->setField('openstatus', 1);
    }

    function movieEnd($movietime){

//        $ret = D('JPush', 'Logic')->pushNotificationByTags(C('PUSH_PARTY_PREFIX').$movietime['pid'],
//            '你参加的'.$movietime['title'].'活动即将开始，摇一摇加入社交圈，开启捉影之旅。',
//            null, array(
//                "redirect" => "circleentry",
//            ));

        //活动完毕删除当前活动Tag
        D('JPush', 'Logic')->RemoveTags(array(C('PUSH_PARTY_PREFIX').$movietime['pid']));

        M('Socialcircle')->where('id = %d', $movietime['id'])->setField('openstatus', 2);
    }

    function getMovieList() {
        $movieList = M()->query("
                select t1.id, t1.pid, t2.title, t1.openstatus, t2.starttime, t2.endtime
                from t_socialcircle t1, t_party t2, t_news t3
                where t1.pid = t2.id and t2.nid = t3.id and t1.openstatus in (0, 1)
                ");
        S('movietime', $movieList, 60 * 60 * 24);

        return $movieList;
    }

    public function loadData(){

        $this->optimization();

        $scid = C('CURRENT_SCID');

        //向融云服务器发送创建群组的请求
        $im = D('Im', 'Logic');
        $im->createCircle($scid);

        M('Socialcircle')->where('id = %d', $scid)->setField('openstatus', 1);
    }

    public function clearData(){
        S('party_'.C('CURRENT_SCID'), null);
    }

    public function test(){
        $movieList = S('movietime');
        if (empty($movieList)) {
            $movieList = $this->getMovieList();
        }

        foreach ($movieList as $movietime) {
            $starttime = $movietime['starttime'];
            $endtime = $movietime['endtime'];
            $openstatus = $movietime['openstatus'];

            //if ($openstatus == 0 && $starttime <= time() && time() <= $endtime) {

                S("scid_interval_".$movietime['id'], true, $endtime - $starttime);

                //向融云服务器发送创建群组的请求
                $im = D('Im', 'Logic');
                $im->createCircle($movietime['id']);

                M('Socialcircle')->where('id = %d', $movietime['id'])->setField('openstatus', 1);

                dump('已开启相关活动');
            //}
        }
        dump($movieList);
    }
}