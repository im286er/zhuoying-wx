<?php
/**
 * Created by PhpStorm.
 * User: 元凯
 * Date: 2015/8/4
 * Time: 11:32
 */
namespace Api\Model;
use Think\Model\RelationModel;
class CommentModel extends RelationModel{

    public function addComment($uid,$pid,$cid,$title,$content){
        $data = array(
            'user_id' => $uid,
            'parent_id' => $pid,
            'comment_parent_id' => $cid,
            'title' => $title,
            'content' => $content,
            'createtime' =>time(),
        );

        $comment=D("comment");

        if($comment->create($data)){
            $flg = $comment->add();

            if ($flg) {
                D('Movie')->where("id=%d",$pid)->setInc('talk_count');
            }

            return $flg;
        }

        $this->retError();
    }
}