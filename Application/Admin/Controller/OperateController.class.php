<?php
/**
*@author WUCHU
*@date 2015 10 23
*/

namespace Admin\Controller;
use User\Api\UserApi;

/**
 * 后台用户控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class OperateController extends AdminController {

    /**
     * 用户管理首页
     */
    public function index(){
        $authsite = M('auth_site') ->where('status=2') ->select();
        $this->assign('_list', $authsite);
        $this->meta_title = '场地认证-ing';
        $this->display();
    }

    /**
    * 展示认证失败数据
    */
    public function showauthfailed(){
        $authsite = M('auth_site') ->where('status=0') ->select();
        $this->assign('_list', $authsite);
        $this->meta_title = '场地认证-ing';
        $this->display();
    }  

    /**
    * 展示认证成功的数据
    */
    public function showauthsuccess(){
        $authsite = M('auth_site') ->where('status=1') ->select();
        $this->assign('_list', $authsite);
        $this->meta_title = '场地认证-ing';
        $this->display();
    }  

    /**
    * 展示认证的历史数据
    */
    public function showhistory(){
        $authsite = M('auth_site') ->order('createtime desc') ->select();
        $this->assign('_list', $authsite);
        $this->meta_title = '场地认证-ing';
        $this->display();
    }         

    /**
    *跳转到场地认证的详细信息页面
    */
    public function do_shwodetail(){
        $this->success("coding unfinished");
    }    

    /**
    *场地认证通过
    */
    public function do_authsuccess(){
        $AUTH_SITE = M("auth_site") ;
        $AUTH_SITE->where('id='.$_GET[siteid])-> setField('status','1'); 

        $USER = M("user");
        $USER -> where('id='.$_GET[uid]) ->setField('role',2);
        $this->success("认证通过");
    }    

    /**
    *认证失败
    */
    public function do_authfailed(){
        $AUTH_SITE = M("auth_site") ;
        $AUTH_SITE->where('id='.$_GET[siteid])-> setField('status','0'); 

        $this->success("认证已禁用");       
    }       
}