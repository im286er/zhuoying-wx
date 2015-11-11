<?php
/**
 * Author: NanQi
 * Date: 20150419 09:59
 */
namespace App\Controller;

use think\Controller;

class PushController extends Controller{

    protected $push = null;

    //必须登录才能调用Movie控制器方法
    function _initialize() {

        $this->push = D('JPush', 'Logic');
    }

    public function PushNews(){

        $news = D("News")->where("createtime = (select max(createtime) from t_news)")->field('id,pushcontent')->find();

        $ret = $this->push->pushNotificationByTags(C('UNKNOWN_TAG'), $news['pushcontent'],
            null, array(
                "redirect" => "recommend",
                "recommend_id" => $news['id'],
            ));

        dump($ret);
    }

    /**
     * @author : NanQi
     * @date   : 20150421 11:31
     *
     * @desc     设备注册，每次打开应用都会调用
     * @param    string registrationid jpush设备注册ID
     * @return   是否注册成功
     */
    public function Registration(){

        if(I('registrationid') == null){
            $this->retError(40001,'参数registrationid不能为空');
        }
        //判断电话号码是否正确
        if (strlen(I("registrationid")) > 50) {
            $this->retError(40002, "使用了非法的registrationid");
        }

        $cnt = M('registration')->where("regid = '%s'", I('registrationid'))->count();
        if (!$cnt) {

            $this->push->AddTags(I('registrationid'), C('UNKNOWN_TAG'));

            $this->success('注册成功');
        }

        $this->success();
    }

    public function pushAlias(){

        dump($this->push->pushNotificationByAlias(I('alias'), I('msg')));
    }

    public function getAliasTags(){

        dump($this->push->getDeviceTagAlias(I('rid')));
    }

    public function pushNewsAll(){

        $news = D("News")->where("createtime = (select max(createtime) from t_news)")->field('id,pushcontent')->find();

        $ret = $this->push->pushNotificationAll($news['pushcontent'],
            null, array(
                "redirect" => "recommend",
                "recommend_id" => $news['id'],
            ));

        dump($ret);
    }

    public function testPush(){

        $ret = $this->push->pushNotificationAlliOS('人家有背景，我有背影');
        dump($ret);
    }
}