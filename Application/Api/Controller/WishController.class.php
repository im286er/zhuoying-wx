<?php
/**
 * Created by PhpStorm.
 * User: 元凯
 * Date: 2015/7/30
 * Time: 11:33
 */

namespace Api\Controller;
use Think\Controller;
use Think\Model;

class WishController extends Controller{
    /**
     * 获取心愿列表
     * @param order new/hot 排序
     * @param pageSize int 页面数量
     * @pagem paraIndex int 页面序号
     * @return JSON 心愿列表
     */
    public function getWishList(){
        $order=I('order');
        $list=null;

        if($order=="new"||!$order){
            $list = D('wish')->relation('movie')->order('last_modify_time desc')->page(I('pageIndex').','.I('pageSize'))->select();
        }elseif($order=="hot"){
            $list = D('wish')->relation('movie')->order('want_count desc')->page(I('pageIndex').','.I('pageSize'))->select();
        }

        for($i=0;$i<count($list);$i++){
            $list[$i]['movie_content']=mb_substr($list[$i]['movie_content'],0,20,"utf-8")."...";
        }

        $this->retSuccess($list);
    }
    /**
     * 获取我的心愿列表
     * @param order new/hot 排序
     * @param pageSize int 页面数量
     * @param paraIndex int 页面序号
     * @param uid int 用户心愿ID
     * @return JSON 心愿列表
     */
    public function getMyWishList(){
        $uid=I('uid');
        if(!$uid){
            $this->retError("用户ID为空");
        }
        $order=I('order');
        $list=null;
        $map="";

        if(!(M("user")->where("id=%d",$uid)->find())){
            $this->retError("该用户不存在!");
        }

        $wish_id=D("UserWish")->where("uid=%d",$uid)->field("wishid")->select();

        if(count($wish_id)==0){
            $this->retError("此用户没有任何心愿");
        }

        for($i=0;$i<count($wish_id);$i++){
            if($i==count($wish_id)-1){
                $map.="id=".$wish_id[$i]['wishid'];
                break;
            }
            $map.="id=".$wish_id[$i]['wishid']." OR ";
        }

        if($order=="new"||!$order){
            $list = D('wish')->where($map)->relation('movie')->order('last_modify_time desc')->page(I('pageIndex').','.I('pageSize'))->select();
        }elseif($order=="hot"){
            $list = D('wish')->where($map)->relation('movie')->order('want_count desc')->page(I('pageIndex').','.I('pageSize'))->select();
        }

        $this->retSuccess($list);
    }
    /**
     * 获取心愿详情
     * @param mid int 心愿ID
     * @return JSON 心愿详情
     */
    public function getWishDetail(){
        $wid=I("mid");

        $detail=D("movie")->where("id=%d",$wid)->field("id,casts as movie_actor,directors as movie_director,large_images as movie_picture,summary as movie_content,title as movie_title")->find();
       
        $this->retSuccess($detail);
    }
  
    /**
     * 添加心愿
     * @param uid int 用户ID
     * @param mid int 电影ID
     * @return boolean 添加成功或失败
     */
    public function addWish(){
        M()->validation(array(
            array('uid', 'require', '用户不能为空', Model::MUST_VALIDATE, 'regex'),
            array('mid', 'require', '心愿电影不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $uid = I('uid');
        $mid = I('mid');

        $UserWish = D("UserWish");

        $cnt=$UserWish->where("uid=%d AND mid=%d",$uid, $mid)->count();

        if($cnt){
            $this->retError('该用户已经有此心愿!');
        }

        $flg = $UserWish->addUserWish($uid, $mid);
        if ($flg) {
            $this->retSuccess("添加心愿成功"); 
        }

        $this->retError();
    }


    //获取心愿列表
    public function get_list() {
        M()->validation(array(
            array('isall', 'require', '是否所有不能为空', Model::MUST_VALIDATE, 'regex'),
        ));
        $page_index = I('pageIndex');
        $page_size = I('pageSize');
        if ($page_index && $page_size) {
            $page = 'limit '.($page_index - 1) * $page_size.','.$page_size;
        }
        else {
            $page = '';
        }
        

        $uid = I('uid');
        if (!$uid) $uid = 0;

        $city = I('city');
        if (!$city) $city = '无城市';

        $isall = I('isall');

        if ($isall) {
            $list = M()->query("SELECT m.id,title,year,large_images,intro,case when mac.activity_total_count is NULL then '0' else mac.activity_total_count end activity_total_count,case when mac.activity_incomplete_count is NULL then '0' else mac.activity_incomplete_count end activity_incomplete_count,wish_total_count,wish_incomplete_count, case when uw.uid is NULL then '0' else '1' end iswish
                FROM (SELECT id,title,year,concat(large_images, '!wish') as large_images,intro,activity_total_count,activity_incomplete_count,wish_total_count,wish_incomplete_count FROM t_movie WHERE status > -1 AND wish_total_count > 0) m left join (SELECT uid, mid, iscomplete FROM t_user_wish WHERE uid = $uid) uw on m.id = uw.mid LEFT JOIN (select mid, city, activity_total_count, activity_incomplete_count from t_movie_activity_count where city = '$city') mac ON m.id = mac.mid order by wish_total_count desc $page");

            // $order = 'wish_total_count';

            // $list = array_sort($list, $order, 'desc');
        }
        else {
            $time = time() - 3600 * 24 * 30;
            $list = M()->query("SELECT m.id,title,year,large_images,intro,case when mac.activity_total_count is NULL then '0' else mac.activity_total_count end activity_total_count,case when mac.activity_incomplete_count is NULL then '0' else mac.activity_incomplete_count end activity_incomplete_count,wish_total_count,wish_incomplete_count, case when uw.uid is NULL then '0' else '1' end iswish, case when uw.iscomplete is NULL then '0' else uw.iscomplete end iscomplete, case when mac.activity_total_count > 0 then '1' when uw.createtime < $time then '-1' else '0' end findstatus
                FROM (SELECT id,title,year,concat(large_images, '!wish') as large_images,intro,activity_total_count,activity_incomplete_count,wish_total_count,wish_incomplete_count FROM t_movie WHERE status > -1 AND wish_total_count > 0) m inner join (SELECT uid, mid, iscomplete, createtime FROM t_user_wish WHERE uid = $uid) uw on m.id = uw.mid LEFT JOIN (select mid, city, activity_total_count, activity_incomplete_count from t_movie_activity_count where city = '$city') mac ON m.id = mac.mid order by uw.createtime desc $page"); 
        }

       

        $this->retSuccess($list);
    }

    //根据电影名称查询心愿
    public function get_list_by_movie_title() {
        M()->validation(array(
            array('title', 'require', '查询关键字不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $title=I("title");
        $page_index = I('pageIndex');
        $page_size = I('pageSize');
        if(!$page_index) $page_index = 1;
        if(!$page_size) $page_size = 20;
        $page = 'limit '.($page_index - 1) * $page_size.','.$page_size;

        $uid = I('uid');
        if (!$uid) $uid = 0;

        $city = I('city');
        if (!$city) $city = '无城市';

        $list = M()->query("SELECT m.id,title,year,large_images,intro,case when mac.activity_total_count is NULL then '0' else mac.activity_total_count end activity_total_count,case when mac.activity_incomplete_count is NULL then '0' else mac.activity_incomplete_count end activity_incomplete_count,wish_total_count,wish_incomplete_count, case when uw.uid is null then '0' else '1' end iswish
                from (SELECT id,title,year,concat(large_images, '!wish') as large_images,intro,activity_total_count,activity_incomplete_count,wish_total_count,wish_incomplete_count from t_movie where status > -1 and title like '%$title%' order by wish_incomplete_count desc $page) m left join (SELECT uid, mid, iscomplete from t_user_wish where uid = $uid) uw on m.id = uw.mid LEFT JOIN (select mid, city, activity_total_count, activity_incomplete_count from t_movie_activity_count where city = '$city') mac ON m.id = mac.mid");

        $this->retSuccess($list); 
    }
}