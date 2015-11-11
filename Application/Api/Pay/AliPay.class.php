<?php
/**
 * Author: ZYK
 * Date: 20150512 14:47
 */
namespace Api\Pay;

class AliPay extends BasePay {
    /**
     * 支付
     */
    public function payOrder($data)
    {
        // 获取支付金额 动态传进来 *
        /*$amount = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $amount = $_POST['total'];
        } else {
            $amount = $_GET['total'];
        }*/
        $total = floatval($data['total']);
        if (!$total) {
            $total = 1 / 100;
        }
        // 支付宝合作身份者ID，以2088开头的16位纯数字
        $partner = C('ALIPAY_PARAMS.partner');  // 支付宝开通快捷支付功能后可获取
        // 支付宝账号
        $seller_id = C('ALIPAY_PARAMS.seller_id');
        // 商品网址
        //$base_path = urlencode('http://demo.dcloud.net.cn/helloh5/payment/');
        // 异步通知地址
        $notify_url = C('ALIPAY_PARAMS.notify_url');
        // 订单标题 动态传进来 *
        $subject = $data['subject'];
        // 订单详情 动态传进来  *
        $body = $data['body'];
        // 订单号，示例代码使用时间值作为唯一的订单ID号
        $out_trade_no = $data['orderno'];
        $parameter = array(
            'service' => 'mobile.securitypay.pay',   // 必填，接口名称，固定值
            'partner' => $partner,                   // 必填，合作商户号
            '_input_charset' => 'UTF-8',                    // 必填，参数编码字符集
            'out_trade_no' => $out_trade_no,              // 必填，商户网站唯一订单号
            'subject' => $subject,                   // 必填，商品名称
            'payment_type' => '1',                        // 必填，支付类型
            'seller_id' => $seller_id,                 // 必填，卖家支付宝账号
            'total_fee' => $total,                     // 必填，总金额，取值范围为[0.01,100000000.00]
            'body' => $body,                      // 必填，商品详情
            'it_b_pay' => '1d',                       // 可选，未付款交易的超时时间
            'notify_url' => $notify_url              // 可选，服务器异步通知页面路径
            // 'show_url'       => $base_path                  // 可选，商品展示网站
        );

        //生成需要签名的订单
        $orderInfo = $this->createLinkstring($parameter);
        //签名
        //$this->retSuccess($orderInfo);
        $sign = $this->rsaSign($orderInfo);
        //生成订单

        $returnData= $orderInfo . '&sign="' . $sign . '"&sign_type="RSA"';

        $Order=D("order");
        $data['total'] = $data['total'] * 100;
        $Order->addOrder($data);

        return $returnData;
    }

    /**
     * @param $para
     * @return string
     * 对签名字符串转义
     */
    function createLinkstring($para)
    {
        $arg = "";
        while (list ($key, $val) = each($para)) {
            $arg .= $key . '="' . $val . '"&';
        }
        //去掉最后一个&字符
        $arg = substr($arg, 0, count($arg) - 2);
        //如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc()) {
            $arg = stripslashes($arg);
        }
        return $arg;
    }

    /**
     * @param $data
     * @return string
     * 签名生成订单信息
     */
    function rsaSign($data)
    {
        // 生成密钥时获取，直接使用pem文件的字符串 类似这些支付常量到时定义为thinkphp的常量
        $priKey = "-----BEGIN RSA PRIVATE KEY-----
MIICXgIBAAKBgQCxrMN69pvAy6IJbYZ3jI3wqfg8CsxNArOasO6A9eLaXWQ+Tr4V
lK6+wPTCbAHOeehfIx6ulyr9q98rkvd6DLj5qmnccPP9WiaKoF5MUM7nzKp/JGfV
3BMFXGxHw0fcUGFUOPmmo09NlXp54OCcEjw+NK6Vy7MacnUB8KkXbXw7+QIDAQAB
AoGBAKoY8dSIv7glu203M+LD5yeeMY6Z79lSWswf01YXESVo/40/KW/Tti/c3vY7
HMelojdikf8nrfrJTpCS1OY9w993mP4e3wrbUvgj28lx/uJXifK/1aydNTJran4u
9awIgM70RIZaUtDFe9lg/EYC2hJw5PdpjiilX8NCpmGRI4z5AkEA6cBrgStARMw7
ZSlIz5XMxLRUEke+K6wOSsDoX9ngnlaQI+HO4DlGzqRqFNw2UoNDN0lDDwM/be0N
PdJww+VDZwJBAMKV+tC8ukQ+P4C3uthulE8tHoVffSbkXACmuQpy/VfZj5k0Ovbw
zWp/qfv+XOPp43via8E1pP9rxoA4kRrJSZ8CQQDanyQtEDWw4s8eY0l9FV8KDpqe
6ki78dUAJ4Juu4lwrgdr9/MUFZ3bAT1SO0/3RltkZvn4HZKCjnaW/q6HGXM7AkBG
xwLqNhuSgwnaKfo+i0pK2mCFpswA/4MvgXVBBb5829fNpB/mfoRZPoJ39HeZNxPV
lfAX4QlZFrynGuw80AaFAkEAgjbqoAhI1NzbWR3dMVYdpCP9RJR5bOYJjO2yK5Ds
D0+xQcQGXmGr2JBHL68ZN5nyTNY33W6Ny3kfX0LquGyJDg==
-----END RSA PRIVATE KEY-----";
        $res = openssl_pkey_get_private($priKey);
        openssl_sign($data, $sign, $res);
        openssl_free_key($res);

        $sign = base64_encode($sign);
        $sign = urlencode($sign);
        return $sign;
    }
}