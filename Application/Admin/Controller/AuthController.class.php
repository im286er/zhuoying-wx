<?php
/**
 * Author: NanQi
 * Date: 20150421 20:04
 */
namespace Admin\Controller;
// use Common\Logic;
class AuthController extends AdminController {
    /**
    *组织者认证
    */
    public function hostList(){
        $uname            =   I('uname');
        $map['status']    = array('eq', 2);

        if(is_numeric($uname)){
            $map['id|uname'] =   array(intval($uname),array('like','%'.$uname.'%'),'_multi'=>true);
        }else{
            $map['uname']    =   array('like', '%'.(string)$uname.'%');
        }
        $list   = $this->lists('AuthHost', $map, 'createtime desc');
        $this->assign('_list', $list);
        $this->display();
    }

    /**
    * 场地所有者认证
    */
    public function siteList(){
        $sitename            =   I('sitename');
        $map['status']    = array('eq', 2);

        if(is_numeric($sitename)){
            $map['id|sitename'] =   array(intval($sitename),array('like','%'.$sitename.'%'),'_multi'=>true);
        }else{
            $map['sitename']    =   array('like', '%'.(string)$sitename.'%');
        }
        $list   = $this->lists('AuthSite', $map, 'createtime desc');
        $this->assign('_list', $list);
        $this->display();
    }

    /**
    *组织者认证未通过
    */
    public function hostListUnpassed(){
        $uname            =   I('uname');
        $map['status']    = array('elt', 0);

        if(is_numeric($uname)){
            $map['id|uname'] =   array(intval($uname),array('like','%'.$uname.'%'),'_multi'=>true);
        }else{
            $map['uname']    =   array('like', '%'.(string)$uname.'%');
        }
        $list   = $this->lists('AuthHost', $map, 'createtime desc');
        $this->assign('_list', $list);
        $this->display();
    }  

    /**
    * 场地所有者认证未通过
    */
    public function siteListUnpassed(){
        $sitename            =   I('sitename');
        $map['status']    = array('elt', 0);

        if(is_numeric($sitename)){
            $map['id|sitename'] =   array(intval($sitename),array('like','%'.$sitename.'%'),'_multi'=>true);
        }else{
            $map['sitename']    =   array('like', '%'.(string)$sitename.'%');
        }
        $list   = $this->lists('AuthSite', $map, 'createtime desc');
        $this->assign('_list', $list);
        $this->display();
    }    

    /**
    *组织者认证
    */
    public function hostListHistory(){
        $uname            =   I('uname');
        $map['status']    = array('eq', '1');

        if(is_numeric($uname)){
            $map['id|uname'] =   array(intval($uname),array('like','%'.$uname.'%'),'_multi'=>true);
        }else{
            $map['uname']    =   array('like', '%'.(string)$uname.'%');
        }
        $list   = $this->lists('AuthHost', $map, 'createtime desc');
        $this->assign('_list', $list);
        $this->display();
    }

    /**
    * 场地所有者认证
    */
    public function siteListHistory(){
        $sitename            =   I('sitename');
        $map['status']    = array('eq', '1');

        if(is_numeric($sitename)){
            $map['id|sitename'] =   array(intval($sitename),array('like','%'.$sitename.'%'),'_multi'=>true);
        }else{
            $map['sitename']    =   array('like', '%'.(string)$sitename.'%');
        }
        $list   = $this->lists('AuthSite', $map, 'createtime desc');
        $this->assign('_list', $list);
        $this->display();
    }    


    public function hostView($id = 0){
        $info = M('AuthHost')->where('status=2') ->field(true)->find($id);
        if(false === $info){
            $this->error('获取组织者信息错误');
        }

        $this->assign('info', $info);
        $this->meta_title = '审核组织者信息';
        $this->display();
    }

    public function siteView($id = 0){
        $info = M('AuthSite')->where('status=2')->field(true)->find($id);
        if(false === $info){
            $this->error('获取场地提供者信息错误');
        }

        $this->assign('info', $info);
        $this->meta_title = '审核场地提供者信息';
        $this->display();
    }

    public function changeHost($method=null){
        $uid = array_unique((array)I('uid',0));

        $uid = is_array($uid) ? implode(',',$uid) : $uid;

        $id = array_unique((array)I('id',0));
        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        if( empty($uid) ){
            $this->error('缺少用户ID!');
        }
        $map['id'] =   array('in',$id);
        switch ( strtolower($method) ){
            case 'consent':
                //更新AuthHost表
//                $this->resume('AuthHost', $map);
                M('AuthHost')->where("id=$id")->setField("status", "1");


                //更新User表
                M('User')->where("id=$uid")->setField("role", "1");

                //notify user success    
                $content =  array(
                    'content' => '组织者认证顺利通过',
                    'sendtime' => time(),
                    'operateType' => 1
                );
                $pushinfo = D('Push', 'Logic')->authUser($uid, $content);   

                $this->success('组织者认证顺利通过',U('Auth/hostList'));
                break;
            case 'refusal':
                //更新AuthHost表
//                $this->forbid('AuthHost', $map);
                M('AuthHost')->where("id=$id")->setField("status", "0");
                
                //更新User表
                M('User')->where("id='$uid'")->setField("role", "0");

                //notify user success    
                $content =  array(
                    'content' => '组织者认证未通过',
                    'sendtime' => time(),
                    'operateType' => '-1'
                );
                $pushinfo = D('Push', 'Logic')->authUser($uid, $content);   

                $this->success('已标记未通过', U('Auth/hostList'));
                break;
            default:
                $this->error('参数非法');
        }
    }

    public function changeSite($method=null){
        $uid = array_unique((array)I('uid',0));
        $uid = is_array($uid) ? implode(',',$uid) : $uid;

        $id = array_unique((array)I('id',0));
        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        if( empty($uid) ){
            $this->error('缺少用户ID!');
        }

        $map['id'] =   array('in',$id);
        switch ( strtolower($method) ){
            case 'consent':
                //更新AuthSite表
//                $this->resume('AuthSite', $map);
                M('AuthSite')->where("id='$id'")->setField("status", "1");
                
                //更新User表
                M('User')->where("id='$uid'")->setField("role", "2");

                //notify user success    
                $content =  array(
                    'content' => '场地认证顺利通过',
                    'sendtime' => time(),
                    'operateType' => 2
                );
                $pushinfo = D('Push', 'Logic')->authUser($uid, $content);

                $this->success('场地认证顺利通过',U('Auth/siteList'));
                break;
            case 'refusal':
                //更新AuthSite表
//                $this->forbid('AuthSite', $map);
                M('AuthSite')->where("id='$id'")->setField("status", "0");
                
                //更新User表
                M('User')->where("id='$uid'")->setField("role", "0");

                //notify user success    
                $content =  array(
                    'content' => '场地认证未通过',
                    'sendtime' => time(),
                    'operateType' => -2
                );
                $pushinfo = D('Push', 'Logic')->authUser($uid, $content);

                $this->success('已标记未通过',U('Auth/siteList'));
                break;
            default:
                $this->error('参数非法');
        }
    }
   
}