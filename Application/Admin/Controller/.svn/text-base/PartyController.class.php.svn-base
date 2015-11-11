<?php
/**
 * Author: NanQi
 * Date: 20150512 20:59
 */
namespace Admin\Controller;
class PartyController extends AdminController {
    public function index() {
        $name = I('title');
        $map['status'] = array('gt', -1);

        if (is_numeric($name)) {
            $map['id|title'] = array(intval($name), array('like', '%' . $name . '%'), '_multi' => true);
        } else {
            $map['title'] = array('like', '%' . (string)$name . '%');
        }

        $party = D('Party')->relation(true);

        $list = $this->lists($party, $map, 'starttime desc');
        $this->assign('_list', $list);
        $this->display();
    }

    public function shareRecord($pid) {
        $name = I('stype');

        $map['scid'] = array('exp', ' = (select id from t_socialcircle where pid = '.$pid.')');

        if (is_numeric($name)) {
            $map['stype'] = array(intval($name), array('eq', $name));
        }

        $party = D('Sharerecord')->relation(true);

        $list = $this->lists($party, $map, 'stype, scount desc');
        int_to_string($list, array(
            'stype' => array(
                1 => '微博',
                2 => '微信朋友圈',
                3 => '微信好友',
                4 => 'QQ好友',
                5 => 'QQ空间',
                6 => '豆瓣广播',
            )
        ));
        $this->assign('_list', $list);
        $this->assign('pid', $pid);
        $this->display();
    }

    public function luckyDraw($pid){
        $name = I('searchuid');

        $map['scid'] = array('exp', ' = (select id from t_socialcircle where pid = '.$pid.')');

        if (is_numeric($name)) {
            $map['uid'] = array(intval($name), array('eq', $name));
        }

        $party = D('Drawrecord')->relation(true);

        $list = $this->lists($party, $map, 'round desc,createtime desc');

        $round = S('luckydray_round');

        $uid = S('luckydray_jackpot');//中大奖用户ID
        if ($uid) {
            $user = M('User')->find($uid);
            $this->assign('user', $user);
        }


        $this->assign('_list', $list);
        $this->assign('pid', $pid);
        $this->assign('round', $round);
        $this->display();
    }

    public function setRound($pid, $round, $uid = 0){
        S('luckydray_round', $round, 60 * 5);//五分钟

        if ($round == 3) {
            $sc = M('Socialcircle')->where('pid = %d', $pid)->find();

            //判断在本社交圈中是否已经有人中大奖
            //TODO 这里暂时写死大奖ID 5
            $cnt = M('Drawrecord')->where('scid = %d and pid = 5', $sc['id'])->count();
            if (!$cnt) {
                $userList = M('SocialcircleUser')->field('uid,cnt')->where('scid = %d and cnt > 9', $sc['id'])->select();

                $arr = array();

                foreach ($userList as $key => $val) {
                    $arr[$val['uid']] = (int)$val['cnt'];
                }

                $userID = get_rand($arr); //根据概率获取奖项id

                if ($uid) {
                    $userID = $uid;
                }

                S('luckydray_jackpot', $userID, 60 * 5);//中大奖用户ID
            }
        }

        $this->success('设置成功', U('luckyDraw', array(
            'pid' => $pid,
            'uid' => $uid,
        )));
    }

    public function prizeList($pid){
        $pname            =   I('pname');
        $map['scid'] = array('exp', ' = (select id from t_socialcircle where pid = '.$pid.')');

        if(is_numeric($pname)){
            $map['id|pname'] =   array(intval($pname),array('like','%'.$pname.'%'),'_multi'=>true);
        }else{
            $map['pname']    =   array('like', '%'.(string)$pname.'%');
        }
        $list   = $this->lists('Prize', $map, 'sort');
        $this->assign('pid', $pid);
        $this->assign('_list', $list);
        $this->display();
    }

    public function qualification($pid){
        $uid = I('uid');

        $map['scid'] = array('exp', ' = (select id from t_socialcircle where pid = '.$pid.')');

        if (is_numeric($uid)) {
            $map['uid'] = array(intval($uid), array('eq', $uid));
        }

        $party = D('SocialcircleUser')->relation(true);

        $list = $this->lists($party, $map, 'cnt desc');

        $this->assign('_list', $list);
        $this->assign('pid', $pid);
        $this->display();
    }

    public function bottleRecord($pid){
        $name = I('suid');

        $map['scid'] = array('exp', ' = (select id from t_socialcircle where pid = '.$pid.')');

        if (is_numeric($name)) {
            $map['suid'] = array(intval($name), array('eq', $name));
        }

        $bottle = D('Bottle')->group('suid,ruid')->relation(true);

        $list = $this->lists($bottle, $map, 'cnt desc', 'suid,ruid,count(1) as cnt');

        $this->assign('_list', $list);
        $this->assign('pid', $pid);
        $this->display();
    }
}