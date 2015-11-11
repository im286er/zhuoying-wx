<?php
/**
 * Author: NanQi
 * Date: 20150604 11:19
 */
namespace Weixin\Pay;

class RedpacketPay extends ClientPay {
    function _initialize()
    {
        //设置接口链接
        $this->url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
        //设置curl超时时间
        $this->curl_timeout = C('WX_CURL_TIMEOUT');
    }

    /**
     * 生成接口参数xml
     */
    function createXml($openid)
    {
        $this->parameters["wxappid"] = C('WX_APPID');//公众账号ID
        $this->parameters["mch_id"] = C('WX_MCHID');//商户号
        $this->parameters["nonce_str"] = $this->createNoncestr();//随机字符串
        $this->parameters["mch_billno"] = C('WX_MCHID').$this->build_billno();//随机字符串
        $this->parameters["send_name"] = '捉影APP';//红包发送者名称
        $this->parameters["re_openid"] = $openid;//接受红包的用户 
        $this->parameters["total_amount"] = '100';//付款金额
        $this->parameters["total_num"] = '1';//红包发放总人数
        $this->parameters["wishing"] = '拿走，不谢！拥抱和平，拥抱祖国。也抱抱捉影！';//红包祝福语
        $this->parameters["client_ip"] = $_SERVER['REMOTE_ADDR'];//调用接口的机器Ip地址
        $this->parameters["act_name"] = '慢连活动';//活动名称
        $this->parameters["remark"] = '发红包';//备注信息
        $this->parameters["sign"] = $this->getSign($this->parameters);//签名
        return  $this->arrayToXml($this->parameters);
    }

    /**
     * 发红包 
     */
    function send($openid)
    {
        $xml = $this->createXml($openid);
        $this->response = $this->postXmlSSLCurl($xml,$this->url,$this->curl_timeout);
        $this->result = $this->xmlToArray($this->response);
        return $this->result;
    } 

    /** 生成订单号
     * @return string
     */
    function build_billno() {
        return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 15), 1))), 0, 10);
    }
}