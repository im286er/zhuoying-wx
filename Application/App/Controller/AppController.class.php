<?php
/**
 * Author: NanQi
 * Date: 20150514 09:53
 */
namespace App\Controller;

use think\Controller;
use Think\Model;
class AppController extends Controller{
    /**
     * @author : NanQi
     * @date   : 20150513 18:11
     *
     * @desc     获取更新
     * @param    String version 当前版本
     * @return   URL
     */
    public function update(){
        M()->validation(array(
            array('version', 'require', '当前版本不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $currentVersion = C('CURRENT_VERSION');
        if (I('version') == $currentVersion) {
            $this->retSuccess('not');
        }

        $model = M('Appversion')->find($currentVersion);

        $this->retSuccess($model);
    }

    /**
     * @author : NanQi
     * @date   : 20150515 09:41
     *
     * @desc     获取系统时间
     * @return   系统时间
     */
    public function getSystemTime(){
        $this->retSuccess(time());
    }

    /**
     * @author : NanQi
     * @date   : 20150515 10:01
     *
     * @desc     意见反馈
     * @param    String otype 系统类型
     * @param    String ptype 手机型号
     * @param    String content 反馈内容
     * @return   bool
     */
    public function feedback(){
        $userID = authUserID();

        M()->validation(array(
            array('otype', 'require', '系统类型不能为空', Model::MUST_VALIDATE, 'regex'),
            array('content', 'require', '反馈内容不能为空', Model::MUST_VALIDATE, 'regex'),
            array('content', '1,2000', '反馈内容长度不正确', Model::MUST_VALIDATE, 'length'),
        ));

        D('Feedback')->addFeedback($userID, I('otype'), I('ptype'), I('content'));

        $this->retSuccess();
    }
}