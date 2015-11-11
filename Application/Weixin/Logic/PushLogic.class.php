<?php
/**
 * Author: NanQi
 * Date: 20150512 14:47
 */
namespace Weixin\Logic;
header("Content-Type: text/html; charset=utf-8");

require_once('lib/getui/IGt.Push.php');
require_once('lib/getui/igetui/IGt.AppMessage.php');
require_once('lib/getui/igetui/IGt.APNPayload.php');
require_once('lib/getui/igetui/template/IGt.BaseTemplate.php');
require_once('lib/getui/IGt.Batch.php');

use IGeTui;
use IGtBatch;
use IGtLinkTemplate;
use IGtNotyPopLoadTemplate;
use IGtNotificationTemplate;
use IGtTransmissionTemplate;
use DictionaryAlertMsg;
use IGtAppMessage;
use IGtAPNPayload;
use SimpleAlertMsg;
use IGtSingleMessage;
use IGtTarget;

class PushLogic extends BaseLogic {
    protected $igt;

    public function _initialize(){
        // Vendor('getui.IGt#Push');
        // Vendor('getui.igetui.IGt#AppMessage');
        // Vendor('getui.igetui.IGt#APNPayload');
        // Vendor('getui.igetui.template.IGt#BaseTemplate');
        // Vendor('getui.IGt#Batch');

        define('APPKEY',C('PUSH_APPKEY'));
        define('APPID',C('PUSH_APPID'));
        define('MASTERSECRET',C('PUSH_MASTERSECRET'));
        define('HOST',C('PUSH_HOST'));

        $this->igt = new IGeTui(C('PUSH_HOST'),C('PUSH_APPKEY'),C('PUSH_MASTERSECRET'));
    }

    public function reminderUser($uid, $v, $content) {
        $device = D('Device')->cache('device_'.$uid, 3600 * 24)->where("uid = $uid")->find();
        if ($device) {
            $sendData = array();
            $sendData['t'] = 2;
            $sendData['v'] = $v;
            $sendData['d'] = array(
                'content' => $content,
                'sendtime' => time(),
            );
            $rep = $this->pushMessageToSingle($device['deviceid'], '', json_encode($sendData));
        } 
    }

    public function sendUser($uid, $msg, $para = '') {
        $device = D('Device')->cache('device_'.$uid, 3600 * 24)->where("uid = $uid")->find();
        if ($device) {
            $rep = $this->pushMessageToSingle($device['deviceid'], $msg, $para);
        } 
    }

    public function sendAll($msg, $para = '') {
        return $this->pushMessageToApp($msg, $para); 
    }

    //单推接口案例
    public function pushMessageToSingle($cid, $msg, $para = ''){
            
        
        //消息模版：
        // 1.TransmissionTemplate:透传功能模板
        // 2.LinkTemplate:通知打开链接功能模板
        // 3.NotificationTemplate：通知透传功能模板
        // 4.NotyPopLoadTemplate：通知弹框下载功能模板
        
    //      $template = IGtNotyPopLoadTemplateDemo();
    //      $template = IGtLinkTemplateDemo();
        // $template = $this->IGtNotificationTemplateDemo('测试单推透传消息');
        $template = $this->IGtTransmissionTemplateDemo($msg, $para);

        //个推信息体
        $message = new IGtSingleMessage();

        $message->set_isOffline(true);//是否离线
        $message->set_offlineExpireTime(3600*12*1000);//离线时间
        $message->set_data($template);//设置推送消息类型
    //  $message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送
        //接收方
        $target = new IGtTarget();
        $target->set_appId(C('PUSH_APPID'));
        $target->set_clientId($cid);
    //    $target->set_alias(Alias);

        try {
            $rep = $this->igt->pushMessageToSingle($message, $target);
            // var_dump($rep);
            // echo ("<br><br>");

        }catch(RequestException $e){
            $requstId =e.getRequestId();
            $rep = $this->igt->pushMessageToSingle($message, $target, $requstId);
            // var_dump($rep);
            // echo ("<br><br>");
        }

        return $rep;

    }

    public function pushMessageToSingleBatch()
    {
        putenv("gexin_pushSingleBatch_needAsync=false");

        $batch = new IGtBatch(APPKEY, $igt);
        $batch->setApiUrl(HOST);
        //$igt->connect();
        //消息模版：
        // 1.TransmissionTemplate:透传功能模板
        // 2.LinkTemplate:通知打开链接功能模板
        // 3.NotificationTemplate：通知透传功能模板
        // 4.NotyPopLoadTemplate：通知弹框下载功能模板

    //    $template = IGtNotyPopLoadTemplateDemo();
        $template = IGtLinkTemplateDemo();
        //$template = IGtNotificationTemplateDemo();
    //    $template = IGtTransmissionTemplateDemo();

        //个推信息体
        $message = new IGtSingleMessage();
        $message->set_isOffline(true);//是否离线
        $message->set_offlineExpireTime(12 * 1000 * 3600);//离线时间
        $message->set_data($template);//设置推送消息类型
    //    $message->set_PushNetWorkType(1);//设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送

        $target = new IGtTarget();
        $target->set_appId(APPID);
        $target->set_clientId(CID);
        $batch->add($message, $target);
        try {

            $rep = $batch->submit();
            var_dump($rep);
            echo("<br><br>");
        }catch(Exception $e){
            $rep=$batch->retry();
            var_dump($rep);
            echo ("<br><br>");
        }
    }

    //多推接口案例
    public function pushMessageToList($cids, $msg, $para = '')
    {
        putenv("gexin_pushList_needDetails=true");
        putenv("gexin_pushList_needAsync=true");

        //消息模版：
        // 1.TransmissionTemplate:透传功能模板
        // 2.LinkTemplate:通知打开链接功能模板
        // 3.NotificationTemplate：通知透传功能模板
        // 4.NotyPopLoadTemplate：通知弹框下载功能模板


        //$template = IGtNotyPopLoadTemplateDemo();
        //$template = IGtLinkTemplateDemo();
        //$template = IGtNotificationTemplateDemo();
        $template = $this->IGtTransmissionTemplateDemo($msg, $para);
        //个推信息体
        $message = new IGtListMessage();

        $message->set_isOffline(true);//是否离线
        $message->set_offlineExpireTime(3600 * 12 * 1000);//离线时间
        $message->set_data($template);//设置推送消息类型
    //    $message->set_PushNetWorkType(1); //设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送
    //    $contentId = $igt->getContentId($message);
        $contentId = $igt->getContentId($message);   //根据TaskId设置组名，支持下划线，中文，英文，数字

        foreach ($cids as $cid) {
            //接收方1
            $target = new IGtTarget();
            $target->set_appId(C('PUSH_APPID'));
            $target->set_clientId($cid);
        //    $target->set_alias(Alias);

            $targetList[] = $target;
        }

        $rep = $igt->pushMessageToList($contentId, $targetList);

        return $rep;
    }

    //群推接口案例
    public function pushMessageToApp($msg, $para = ''){
        //消息模版：
        // 1.TransmissionTemplate:透传功能模板
        // 2.LinkTemplate:通知打开链接功能模板
        // 3.NotificationTemplate：通知透传功能模板
        // 4.NotyPopLoadTemplate：通知弹框下载功能模板
        
        //$template = $this->IGtNotyPopLoadTemplateDemo();
        // $template = $this->IGtLinkTemplateDemo();
        // $template = $this->IGtNotificationTemplateDemo('测试通知透传消息');
        $template = $this->IGtTransmissionTemplateDemo($msg, $para);
        
        //个推信息体
        //基于应用消息体
        $message = new IGtAppMessage();

        $message->set_isOffline(true);
        $message->set_offlineExpireTime(3600*12*1000);//离线时间单位为毫秒，例，两个小时离线为3600*1000*2
        $message->set_data($template);
    //  $message->set_PushNetWorkType(1);   //设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送
    //    $message->set_speed(50);          //控速推送，设置每秒消息的下发量
     
        $message->set_appIdList(array(C('PUSH_APPID')));
        //$message->set_phoneTypeList(array('ANDROID'));
        //$message->set_provinceList(array('浙江','北京','河南'));
    //  $message->set_tagList(array('中文'));

        $rep = $this->igt->pushMessageToApp($message);//根据TaskId设置组名，支持下划线，中文，英文，数字
        return $rep;
    }

    //群推接口案例
    public function pushNotificationToApp(){
        //消息模版：
        // 1.TransmissionTemplate:透传功能模板
        // 2.LinkTemplate:通知打开链接功能模板
        // 3.NotificationTemplate：通知透传功能模板
        // 4.NotyPopLoadTemplate：通知弹框下载功能模板
        
        //$template = $this->IGtNotyPopLoadTemplateDemo();
        // $template = $this->IGtLinkTemplateDemo();
        $template = $this->IGtNotificationTemplateDemo('测试通知透传消息');
        // $template = $this->IGtTransmissionTemplateDemo('测试透传消息');
        
        //个推信息体
        //基于应用消息体
        $message = new IGtAppMessage();

        $message->set_isOffline(true);
        $message->set_offlineExpireTime(3600*12*1000);//离线时间单位为毫秒，例，两个小时离线为3600*1000*2
        $message->set_data($template);
    //  $message->set_PushNetWorkType(1);   //设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送
    //    $message->set_speed(50);          //控速推送，设置每秒消息的下发量
     
        $message->set_appIdList(array(C('PUSH_APPID')));
        //$message->set_phoneTypeList(array('ANDROID'));
        //$message->set_provinceList(array('浙江','北京','河南'));
    //  $message->set_tagList(array('中文'));

        $rep = $this->igt->pushMessageToApp($message);//根据TaskId设置组名，支持下划线，中文，英文，数字
    }

            //所有推送接口均支持四个消息模板，依次为通知弹框下载模板，通知链接模板，通知透传模板，透传模板
        //注：IOS离线推送需通过APN进行转发，需填写pushInfo字段，目前仅不支持通知弹框下载功能

    function IGtNotyPopLoadTemplateDemo(){
            $template =  new IGtNotyPopLoadTemplate();

            $template ->set_appId(APPID);//应用appid
            $template ->set_appkey(APPKEY);//应用appkey
            //通知栏
            $template ->set_notyTitle("个推");//通知栏标题
            $template ->set_notyContent("个推最新版点击下载");//通知栏内容
            $template ->set_notyIcon("");//通知栏logo
            $template ->set_isBelled(true);//是否响铃
            $template ->set_isVibrationed(true);//是否震动
            $template ->set_isCleared(true);//通知栏是否可清除
            //弹框
            $template ->set_popTitle("弹框标题");//弹框标题
            $template ->set_popContent("弹框内容");//弹框内容
            $template ->set_popImage("");//弹框图片
            $template ->set_popButton1("下载");//左键
            $template ->set_popButton2("取消");//右键
            //下载
            $template ->set_loadIcon("");//弹框图片
            $template ->set_loadTitle("地震速报下载");
            $template ->set_loadUrl("http://dizhensubao.igexin.com/dl/com.ceic.apk");
            $template ->set_isAutoInstall(false);
            $template ->set_isActived(true);
            //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
            $template->set_pushInfo("123", 13, "你好1234", "com.gexin.ios.silence", "1235", "13", "14", "15");

            return $template;
    }

    function IGtLinkTemplateDemo(){
            $template =  new IGtLinkTemplate();
            $template ->set_appId(APPID);//应用appid
            $template ->set_appkey(APPKEY);//应用appkey
            $template ->set_title("个推测试");//通知栏标题
            $template ->set_text("你好1234");//通知栏内容
            $template ->set_logo("");//通知栏logo
            $template ->set_isRing(true);//是否响铃
            $template ->set_isVibrate(true);//是否震动
            $template ->set_isClearable(true);//通知栏是否可清除
            $template ->set_url("http://www.igetui.com/");//打开连接地址
            //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
        //iOS推送需要设置的pushInfo字段
           $apn = new IGtAPNPayload();
           $apn->alertMsg = "alertMsg";
           $apn->badge = 11;
           $apn->actionLocKey = "启动";
       //        $apn->category = "ACTIONABLE";
       //        $apn->contentAvailable = 1;
           $apn->locKey = "通知栏内容";
           $apn->title = "通知栏标题";
           $apn->titleLocArgs = array("titleLocArgs");
           $apn->titleLocKey = "通知栏标题";
           $apn->body = "body";
           $apn->customMsg = array("payload"=>"payload");
           $apn->launchImage = "launchImage";
           $apn->locArgs = array("locArgs");

           $apn->sound=("test1.wav");;
           $template->set_apnInfo($apn);
           $template->set_pushInfo("123", 13, "你好1234", "com.gexin.ios.silence", "1235", "13", "14", "15");
        return $template;
    }

    function IGtNotificationTemplateDemo($msg, $para = ''){
            $template =  new IGtNotificationTemplate();
            $template->set_appId(APPID);//应用appid
            $template->set_appkey(APPKEY);//应用appkey
            $template->set_transmissionType(1);//透传消息类型
            $template->set_transmissionContent($para);//透传内容
            $template->set_title("捉影");//通知栏标题
            $template->set_text($msg);//通知栏内容
            // $template->set_logo("http://wwww.igetui.com/logo.png");//通知栏logo
            $template->set_isRing(true);//是否响铃
            $template->set_isVibrate(true);//是否震动
            $template->set_isClearable(true);//通知栏是否可清除
            //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
        //iOS推送需要设置的pushInfo字段
            $apn = new IGtAPNPayload();
            $apn->alertMsg = $msg;
            $apn->badge = 11;
            $apn->actionLocKey = "启动";
        //        $apn->category = "ACTIONABLE";
        //        $apn->contentAvailable = 1;
            $apn->locKey = "通知栏内容";
            $apn->title = "捉影";
            $apn->titleLocArgs = array("titleLocArgs");
            $apn->titleLocKey = "通知栏标题";
            $apn->body = $msg;
            // $apn->customMsg = array("payload"=>"payload");
            $apn->launchImage = "launchImage";
            $apn->locArgs = array("locArgs");
     
            $apn->sound=("test1.wav");;
            $template->set_apnInfo($apn);
            // $template->set_pushInfo("123", 13, "你好1234", "com.gexin.ios.silence", "1235", "13", "14", "15");
            return $template;
    }

    function IGtTransmissionTemplateDemo($msg, $para = ''){
            $template = new IGtTransmissionTemplate();
            $template->set_appId(APPID);//应用appid
            $template->set_appkey(APPKEY);//应用appkey
            $template->set_transmissionType(2);//透传消息类型
            $template->set_transmissionContent($para);//透传内容
            //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
            // $template =  new IGtNotificationTemplate();
            // $template->set_appId(APPID);//应用appid
            // $template->set_appkey(APPKEY);//应用appkey
            // $template->set_transmissionType(1);//透传消息类型
            // $template->set_transmissionContent($para);//透传内容
            // $template->set_title("捉影");//通知栏标题
            // $template->set_text($msg);//通知栏内容
            // // $template->set_logo("http://wwww.igetui.com/logo.png");//通知栏logo
            // $template->set_isRing(true);//是否响铃
            // $template->set_isVibrate(true);//是否震动
            // $template->set_isClearable(true);//通知栏是否可清除
        //APN简单推送
            $apn = new IGtAPNPayload();
    //         $alertmsg=new SimpleAlertMsg();
    //         $alertmsg->alertMsg=$msg;
    //         $apn->alertMsg=$alertmsg;
    // //         $apn->badge=2;
    // //         $apn->sound="";
    //         // $apn->add_customMsg("payload","payload");
    //         $apn->contentAvailable=2;
    //         $apn->category="ACTIONABLE";
    //         $template->set_apnInfo($apn);

        //APN高级推送
            $apn = new IGtAPNPayload();
            if ($msg) {

                $alertmsg=new DictionaryAlertMsg();
                $alertmsg->body = $msg;
                $alertmsg->actionLocKey="ActionLockey";
                $alertmsg->locKey="LocKey";
                $alertmsg->locArgs=array("locargs");
                $alertmsg->launchImage="launchimage";
        //        IOS8.2 支持
                $alertmsg->title="捉影";
                $alertmsg->titleLocKey="TitleLocKey";
                $alertmsg->titleLocArgs=array("TitleLocArg");

                // $alertmsg=new SimpleAlertMsg();
                // $alertmsg->alertMsg=$msg;

                $apn->alertMsg=$alertmsg;
                $apn->sound="";
                $apn->contentAvailable=1;
                $apn->badge=0;
            }
            
            $apn->add_customMsg("payload","$para");
            $apn->category="ACTIONABLE";
            $template->set_apnInfo($apn);

        //PushApn老方式传参
    //    $template = new IGtAPNTemplate();
    //          $template->set_pushInfo("", 10, "", "com.gexin.ios.silence", "", "", "", "");

        return $template;
    }

}