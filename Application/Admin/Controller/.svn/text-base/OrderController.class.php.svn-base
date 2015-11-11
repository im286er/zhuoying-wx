<?php
/**
 * Author: NanQi
 * Date: 20150513 13:31
 */
namespace Admin\Controller;
class OrderController extends AdminController {

    public function index() {
        $orderNo = I('orderno');

        $map['orderno']   = array('like', '%'.$orderNo.'%');

        $order = D('Order')->relation(true);

        $list   = $this->lists($order, $map, 'ordertime desc');
        int_to_string($list, array(
            'paystatus' => array(
                -2 => '已过期',
                -1 => '取消',
                0  => '待支付',
                1  => '已支付',
                2  => '退款',
            ),
        ));

        $this->assign('_list', $list);
        $this->display();
    }
}