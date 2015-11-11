<?php
/**
 * Author: NanQi
 * Date: 20150604 11:23
 */
namespace Weixin\Controller;
use Think\Controller;

class MenuController extends Controller{
    public function Create(){
        $data = '
        {
            "button":
            [
                {
                    "type":"view",
                    "name":"心愿池",
                    "url":"http://weixin.myline.cc/weixin/wishes"
                },
                {
                    "type":"view",
                    "name":"电影活动",
                    "url":"http://weixin.myline.cc/weixin/activitys"
                },
                {
                    "name":"捉影",
                    "sub_button":
                    [
                        {
                            "type":"view",
                            "name":"APP下载",
                            "url":"http://fir.im/default"
                        },
                        {
                            "type":"view",
                            "name":"捉影社区",
                            "url":"http://s.p.qq.com/pub/jump?d=AAAOb_VD"
                        },
                        {
                            "type":"view",
                            "name":"粉丝福利",
                            "url":"http://mp.weixin.qq.com/s?__biz=MzAxOTUwMTE0MQ==&mid=210486967&idx=1&sn=508c9ab4e9ebbeb2a6f4a23c8390dbd8#rd"
                        },
                        {
                            "type":"view",
                            "name":"九月影单",
                            "url":"http://mp.weixin.qq.com/s?__biz=MzAxOTUwMTE0MQ==&mid=210134501&idx=3&sn=22dbb8daa6e423f7884426abdbe1b1a6#rd"
                        },
                        {
                            "type":"view",
                            "name":"魔仙影评",
                            "url":"http://mp.weixin.qq.com/s?__biz=MzAxOTUwMTE0MQ==&mid=210392691&idx=1&sn=f405874d213469f094c3e7902a1e1889#rd"
                        }
                    ]
                }
            ]
         }';


        $ret = D('Menu')->create_menu($data);
        dump($ret);
    }
}
