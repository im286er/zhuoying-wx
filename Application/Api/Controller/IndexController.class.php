<?php

namespace Api\Controller;
use Think\Controller;

use SaeVCode;

class IndexController extends Controller{
    public function index(){
        $arr = array();
        $arr['name'] = 'nanqi';
        $arr['age'] = 100;

        dump(json_encode($arr));
    }

    public function test() {
        session_start();
        $vcode = new SaeVCode();
        if ($vcode === false)
                var_dump($vcode->errno(), $vcode->errmsg());

        $_SESSION['vcode'] = $vcode->answer();
        $question=$vcode->question();
        echo $question['img_html'];
    }
}
