<?php
/**
 * Created by PhpStorm.
 * User: 元凯
 * Date: 2015/8/4
 * Time: 11:30
 */


namespace Api\Controller;
use Think\Controller;
use Think\Model;

class CommentController extends Controller{
    /**
     * 添加评论
     */
    public function add(){
        $Comment=D("comment");
        $Comment->validation(array(
            array('uid', 'require', '用户不能为空', Model::MUST_VALIDATE, 'regex'),
            array('mid', 'require', '电影不能为空', Model::MUST_VALIDATE, 'regex'),
            array('title', 'require', '标题不能为空', Model::MUST_VALIDATE, 'regex'),
            array('content', 'require', '评论不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $uid=I("uid");
        $mid=I("mid");
        $title=I("title");
        $content=I("content");

        $flg = $Comment->addComment($uid,$mid,0,$title,$content);

        if ($flg) {
            $this->retSuccess("添加评论成功!");
        }
        
        $this->retError();
    }


    /**
     * 获取心愿下评论列表
     */
    public function get_list(){
        M()->validation(array(
            array('mid', 'require', '电影不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $mid = I('mid');
        
        $list = M()->query("select c.id, c.parent_id as mid, c.title, c.content, c.createtime, c.user_id as uid, u.nickname, u.avatar from t_user u, t_comment c where u.id = c.user_id and c.parent_id = '$mid'");

        $this->retSuccess($list);
    }
}