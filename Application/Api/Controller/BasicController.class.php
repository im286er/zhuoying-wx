<?php

namespace Api\Controller;
use Think\Controller;
use Think\Model;

class AccountController extends Controller{
    public function get_bank_type_list() {
        $list = M('BankType')->where("status > 0")->field('title,icon')->select();
        $this->retSuccess($list);
    }
}