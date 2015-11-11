<?php
namespace Api\Controller;
use Think\Controller;
use Think\Model;

class PushController extends Controller {
    public function pushMessageToApp() {
        $data = array();

        $data['t'] = I('t');
        $data['m'] = I('msg');
        $data['d'] = I('d');
        $data['u'] = I('u');


        $rep = D('Push', 'Logic')->sendAll($data['m'], json_encode($data));
        dump($rep);
    }

    public function pushNotificationToApp() {
        D('Push', 'Logic')->pushNotificationToApp();
    }

    public function pushAuth() {
        $uid = I('uid');
        $oper = I('oper');

        $content =  array(
            'content' => '组织者认证顺利通过',
            'sendtime' => time(),
            'operateType' => $oper
        );
        $pushinfo = D('Push', 'Logic')->authUser($uid, $content);   
    }

    public function pushMessageToSingle() {
        $did = I('did');

        $rep = D('Push', 'Logic')->pushMessageToSingle($did, 'aaa', '1231245');
        dump($rep);
    }

    public function send_system_message() {
        $content = I('content');

        $sendData = array();
        $sendData['t'] = 0;
        $sendData['d'] = array(
            'content' => $content,
            'sendtime' => time()
        );

        $uid = I('uid');

        $res = D('Push', 'Logic')->sendUser($uid, '', json_encode($sendData));
        dump($res);
    }

    public function test() {
    }
}
