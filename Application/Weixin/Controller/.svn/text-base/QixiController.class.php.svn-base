<?php

namespace Weixin\Controller;
use Think\Controller;
use Think\Model;

class QixiController extends Controller{
    public function launch() {
        if (IS_POST) {
            $activity = D('QixiActivity');

            $open_id = I('post.open_id');

            if ($open_id != 'oVe75s2v_gh8E_ed5-MS57NVVXhg') {
                $cnt = $activity->where("open_id = '%s'", $open_id)->count();

                if ($cnt) {
                    $this->retError(401, '你已经参与此次活动，请分享朋友圈收集祝福');
                }
            }

            $flg = $activity->create();
            if ($flg) {
                $id = $activity->add();

                if ($id) {
                    $this->retSuccess($id);
                }
                else {
                    $this->retError();
                }
            }
        }
        else {
            $code = I('get.code');

            $user = D('User');

            //通过code获得openid
            if (empty($code)) {
                //触发微信返回code码
                $url = $user->createOauthUrlForCode('http://weixin.myline.cc/weixin/qixi/launch');
                header("Location: $url");
                exit;
            }

            $userinfo = $user->get_userinfo_public($code);

            if ($userinfo['errcode']) {
                //触发微信返回code码
                $url = $user->createOauthUrlForCode('http://weixin.myline.cc/weixin/qixi/launch');
                header("Location: $url");
                exit;
            }


            $package = D('Jssdk')->getSignPackage();

            if ($userinfo['openid'] != 'oVe75s2v_gh8E_ed5-MS57NVVXhg') {
                $activity = D('QixiActivity')->where("open_id = '%s'", $userinfo['openid'])->find();
                if ($activity) {
                    $this->assign('activity', $activity);
                }
            }

            $this->assign('package', $package);
            $this->assign('userinfo', $userinfo);
            $this->display();
        }
    }

    public function blessing($activityid) {
        if (IS_POST) {
            $blessing = M('QixiBlessing');

            $cnt = $blessing->where("activity_id = %d and open_id = '%s'", $activityid, I('post.openid'))->count();
            if ($cnt) {
                $this->retError(401, '不能重复祝福');
            }

            $activity = D('QixiActivity')->find($activityid);

            if ($activity['blessing'] >= 77) {
                $this->success('77个祝福已经集满，快通知他领奖吧');
            }

            $data = array(
                'activity_id' => $activityid,
                'open_id' => I('post.openid'),
                'nickname' => I('post.nickname'),
                'avatar' => I('post.avatar')
            );

            $flg = $blessing->create($data);

            if ($flg) {
                $flg = $blessing->add($data);

                if ($flg) {
                    D('QixiActivity')->where('id = %d', $activityid)->setInc('blessing');

                    if ($activity['blessing'] >= 76) {
                        $this->retSuccess('77个祝福已经集满，快通知他领奖吧');
                    }
                    else {
                        $this->retSuccess('感谢你的祝福，使得他们又进了一步');
                    }
                }
            }

            $this->retError(501, '祝福过程出了点小意外，请稍候再试');
        }
        else {
            $code = I('get.code');

            $user = D('User');

            //通过code获得openid
            if (empty($code)) {
                //触发微信返回code码
                $url = $user->createOauthUrlForCode('http://weixin.myline.cc/weixin/qixi/blessing?activityid='.$activityid, 'snsapi_base');
                header("Location: $url");
                exit;
            }

            $userinfo = $user->get_userinfo_public($code);

            if ($userinfo['errcode']) {
                //触发微信返回code码
                $url = $user->createOauthUrlForCode('http://weixin.myline.cc/weixin/qixi/blessing?activityid='.$activityid, 'snsapi_base');
                header("Location: $url");
                exit;
            }

            $activity = D('QixiActivity')->find($activityid);
            if (empty($activity)) {
                $url = $user->createOauthUrlForCode('http://weixin.myline.cc/weixin/qixi/launch');
                header("Location: $url");
                exit;
            }

            $package = D('Jssdk')->getSignPackage();
            $this->assign('package', $package);

            $this->assign('userinfo', $userinfo);
            $this->assign('activity', $activity);

            $cnt = M('QixiBlessing')->where("activity_id = %d and open_id = '%s'", $activityid, $userinfo['openid'])->count();
            if ($cnt) {
                $this->assign('isBlessing', $cnt);
            }

            if ($activity['blessing'] >= 77) {
                $this->display('down'); 
            }
            else if ($activity['blessing'] >= 17 && $activity['open_id'] == $userinfo['openid'] && $activity['status'] == '1') {
                $this->display('down'); 
            }
            else {
                $this->display();
            }
        }
    }

    public function zhufu($activityid) {
        $activity = D('QixiActivity')->find($activityid);
        if (empty($activity)) {
            $url = $user->createOauthUrlForCode('http://weixin.myline.cc/weixin/qixi/launch');
            header("Location: $url");
            exit;
        }

        $list = M('QixiBlessing')->where('activity_id = %d', $activityid)->select();
        $this->assign('_list', $list);

        $package = D('Jssdk')->getSignPackage();
        $this->assign('package', $package);

        $this->assign('activity', $activity);
        $this->display();
    }

    public function isLaunch($open_id) {

        if ($open_id == 'oVe75s2v_gh8E_ed5-MS57NVVXhg') {
            $this->retSuccess();
        }

        $cnt = D('QixiActivity')->where("open_id = '%s'", $open_id)->count();

        if ($cnt) {
            $this->retError(401, '你已经参与此次活动，点击查看你的活动');
        }

        $this->retSuccess();
    }

    public function finish() {

        debug_log('finish');
        
        D('QixiActivity')->validation(array(
            array('phone_number', 'require', '手机号不能为空', Model::MUST_VALIDATE, 'regex'),
            array('city', 'require', '城市不能为空', Model::MUST_VALIDATE, 'regex'),
            array('phone_number', 'phonenumber', '手机号格式不正确', Model::MUST_VALIDATE, 'regex'),
            array('phone_number', '', '该手机号已经领奖', Model::EXISTS_VALIDATE, 'unique'),
        ));

        $activity = D('QixiActivity')->find(I('post.activityid'));

        if (empty($activity)) {
            $this->retError('找不到你的活动');
        }

        if ($activity['open_id'] != I('post.openid')) {
            $this->retError('你不是此次活动的发起者');
        }

        $data = array(
            'phone_number' => I('post.phone_number'),
            'city' => I('post.city'),
            'status' => 2
        );

        $flg = D('QixiActivity')->where('id = %d', I('post.activityid'))->save($data);

        if ($flg) {
            $result = $this->send_get('http://weixin.myline.cc/Weixin/Template/qixi_tickets?activityid='.I('post.activityid'));
            $this->retSuccess('提交成功');
        }
        else {
            $this->retError(501, '提交过程发生意外，请联系工作人员');
        }
    }

    public function attention($openid, $activityid) {
        debug_log('attention:'.$openid.':'.$activityid);
        S($openid, $activityid);
    }

    public function uploadResource($mediaID) {
        $media = D('Resource')->getTempResource($mediaID);

        $result = D('Qiniu', 'Logic')->upload($media);
        if($result){
            $this->retSuccess($result['downLink'].'?imageView2/1/w/480');
        }

        $this->retError('上传失败');
    }

    function send_get($url) {
        //初始化
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);

        return json_decode($output, true);
    }

}