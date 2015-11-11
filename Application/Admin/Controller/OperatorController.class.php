<?php
/**
 * Author: NanQi
 * Date: 20150508 12:48
 */
namespace Admin\Controller;
class OperatorController extends AdminController {

    public function index() {
        $this->display();
    }

    public function luckyDraw($id){
        S('luckydray_round', $id, 60 * 50);//五分钟
        $this->redirect('index');
    }

    public function vertify($code){
        $order = M('Order')->where('orderno = (select orderno from t_orderrecord where vertifycode = %s)', $code)->find();

        if (empty($order)) {
            $this->error('验证码错误');
        }

        $sc = M('Socialcircle')->where('pid = %d', $order['pid'])->find();

        $user = D('User')->relation(true)
            ->field('id,phonenumber')
            ->where('id = %s', $order['uid'])
            ->find();

        $user['avatar'] = $user['avatar'].'?imageView2/1/h/512';

        $this->assign('scid', $sc['id']);
        $this->assign('user', $user);
        $this->display();
    }

    public function signin($scid, $uid){

        $cnt = M('SocialcircleUser')->where('scid = %d and uid = %d', $scid, $uid)->count();
        if ($cnt) {
            $this->ajaxReturn('用户已验证');
        }

        $flg = M('SocialcircleUser')->add(array(
            'scid' => $scid,
            'uid' => $uid,
        ));

        $user_sc_CacheKey = "user_".$uid.'_scid_'.$scid;
        S($user_sc_CacheKey, true, 60 * 60 * 24);

        if (!$flg) {
            $this->ajaxReturn('操作失败');
        }

        $this->ajaxReturn('OK');
    }
}