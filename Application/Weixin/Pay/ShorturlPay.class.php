<?php
/**
 * Author: NanQi
 * Date: 20150611 15:45
 */
namespace Weixin\Pay;
use Think\Model;

class ShorturlPay extends ClientPay {

    function _initialize()
    {
        //设置接口链接
        $this->url = "https://api.mch.weixin.qq.com/tools/shorturl";
        //设置curl超时时间
        $this->curl_timeout = C('WX_CURL_TIMEOUT');
    }

    /**
     * 生成接口参数xml
     */
    function createXml()
    {
        if($this->parameters["long_url"] == null )
        {
            E("短链接转换接口中，缺少必填参数long_url！"."<br>");
        }
        $this->parameters["appid"] = C('WX_APPID');//公众账号ID
        $this->parameters["mch_id"] = C('WX_MCHID');//商户号
        $this->parameters["nonce_str"] = $this->createNoncestr();//随机字符串
        $this->parameters["sign"] = $this->getSign($this->parameters);//签名
        return  $this->arrayToXml($this->parameters);
    }

    /**
     * 获取prepay_id
     */
    function getShortUrl()
    {
        $this->getResult();
        $prepay_id = $this->result["short_url"];
        return $prepay_id;
    }
}