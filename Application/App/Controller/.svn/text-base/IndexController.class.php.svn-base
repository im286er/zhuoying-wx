<?php
namespace App\Controller;
use Think\Controller;
use Think\Exception;

class IndexController extends Controller {
    public function Index(){
        $this->display();
    }

    public function channel(){
        $channel = D('SaeChannel');
        $connection = $channel->createChannel('test',100);

        dump($connection);
        $message_content = 'hello,sae';
        // Send message
        $ret = $channel->sendMessage('test',$message_content);
    }

    public function testValidate(){
        $User = D('User');
        if (!$User->create()) {
            $this->retModelError($User);
        }

        $this->retSuccess();
    }

    public function clearData(){
        S('movietime', null);
    }

    public function RemoveTags(){
        $ret =  D('JPush', 'Logic')->RemoveTags(array(C('PUSH_PARTY_PREFIX').I('pid')));
        dump($ret);
    }

    public function AddTags(){
        $ret = D('JPush', 'Logic')->AddTags(I('regid'), array(C('PUSH_PARTY_PREFIX').I('pid')));
        dump($ret);
    }

    public function getOrder(){
        $pid = D('Order')->where('uid = %d and not exists (select pid from (select distinct(t1.pid) from t_socialcircle t1, t_socialcircle_user t2 where t1.id = t2.scid and t2.uid = %d) t3 where t_order.pid = t3.pid)', I('id'), I('id'))->getField('pid');
        dump($pid);
    }

    public function test(){
        $movieList = S('movietime');
        dump($movieList);
    }

    public function tokle(){
        $url = 'http://www.tuling123.com/openapi/api?key=02cda105656bd5bd4837b9c463d6046d&info='.I('content').'&userid='.I('id');
        $ret = $this->curl($url);
        $ret = json_decode($ret);

        //$this->im->throwBottle(I('id'), $ret->text, $this->scid, I('privacy'), $this->userID);

        dump($ret->text);
    }

    public function createGroup(){
        D('Im', 'Logic')->createCircle('test');
    }

    public function getToken(){
        $uid = substr(md5(uniqid(mt_rand(), true)), 0, 20);
        $requ = D('Im', 'Logic')->getToken($uid, null, null);
        D('Im', 'Logic')->joinCircle($uid, 'test');
        $this->retSuccess($requ->token);
    }

    protected function curl($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}