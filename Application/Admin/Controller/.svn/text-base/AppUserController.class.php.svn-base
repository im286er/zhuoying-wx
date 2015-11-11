<?php
/**
 * Author: NanQi
 * Date: 20150513 13:50
 */
namespace Admin\Controller;
class AppUserController extends AdminController {

    public function index() {
        $phonenumber = I('phonenumber');

        if(is_numeric($phonenumber)){
            $map['id|phonenumber'] =   array(intval($phonenumber),array('like','%'.$phonenumber.'%'),'_multi'=>true);
        }else{
            $map['phonenumber']    =   array('like', '%'.(string)$phonenumber.'%');
        }

        $list   = $this->lists("User", $map);
        int_to_string($list, array(
            'sex' => array(
                '' => '未知',
                '0' => '未知',
                '1' => '男',
                '2' => '女',
            )
        ));

        $this->assign('_list', $list);
        $this->display();
    }
}