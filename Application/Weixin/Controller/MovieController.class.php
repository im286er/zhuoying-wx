<?php

namespace Weixin\Controller;
use Think\Controller;
use Think\Model;

class MovieController extends Controller{
    public function index() {
        $mid = I('id');
        $title = I('title');
        $city = I('city');
        $uid = I('uid');
        $this->assign('mid',$mid);
        $this->assign('title',$title);
        $this->assign('city',$city);
        $this->assign('uid',$uid);
        $this->display();
    }
}