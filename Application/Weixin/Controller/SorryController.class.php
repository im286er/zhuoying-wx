<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/29 0029
 * Time: 下午 6:47
 */

namespace Weixin\Controller;


use Think\Controller;

class SorryController extends Controller{

    function index()
    {
//        $openid = M('Kangzhanbufa')->where('id=3152')->getField('openid');
//        $this->send($openid);
        $list = M('Kangzhanbufa')->where('create_time = 0')->limit(10)->select();
        foreach($list as $k=>$v)
        {
            $this->send($v['openid']);
            if($k==0)
            {
                $arr=$v['id'];
            }
            else{
                $arr.=','.$v['id'];
            }
        }


        M()->execute('update t_kangzhanbufa set create_time = 1 where id in ('.$arr.')');
//        echo M()->getLastSql();


    }

    function send($openid)
    {
        $template = D('Template');

        $template->daoqian($openid);
    }

}