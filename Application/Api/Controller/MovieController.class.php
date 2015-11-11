<?php

namespace Api\Controller;
use Think\Controller;
use Think\Model;
header( 'Access-Control-Allow-Origin:*' );


class MovieController extends Controller{
    /**
     * 获取电影列表
     * @param order new/hot 排序
     * @param pageSize int 页面数量
     * @param paraIndex int 页面序号
     * @param tag string 标签(可选)
     * @param title string 标题(可选)
     * @return JSON 电影列表
     */
    public function getMovieList(){
        M()->validation(array(
            array('title', 'require', '查询关键字不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $title=I("title");
        $page_index = I('pageIndex');
        $page_size = I('pageSize');
        if(!$page_index) $page_index = 1;
        if(!$page_size) $page_size = 20;
        $page = 'limit '.($page_index - 1) * $page_size.','.$page_size;

        $city = I('city');
        if (!$city) $city = '无城市';

        $list = M()->query("SELECT m.id,title,year,large_images,intro,case when mac.activity_total_count is NULL then '0' else mac.activity_total_count end activity_total_count,case when mac.activity_incomplete_count is NULL then '0' else mac.activity_incomplete_count end activity_incomplete_count,wish_total_count,wish_incomplete_count 
                from (SELECT id,title,year,concat(large_images, '!wish') as large_images,intro,activity_total_count,activity_incomplete_count,wish_total_count,wish_incomplete_count from t_movie where status > -1 and title like '%$title%' order by activity_total_count desc $page) m LEFT JOIN (select mid, city, activity_total_count, activity_incomplete_count from t_movie_activity_count where city = '$city') mac ON m.id = mac.mid");

        $this->retSuccess($list); 
    }

    public function get_info() {
        M()->validation(array(
            array('mid', 'require', '电影ID不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $mid = I('mid');

        $movie = D('Movie')->cache('movie_base_'.$mid, 3600 * 24)->find($mid);

        if (!$movie) {
            $this->retError('没有对应的电影ID');
        }

        $this->retSuccess($movie);
    }
}
 