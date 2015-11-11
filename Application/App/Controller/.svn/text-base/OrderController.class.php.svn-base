<?php
/**
 * Author: NanQi
 * Date: 20150429 10:09
 */
namespace App\Controller;
use Think\Controller;
use Think\Model;
class OrderController extends Controller {

    protected $userID = '';

    //必须登录才能调用Movie控制器方法
    function _initialize() {
        $this->userID = authUserID();
    }

    /**
     * @author : NanQi
     * @date   : 20150428 10:50
     *
     * @desc     提交订单
     * @param    Number id 活动ID
     * @param    Number count 参与人数
     * @return   bool
     */
    public function submit(){
        $order = D('Order');

        $order->validation(array(
            array('id', 'require', '活动ID不能为空', Model::MUST_VALIDATE, 'regex'),
            array('id', 'number', '活动ID格式不正确', Model::MUST_VALIDATE, 'regex'),
            array('count', 'require', '参与人数不能为空', Model::MUST_VALIDATE, 'regex'),
            array('count', 'number', '参与人数格式不正确', Model::MUST_VALIDATE, 'regex'),
        ));

        $expireOrderCacheKey = "userid_order_".$this->userID;
        $orderNo = S($expireOrderCacheKey);
        if (!empty($orderNo)) {
            $this->retError(201, '你有一个未付款的订单');
        }

        $party = D('Party');

        $cntremain = $party->getRemainCount(I('id')) - I('count');
        if ($cntremain < 0) {
            $this->retError(202, '没有足够的活动名额');
        }

        $cnt = M('PartyUser')->where('pid = %d and uid = %d', I('id'), $this->userID)->count();
        if ($cnt) {
           $this->retError(203, '不能重复报名');
        }

        $party->setRemainCount(I('id'), $cntremain);

        $orderNo = $order->createOrder($this->userID, I('id'), I('count'));

        S($expireOrderCacheKey, $orderNo, 60 * 15);//15分中订单有效期

        $fullInfo = $party->getFullInfo(I('id'));
        $nid = $fullInfo['nid'];

        S("party_".$nid, null);//提交订单清空活动缓存

        S('schedule_order', true, 60 * 10);

        $this->retSuccess($orderNo);
    }

    /**
     * @author : NanQi
     * @date   : 20150429 10:10
     *
     * @desc     支付
     * @param    String no 订单编号
     * @param    float money 支付金额
     * @param    String type 支付方式
     * @return   charge
     */
    public function pay(){
        M()->validation(array(
            array('no', 'require', '订单编号不能为空', Model::MUST_VALIDATE, 'regex'),
            array('money', 'require', '支付金额不能为空', Model::MUST_VALIDATE, 'regex'),
            array('money', 'currency', '支付金额格式不正确', Model::MUST_VALIDATE, 'regex'),
            array('type', 'require', '支付方式不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $order = D('Order');
        $orderDetail = S('orderdetail_'.I('no'));
        if (empty($orderDetail)) {
            $orderDetail = $order->find(I('no'));
        }

        if (empty($orderDetail)) {
            $this->retError(201, '没有对应的订单编号');
        }

        if ($orderDetail['paystatus'] == -2) {
            $this->retError(202, '该订单已过期');
        }
        elseif ($orderDetail['paystatus'] != 0) {
            $this->retError(203, '只有待支付状态的订单可以付款');
        }

        $money = I('money');

        $money = number_format($money, 2, '.', '');
        $money = floatval($money) * 100;

        //$this->retSuccess($money);

        $charge = D('Pay', 'Logic')->pay(I('no'), $money, I('type'));

        $this->retSuccess(json_decode($charge));
    }

    /**
     * @author : NanQi
     * @date   : 20150429 10:10
     *
     * @desc     订单列表
     * @return   array order
     */
    public function orderList(){
        $countnum = -1;
        $data = null;

        $order = D('Order');

        if (I('page') != null && I('limit') != null) {

            $countnum = $order->where('uid = %d', $this->userID)->count();

            $orderBy = I('order');
            $orderBy = empty($orderBy) ? 'ordertime desc' : $orderBy;

            $data = $order
                ->relation(true)
                ->where("uid = %d", $this->userID)
                ->order($orderBy)
                ->limit(I('limit'))
                ->page(I('page'))
                ->select();
        }
        else {

            $data = $order->relation(true)->where("uid = %d", $this->userID)->select();
        }

        $this->retPager($countnum, $data);
    }

    /**
     * @author : NanQi
     * @date   : 20150508 18:19
     *
     * @desc     已购票用户列表
     * @param    int id 活动ID
     * @return   用户列表
     */
    public function userList(){
        M()->validation(array(
            array('id', 'require', '活动ID不能为空', Model::MUST_VALIDATE, 'regex'),
            array('id', 'number', '活动ID格式不正确', Model::MUST_VALIDATE, 'regex'),
        ));

        $userList = M()->query("select t2.uid,nickname,sex,avatar,job,signature,birthday from t_party_user t1, t_privacy t2 where t1.uid = t2.uid and t1.pid = %d",
            I('id'));

        $this->retSuccess($userList);
    }

    /**
     * @author : NanQi
     * @date   : 20150429 10:10
     *
     * @desc     订单详情
     * @param    String id 订单编号
     * @return   order
     */
    public function detail(){
        M()->validation(array(
            array('id', 'require', '订单编号不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $order = D('Order')
            //->cache('orderdetail_'.I('id'))
            ->relation(true)
            ->find(I('id'));

        if (empty($order)) {
            $this->retError(201, '没有对应的订单编号');
        }

        $fullInfo = D('Party')->getFullInfo($order['pid']);
        $nid = $fullInfo['nid'];

        $order['nid'] = $nid;
        $order['url'] = C("HTTP_HOST").'/admin.php?s=/Operator/vertify/code/'.$order['vertifycode'].'.html';

        $this->retSuccess($order);

    }
    /**
     * @author : NanQi
     * @date   : 20150429 10:10
     *
     * @desc     取消订单
     * @param    String id 订单编号
     * @return   bool
     */
    public function cancel(){
        M()->validation(array(
            array('id', 'require', '订单编号不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $order = D('Order');

        $orderDetail = S('orderdetail_'.I('id'));
        if (empty($orderDetail)) {
            $orderDetail = $order->find(I('id'));
        }

        if (empty($orderDetail)) {
            $this->retError(201, '没有对应的订单编号');
        }

        if ($orderDetail['paystatus'] != 0) {
            $this->retError(202, '只有待支付状态的订单可以取消');
        }

        $party = D('Party');

        $order->modifyPayStatus(I('id'), -1);//取消订单
        $party->where('id = %d', $orderDetail['pid'])->setInc('cntremain', $orderDetail['quantity']);

        $fullInfo = $party->getFullInfo($orderDetail['pid']);
        $nid = $fullInfo['nid'];

        S("party_".$nid, null);//提交订单清空活动缓存
        S('orderdetail_'.I('id'), null);
        S("userid_order_".$this->userID, null);//清空用户订单缓存

        $this->retSuccess();
    }

    /**
     * @author : Comer
     * @date   : 2015/4/20 14:39
     *
     * @desc     接收支付和退款的异步通知
     * @param    Notify  URL类似:http://mylineserver.sinaapp.com/app/Pay/getMessage
     * @return    null
     */
    public function getMessage(){

        $Orderrecord = D('Orderrecord');
        $vertifyCode = $Orderrecord->buildVertifyCode();

        $data = array(
            'orderno' => I('order_no'),
            'paytime' => I('created'),
            'paymethod' => I('channel'),
            'chargeid' => I('id'),
            'vertifycode' => $vertifyCode,
        );

        M()->startTrans();

        if ($Orderrecord->create($data)) {
            $id = $Orderrecord->add();
            if($id){

                $party = M()->query("select t2.id, t2.title, t2.starttime from t_order t1, t_party t2 where t1.pid = t2.id and t1.orderno = %s", I('order_no'));
                if (empty($party)) {
                    $this->retError();
                }
                else {
                    $party = $party[0];
                }

                M('PartyUser')->add(array(
                    'pid' => $party['id'],
                    'uid' => $this->userID,
                ));

                $time = date('Y-m-d H:i', $party['starttime']);

                $user = M('User')->field('id,phonenumber')->find($this->userID);

                $ret = D('Sms', 'Logic')
                    ->sendOrderVerify($user['phonenumber'], $party['title'], $vertifyCode, $time);
                if ($ret != null) {
                    $this->retError(701, $ret);
                }

                D('Order')->modifyPayStatus(I('order_no'), 1);//已支付

                S('orderdetail_'.I('order_no'), null);
                S("userid_order_".$this->userID, null);//清空用户订单缓存

                M()->commit();

                \Think\Log::record('提交订单用户在JPush中归组:'.$party['id']);
                //提交订单后将用户在JPush中归组
                $regIDs = D('Registration')->where('uid = %d', $this->userID)->field('regid')->select();

                foreach($regIDs as $regModel) {
                    $regid = $regModel['regid'];

                    \Think\Log::record('注册ID:'.$regid);
                    D('JPush', 'Logic')->AddTags($regid, array(C('PUSH_PARTY_PREFIX').$party['id']));
                }

                $this->retSuccess();
            } else {
                //
            }
        }

        M()->rollback();
        $this->retError();

        $charge = json_decode(file_get_contents("php://input"), true);
        if($charge['object'] == 'charge'&& $charge['paid']==true) {
            //app客户支付完成后，Ping++即时返回支付charge对象，当判断已支付完成后；自己主动给Ping++发送chargeId请求,再次确认
            //支付是否真的成功

            $data = array(
                'orderno' => $charge['order_no'],
                'paytime' => $charge['created'],
                'paymethod' => $charge['channel'],
                'chargeid' => $charge['id'],
            );

            if (D('Orderrecord')->create($data)) {
                $id = D('Orderrecord')->add();
                if($id){
                    $this->retSuccess();
                } else {
                    //
                }
            }

            $this->retError();

            if($this->getPaystateById($charge['id'])){
                //echo 'paySuccess';

                //Todo:1、跳转至支付成功页面  2、JPUSH推送支付成功页面链接  3、保存支付成功订单记录  4、给ping++返回success

                //3、在订单记录表保存订单记录，并更改订单表的支付状态为1【已支付】、及存储chargeId
            }else{
                //TODO:发起争议订单
            }
        } else if($charge['object'] == 'refund'&& $charge['succeed']==true) {
            echo 'refundSuccess';
        } else if($charge['object'] == 'charge'&& $charge['paid']==false) {
            echo 'payFail';
        } else if($charge['object'] == 'refund'&& $charge['succeed']==false) {
            echo 'refundFail';
        }
    }

    /**
     * @author : Comer
     * @date   : 2015/4/20 16:20
     *
     * @desc     通过charge的ID查询支付状态
     * @param
     * @return   null
     */
    public function getPaystateById($chargeId){
        //引入ping++类库文件
        require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/pingpp-php-master/init.php');
        \Pingpp\Pingpp::setApiKey(C('MYLINE_KEY'));
        $Charge = \Pingpp\Charge::retrieve($chargeId);
        $input_data = json_decode($Charge, true);
        if($input_data['object'] == 'charge'&& $input_data['paid']==true) {
            return true;
        }else if($input_data['object'] == 'charge'&& $input_data['paid']==false){
            return false;
        }
    }


    /**
     * @author : NanQi
     * @date   : 20150429 10:10
     *
     * @desc     退款
     * @return   bool
     */
    public function refund(){
        M()->validation(array(
            array('id', 'require', '订单编号不能为空', Model::MUST_VALIDATE, 'regex'),
        ));
    }
}