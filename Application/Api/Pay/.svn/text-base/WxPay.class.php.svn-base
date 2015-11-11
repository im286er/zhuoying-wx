<?php
namespace Api\Pay;

require_once "Application/Api/Pay/WxPay.Api.php";
require_once "Application/Api/Pay/WxPay.Data.php";
require_once "Application/Api/Pay/WxPay.Notify.php";

use WxPayUnifiedOrder;
use WxPayApi;
use WxPayNotify;

class WxPay extends BasePay {

    public function payOrder($data) {

        $total = floatval($data['total']);
        $total = round($total*100); // 将元转成分
        if(empty($total)){
            $total = 1;
        }

        $Order=D("order");
        $data['total'] = $total;
        $Order->addOrder($data);

        // 商品名称
        $subject = $data['subject'];
        // 订单号，示例代码使用时间值作为唯一的订单ID号
        $out_trade_no = $data['orderno'];
        $unifiedOrder = new WxPayUnifiedOrder();
        $unifiedOrder->SetBody($subject);//商品或支付单简要描述
        $unifiedOrder->SetOut_trade_no($out_trade_no);
        $unifiedOrder->SetTotal_fee($total);
        $unifiedOrder->SetTrade_type("APP");
        $result = WxPayApi::unifiedOrder($unifiedOrder);
        if (is_array($result)) {
            return $result;
        }

        return '微信支付失败';
    }

    public function notify() {
        $notify = new WxPayNotify();
        $notify->Handle(false);
    }
}