<?php
/**
 * Author: NanQi
 * Date: 20150611 16:18
 */
namespace Weixin\Pay;
use Think\Model;

class NativecallPay extends ServerPay {
    /**
     * 生成接口参数xml
     */
    function createXml()
    {
        if($this->returnParameters["return_code"] == "SUCCESS"){
            $this->returnParameters["appid"] = C('WX_APPID');//公众账号ID
            $this->returnParameters["mch_id"] = C('WX_MCHID');//商户号
            $this->returnParameters["nonce_str"] = $this->createNoncestr();//随机字符串
            $this->returnParameters["sign"] = $this->getSign($this->returnParameters);//签名
        }
        return $this->arrayToXml($this->returnParameters);
    }

    /**
     * 获取product_id
     */
    function getProductId()
    {
        $product_id = $this->data["product_id"];
        return $product_id;
    }

}