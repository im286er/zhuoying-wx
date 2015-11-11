<?php
/**
 * Author: NanQi
 * Date: 20150511 11:37
 */
namespace App\Logic;

use Think\Log;

class SmsLogic extends BaseLogic {
    private $AccountSid;
    private $AccountToken;
    private $AppId;
    private $ServerIP;
    private $ServerPort;
    private $SoftVersion;
    private $Batch;  //时间戳
    private $BodyType = "json";//包体格式，可填值：json 、xml
    function __construct() {
        $this->AccountSid = C('SMS_ACCOUNT_SID');
        $this->AccountToken = C('SMS_ACCOUNT_TOKEN');
        $this->AppId = C('SMS_APP_ID');

        $this->ServerIP = 'app.cloopen.com';
        $this->ServerPort = 8883;
        $this->SoftVersion = '2013-12-26';
    }

    /**
     * @author : NanQi
     * @date   : 20150512 09:50
     *
     * @desc     发送手机验证码
     * @param    String $phoneNumber 手机号
     */
    public function sendSMSVerify($phoneNumber, $code){
        $this->sendTemplateSMS($phoneNumber, array($code, 5), 19347);
    }

    /**
     * @author : NanQi
     * @date   : 20150512 09:50
     *
     * @desc     发送订单验证码
     * @param    String $phoneNumber 手机号
     */
    public function sendOrderVerify($phoneNumber, $title, $code, $time){
        $this->sendTemplateSMS($phoneNumber, array($title, $code, $time), 19348);
    }

    /**
     * 发送模板短信
     * @param String $to 短信接收彿手机号码集合,用英文逗号分开
     * @param array $datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
     * @param int $tempId 模板Id,测试应用和未上线应用使用测试模板请填写1，正式应用上线后填写已申请审核通过的模板ID
     * @return 内容数据|mixed
     */
    function sendTemplateSMS($to,$datas,$tempId) {
        $this->Batch = date("YmdHis");

        // 拼接请求包体
        $data="";
        for($i=0;$i<count($datas);$i++){
            $data = $data. "'".$datas[$i]."',";
        }
        $body= "{'to':'$to','templateId':'$tempId','appId':'$this->AppId','datas':[".$data."]}";

        $level = Log::INFO;

        Log::record("response body = ".$body,$level);
        // 大写的sig参数
        $sig =  strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
        // 生成请求URL
        $url="https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/SMS/TemplateSMS?sig=$sig";
        Log::record("request url = ".$url,$level);
        // 生成授权：主帐户Id + 英文冒号 + 时间戳。
        $authen = base64_encode($this->AccountSid . ":" . $this->Batch);
        // 生成包头
        $header = array("Accept:application/$this->BodyType","Content-Type:application/$this->BodyType;charset=utf-8","Authorization:$authen");
        // 发送请求
        $result = $this->curl_post($url,$body,$header);
        Log::record("response body = ".$result,$level);
        $datas=json_decode($result);

        //重新装填数据
        if($datas->statusCode==0){
            if($this->BodyType=="json"){
                $datas->TemplateSMS =$datas->templateSMS;
                unset($datas->templateSMS);
            }
        }

        return $datas;
    }

    /**
     * 发起HTTPS请求
     */
    function curl_post($url,$data,$header,$post=1) {
        //初始化curl
        $ch = curl_init();
        //参数设置
        $res= curl_setopt ($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt ($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, $post);
        if($post)
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        $result = curl_exec ($ch);
        //连接失败
        if($result == FALSE){
            $result = "{\"statusCode\":\"172001\",\"statusMsg\":\"网络错误\"}";
        }

        curl_close($ch);
        return $result;
    }
}