<?php

namespace Weixin\Controller;
use Think\Controller;
use Think\Model;

class HomeController extends Controller{

    public function activity() {
        $package = D('Jssdk')->getSignPackage();
        $this->assign('package', $package);

        $this->assign('wxData', $result);
        $this->display();
    }

    public function wish() {

        $code = I('get.code');

        $user = D('User');

        //通过code获得openid
        if (empty($code)) {
            //触发微信返回code码
            $url = $user->createOauthUrlForCode('http://weixin.myline.cc/weixin/wishes');
            header("Location: $url");
            exit;
        }

        $isUserinfo = I('get.userinfo');
        if ($isUserinfo) {

            $userinfo = $user->get_userinfo_web($code);
        }
        else {
            $userinfo = $user->get_userinfo_public($code);

            if ($userinfo['subscribe'] == '0') {
                //触发微信返回code码
                $url = $user->createOauthUrlForCode('http://weixin.myline.cc/weixin/wishes?userinfo=1', 'snsapi_userinfo');
                header("Location: $url");
                exit;
            }
        }

        if ($userinfo['errcode']) {
            dump($userinfo);
            exit;
        }

        $unionid = $userinfo['unionid'];

        $account = D('Thirdaccount')->where("atype = 'weixin' and token = '$unionid'")->find();
        $uid = $account['user_id'];
        $user = D('User');
        if (!$uid) {
            //TODO 现在直接创建新帐号,以后可能增加帐号绑定功能
            $data = array(
                'nickname' => $userinfo['nickname'],
                'avatar' => $userinfo['headimgurl'],
                'sex' => $userinfo['sex'],
                'newpasstime' => time(),
                'istempuser' => 1,
                'createtime' => time(),
            );

            if ($user->create($data)) {
                $uid = $user->add();
            }

            $data = array(
                'token' => $unionid,
                'user_id' => $uid,
                'atype' => 'weixin'
            );
            $thirdaccount = D('Thirdaccount');
            if ($thirdaccount->create($data)) {
                $thirdaccount->add();
            }
        }

        $info = $user->cache('user_base_'.$uid, 3600 * 24)->find($uid);
        $this->assign('user',$info);

        $package = D('Jssdk')->getSignPackage();
        $this->assign('package', $package);

        $this->assign('wxData', $userinfo);
        $this->display();
    }

    public function test() {
        // //使用jsapi接口
        // $jsApi = D('Jsapi', 'Pay');

        // //=========步骤1：网页授权获取用户openid============
        // //通过code获得openid
        // if (!isset($_GET['code'])) {
        //     //触发微信返回code码
        //     $url = $jsApi->createOauthUrlForCode('http://weixin.myline.cc/weixin/party?showwxpaytitle=1');
        //     header("Location: $url");
        //     exit;
        // }
        // else {
        //     //获取code码，以获取openid
        //     $code = $_GET['code'];
        //     $jsApi->setCode($code);
        //     $openid = $jsApi->getOpenId();
        // }

        // $this->retSuccess($openid);
//         $sql_format_content = "INSERT INTO `t_resource` (`id`, `user_id`, `parent_id`, `is_folder`, `sort`, `rname`, `rdesc`, `rsize`, `mime_type`, `rext`, `key_orignal`, `key_preview`, `key_thumb`, `is_processing`, `persistent_id`, `bucket`, `charges`, `create_time`, `status`) VALUES
// ({0}, 72, 0, 0, 0, '{1}', '', 496456, 'image/jpeg', 'JPG', 'o_19rai36mbvhd18fd1inp1fvo11371t.jpg', 'o_19rai36mbvhd18fd1inp1fvo11371t.jpg?imageView2/0/w/1024', 'o_19rai36mbvhd18fd1inp1fvo11371t.jpg?imageView2/1/w/57', 0, '', 'space-test', 0, 1438094773, 1);\n";


//         for ($i=1300000; $i < 1300000 + 3000; $i++) { 
//             $sql .= $this->string_format($sql_format_content, $i, '测试文件'.$i);
//         }

//         echo $sql;
    }

    function string_format() {       
        $args = func_get_args();     
        if (count($args) == 0) { return '';}     
        if (count($args) == 1) { return $args[0]; }     
        $str = array_shift($args);
        $GLOBALS['OBJ'] = $args;
        $str = preg_replace_callback(
                '/\\\?{([^{}]+)}/', 
                function ($matches) {
                    list($matche, $name) = $matches;
                    if ($matche[0] === '\\') {
                        return substr($matche, 1);
                    }
                    $obj = $GLOBALS['OBJ'];
                    return isset($obj[$name]) ? $obj[$name] : $matche;
                },
                $str
         ); 
        $GLOBALS['OBJ'] = NULL;
        return $str;
    }
}
