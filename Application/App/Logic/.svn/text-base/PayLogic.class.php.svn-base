<?php
/**
 * Author: NanQi
 * Date: 20150429 15:02
 */
namespace App\Logic;

require_once 'pingpp/init.php';
use Pingpp\Pingpp as Ping;
use Pingpp\Charge as Charge;
use Pingpp\Error\Base as PingException;
use Think\Model;

class PayLogic extends BaseLogic {

    protected $autoCheckFields = false;

    public function __construct() {
        Ping::setApiKey(C('MYLINE_KEY'));
    }

    public function pay($orderNo, $amount, $channel) {
        $extra = array();
        $ip = get_client_ip();
        switch ($channel) {
            case 'alipay_wap':
                $extra = array(
                    'success_url' => 'http://www.yourdomain.com/success',
                    'cancel_url' => 'http://www.yourdomain.com/cancel'
                );
                break;
            case 'upmp_wap':
                $extra = array(
                    'result_url' => 'http://www.yourdomain.com/result?code='
                );
                break;
            case 'bfb_wap':
                $extra = array(
                    'result_url' => 'http://www.yourdomain.com/result?code='
                );
                break;
            case 'upacp_wap':
                $extra = array(
                    'result_url' => 'http://www.yourdomain.com/result?code='
                );
                break;
            case 'wx_pub':
                $extra = array(
                    'open_id' => 'Openid'
                );
                break;
            case 'wx_pub_qr':
                $extra = array(
                    'product_id' => 'Productid'
                );
                break;
        }

        try {
            $charge = Charge::create(
                array(
                    "subject"   => "Your Subject",
                    "body"      => "Your Body",
                    "amount"    => $amount,
                    "order_no"  => $orderNo,
                    "currency"  => "cny",
                    "extra"     => $extra,
                    "channel"   => $channel,
                    "client_ip" => $ip,
                    "app"       => array("id" => C('MYLINE_ID_KEY'))
                )
            );
            return $charge;
        } catch (PingException $e) {

            $this->retError(701, $e->getMessage());
        }
    }

    public function refund($chargeId, $orderprice, $description){
        $charge = Charge::retrieve($chargeId);
        $charge->refunds->create(
            array(
                'amount' => $orderprice,
                'description' => $description,
            )
        );

        return $charge;
    }
}