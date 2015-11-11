<?php
/**
 * Author: NanQi
 * Date: 20150605 18:29
 */
namespace Weixin\Controller;
use Think\Controller;
use Think\Model;

class PartyController extends Controller{

    public function index(){
        //使用jsapi接口
        $jsApi = D('Jsapi', 'Pay');

        //=========步骤1：网页授权获取用户openid============
        //通过code获得openid
        if (!isset($_GET['code'])) {
            //触发微信返回code码
            $url = $jsApi->createOauthUrlForCode(C('WX_JS_API_CALL_URL'));
            header("Location: $url");
            exit;
        }
        else {
            //获取code码，以获取openid
            $code = $_GET['code'];
            $jsApi->setCode($code);
            $openid = $jsApi->getOpenId();
        }

        $nid = 6;

        $party = D('Party')->cache("party_".$nid, 60*60*24)
            ->relation(true)
            ->where('nid = %d', $nid)
            ->find();

        $this->assign('party', $party);
        $this->assign('openid', $openid);
        $this->display();
    }

    public function getPayParameters($openid){

        M()->validation(array(
            array('nickname', 'require', '姓名不能为空', Model::MUST_VALIDATE, 'regex'),
            array('phonenumber', 'require', '手机号不能为空', Model::MUST_VALIDATE, 'regex'),
            array('nickname', '2,10', '姓名长度不正确', Model::MUST_VALIDATE, 'length'),
            array('phonenumber', 'phonenumber', '手机号格式不正确', Model::MUST_VALIDATE, 'regex'),
        ));

        $nickname = I('nickname');
        $phonenumber = I('phonenumber');

        $jsApi = D('Jsapi', 'Pay');
        //=========步骤2：使用统一支付接口，获取prepay_id============
        //使用统一支付接口
        $unifiedOrder = D('Unified', 'Pay');

        $nid = 6;
        $quantity = 1;
        $party = D('Party')->cache("party_".$nid, 60*60*24)
            ->relation(true)
            ->where('nid = %d', $nid)
            ->find();

        $cntremain = (int)$party['cntremain'];
        if ($cntremain < $quantity) {
            $this->retError(202, '没有足够的活动名额');
        }

        sae_log('123');

        //设置统一支付接口参数
        //设置必填参数
        //appid已填,商户无需重复填写
        //mch_id已填,商户无需重复填写
        //noncestr已填,商户无需重复填写
        //spbill_create_ip已填,商户无需重复填写
        //sign已填,商户无需重复填写

//        A('App/Order')

        $unifiedOrder->setParameter("openid","$openid");//用户openid
        $unifiedOrder->setParameter("body",$party['title']);//商品描述
        //自定义订单号，此处仅作举例
        $unifiedOrder->setParameter("out_trade_no", buildOrderNo());//商户订单号 			$unifiedOrder->setParameter("product_id","$product_id");//商品ID

        $money = number_format($party['price'], 2, '.', '');
        $money = floatval($money) * 100 * $quantity;


        $unifiedOrder->setParameter("total_fee",$money);//总金额
        $unifiedOrder->setParameter("notify_url",C('WX_NOTIFY_URL'));//通知地址
        $unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
        //非必填参数，商户可根据实际情况选填
        //$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号
        //$unifiedOrder->setParameter("device_info","XXXX");//设备号

        $attach = array(
            'nid' => $nid,
            'nickname' => $nickname,
            'phonenumber' => $phonenumber,
            'quantity' => $quantity,
            'is_carry' => I('is_carry'),
            'pet_type' => I('pet_type'),
            'pet_sex' => I('pet_sex'),
        );

        $attach = json_encode($attach);

        $currentTime = time();

        $unifiedOrder->setParameter("attach",$attach);//附加数据
        $unifiedOrder->setParameter("time_start", date('YmdHis', $currentTime));//交易起始时间
        $unifiedOrder->setParameter("time_expire",date('YmdHis', $currentTime + 60 * 5));//交易结束时间
        //$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记
        //$unifiedOrder->setParameter("openid","XXXX");//用户标识

        $prepay_id = $unifiedOrder->getPrepayId();
        //=========步骤3：使用jsapi调起支付============
        $jsApi->setPrepayId($prepay_id);

        $jsApiParameters = $jsApi->getParameters();
        $this->retSuccess($jsApiParameters);
    }

    public function notify(){
        /*
         *
        <xml>
        <appid><![CDATA[wx4e81aacd38a27424]]></appid>
        <bank_type><![CDATA[CFT]]></bank_type>
        <cash_fee><![CDATA[1]]></cash_fee>
        <fee_type><![CDATA[CNY]]></fee_type>
        <is_subscribe><![CDATA[Y]]></is_subscribe>
        <mch_id><![CDATA[1247136201]]></mch_id>
        <nonce_str><![CDATA[7haw88jm20b078lxufc3vhl1j5i5kiwm]]></nonce_str>
        <openid><![CDATA[oVe75s2v_gh8E_ed5-MS57NVVXhg]]></openid>
        <out_trade_no><![CDATA[wx4e81aacd38a274241434089697]]></out_trade_no>
        <result_code><![CDATA[SUCCESS]]></result_code>
        <return_code><![CDATA[SUCCESS]]></return_code>
        <sign><![CDATA[8791ACDF25B56957CA0AB4F055304BBA]]></sign>
        <time_end><![CDATA[20150612141518]]></time_end>
        <total_fee>1</total_fee>
        <trade_type><![CDATA[JSAPI]]></trade_type>
        <transaction_id><![CDATA[1000040089201506120240872710]]></transaction_id>
        </xml>
         */

        //使用通用通知接口
        $notify = D('Server', 'Pay');

        //存储微信的回调
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        sae_log('notify', $xml);
        $notify->saveData($xml);

        //验证签名，并回应微信。
        //对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
        //微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
        //尽可能提高通知的成功率，但微信不保证通知最终能成功。
        if($notify->checkSign() == FALSE){
            $notify->setReturnParameter("return_code","FAIL");//返回状态码
            $notify->setReturnParameter("return_msg","签名失败");//返回信息
        }else{
            $notify->setReturnParameter("return_code","SUCCESS");//设置返回码
        }

        if($notify->checkSign() == TRUE)
        {
            if ($notify->data["return_code"] == "FAIL") {
                //通信出错
            }
            elseif($notify->data["result_code"] == "FAIL"){
                //业务出错
            }
            else{
                //支付成功
                $data = $notify->getData();

                $order = M('OrderWeixin');

                $attach = $data['attach'];
                $attach = json_decode($attach);

                foreach ($attach as $key => $val) {
                    $data[$key] = $val;
                }

                $Orderrecord = D('Orderrecord');
                $vertifyCode = $Orderrecord->buildVertifyCode();

                $dataRecord = array(
                    'orderno' => $data['out_trade_no'],
                    'paytime' => tounix($data['time_end']),
                    'paymethod' => '__weixin__',
                    'chargeid' => $data['transaction_id'],
                    'vertifycode' => $vertifyCode,
                );

                M()->startTrans();

                $flg = $order->add($data);
                if (!$flg) {
                    sae_log('OrderWeixin', $data);
                    M()->rollback();
                    $returnXml = $notify->returnXml();
                    echo $returnXml;
                    exit;
                }

                $flg = $Orderrecord->add($dataRecord);
                if (!$flg) {
                    sae_log('Orderrecord', $dataRecord);
                    M()->rollback();
                    $returnXml = $notify->returnXml();
                    echo $returnXml;
                    exit;
                }

                $party = D('Party')->cache("party_".$data['nid'], 60*60*24)
                    ->relation(true)
                    ->where('nid = %d', $data['nid'])
                    ->find();

                D('Party')->where('id = %d', $party['id'])->setDec('cntremain', $data['quantity']);

                S("party_".$data['nid'], null);

                M()->commit();



                $time = date('Y-m-d H:i', $party['starttime']);

                $ret = D('Sms', 'Logic')
                    ->sendOrderVerify($data['phonenumber'], $party['title'], $vertifyCode, $time);
                if ($ret != null) {
                    $returnXml = $notify->returnXml();
                    echo $returnXml;
                    exit;
                }
            }

            //商户自行增加处理流程,
            //例如：更新订单状态
            //例如：数据库操作
            //例如：推送支付完成信息
        }

        $returnXml = $notify->returnXml();
        echo $returnXml;
    }
}