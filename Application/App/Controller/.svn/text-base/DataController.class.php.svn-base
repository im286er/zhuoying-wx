<?php
/**
 * Author: NanQi
 * Date: 20150604 16:37
 */
namespace App\Controller;

use think\Controller;

class DataController extends Controller{

    /**
     * 删除历史聊天记录
     */
    public function deleteMessage(){
        $date = date("Ymdh",strtotime("+1 hour"));
        $ret = D('Im', 'Logic')->messageHistoryDelete($date);

        dump($date);
        dump($ret);
    }
}