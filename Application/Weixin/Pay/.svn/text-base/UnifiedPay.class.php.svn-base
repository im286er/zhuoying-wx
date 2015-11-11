<?php
/**
 * Author: NanQi
 * Date: 20150611 16:21
 */
namespace Weixin\Pay;
use Think\Model;

class UnifiedPay extends ClientPay {
    function _initialize()
    {
        //设置接口链接
        $this->url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        //设置curl超时时间
        $this->curl_timeout = C('WX_CURL_TIMEOUT');
    }

    /**
     * 生成接口参数xml
     */
    function createXml()
    {
        //检测必填参数
        if($this->parameters["out_trade_no"] == null)
        {
            E("缺少统一支付接口必填参数out_trade_no！"."<br>");
        }elseif($this->parameters["body"] == null){
            E("缺少统一支付接口必填参数body！"."<br>");
        }elseif ($this->parameters["total_fee"] == null ) {
            E("缺少统一支付接口必填参数total_fee！"."<br>");
        }elseif ($this->parameters["notify_url"] == null) {
            E("缺少统一支付接口必填参数notify_url！"."<br>");
        }elseif ($this->parameters["trade_type"] == null) {
            E("缺少统一支付接口必填参数trade_type！"."<br>");
        }elseif ($this->parameters["trade_type"] == "JSAPI" &&
            $this->parameters["openid"] == NULL){
            E("统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！"."<br>");
        }
        $this->parameters["appid"] = C('WX_APPID');//公众账号ID
        $this->parameters["mch_id"] = C('WX_MCHID');//商户号
        $this->parameters["spbill_create_ip"] = $_SERVER['REMOTE_ADDR'];//终端ip
        $this->parameters["nonce_str"] = $this->createNoncestr();//随机字符串
        $this->parameters["sign"] = $this->getSign($this->parameters);//签名
        return  $this->arrayToXml($this->parameters);
    }

    /**
     * 获取prepay_id
     */
    function getPrepayId()
    {
        $this->getResult();

        if ($this->result['return_code'] != 'SUCCESS') {
            sae_log('getPrepayId', $this->result['return_msg']);
        }

        $prepay_id = $this->result["prepay_id"];
        return $prepay_id;
    }
}