<?php
/**
 * Author: NanQi
 * Date: 20150611 15:37
 */
namespace Weixin\Pay;
use Think\Model;

class NativelinkPay extends BasePay {

    public $parameters;

    /**
     * 设置参数
     */
    function setParameter($parameter, $parameterValue)
    {
        $parameter = $this->trimString($parameter);
        $parameterValue = $this->trimString($parameterValue);
        $this->parameters[$parameter] = $parameterValue;
    }

    /**
     * 生成Native支付链接二维码
     */
    function createLink()
    {
        if($this->parameters["product_id"] == null)
        {
            E("缺少Native支付二维码链接必填参数product_id！"."<br>");
        }
        $this->parameters["appid"] = C('WX_APPID');//公众账号ID
        $this->parameters["mch_id"] = C('WX_MCHID');//商户号
        $time_stamp = time();
        $this->parameters["time_stamp"] = "$time_stamp";//时间戳
        $this->parameters["nonce_str"] = $this->createNoncestr();//随机字符串
        $this->parameters["sign"] = $this->getSign($this->parameters);//签名
        $bizString = $this->formatBizQueryParaMap($this->parameters, false);
        $this->url = "weixin://wxpay/bizpayurl?".$bizString;
    }

    /**
     * 返回链接
     */
    function getUrl()
    {
        $this->createLink();
        return $this->url;
    }
}