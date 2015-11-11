<?php
namespace App\Controller;
use Think\Controller;
use Think\Model;
class MovieController extends Controller {

    protected $userID = '';

    //必须登录才能调用Movie控制器方法
    function _initialize() {
        $this->userID = authUserID();
    }

    /**
     * @author : NanQi
     * @date   : 20150412 10:39
     *
     * @desc     获取资讯
     * @param    int id 资讯ID
     * @return   News 资讯
     */
    public function news(){

        $news = D("News");

        $news->validation(array(
            array('id', 'require', '资讯ID不能为空', Model::MUST_VALIDATE, 'regex'),
            array('id', 'number', '资讯ID格式不正确', Model::MUST_VALIDATE, 'regex'),
        ));

        $data = $news->cache("news_".I('id'), 60)
            ->Field('id,title,content,recommend,atype')
            ->where('sendstate = 2 and id = %d', I('id'))
            ->relation('movies')
            ->find();

        $userLike = D('UserMovie')->where("uid = '%d'", $this->userID)->field('mid,likestate')->select();

        foreach($data['movies'] as $key1=>$val1) {

            $data['movies'][$key1]['likestate'] = 0;

            foreach($userLike as $key2=>$val2) {
                if ($val1['id'] == $val2['mid']) {
                    $data['movies'][$key1]['likestate'] = $userLike[$key2]['likestate'];
                }
            }
        }

        $this->retSuccess($data);
    }

    /**
     * @author : NanQi
     * @date   : 20150416 16:40
     *
     * @desc     获取最新电影沙龙资讯
     * @return   News 资讯
     */
    public function latestNews(){

        $news = D("News");

        if(true){
            //TODO 这里还没有实现对于用户的最新资讯获取
        }

        $data = $news->cache("latestNews_".$this->userID, 60)
            ->Field('id,title,content,recommend,atype')
            ->relation('movies')
            ->where("createtime = (select max(createtime) from t_news t1 where sendstate = 2 and not exists (select nid from (select nid from t_party t2, t_order t3 where t2.id = t3.pid and t3.paystatus = 1 and t3.uid = %d) t4 where t1.id = t4.nid))", $this->userID)
            ->find();

        if (empty($data)) {
            //$this->retError(201, '没有对应的资讯');
            $data = $news->cache("latestNews_".$this->userID, 60)
                ->Field('id,title,content,recommend,atype')
                ->relation('movies')
                ->where("createtime = (select max(createtime) from t_news where sendstate = 2)")
                ->find();
        }

        $userLike = D('UserMovie')->where("uid = '%d'", $this->userID)->field('mid,likestate')->select();

        foreach($data['movies'] as $key1=>$val1) {

            $data['movies'][$key1]['likestate'] = 0;

            foreach($userLike as $key2=>$val2) {
                if ($val1['id'] == $val2['mid']) {
                    $data['movies'][$key1]['likestate'] = $userLike[$key2]['likestate'];
                }
            }
        }

        $this->retSuccess($data);
    }

    /**
     * @author : NanQi
     * @date   : 20150416 16:40
     *
     * @desc     获取推送给自己的电影沙龙资讯列表（推送历史）
     * @return   News 资讯
     */
    public function newsList(){
        $news = D("News");

        if(true){
            //TODO 这里还没有实现对于用户的最新资讯获取
        }

        $countnum = -1;
        $data = null;

        if (I('page') != null && I('limit') != null) {

            $countnum = $news->where('sendstate = 2')->count();

            $orderBy = I('order');
            $orderBy = empty($orderBy) ? 'createtime desc' : $orderBy;

            $data = $news->Field('id,title,content,recommend,atype,createtime')->relation('movies')
                ->where('sendstate = 2')
                ->order($orderBy)
                ->limit(I('limit'))
                ->page(I('page'))
                ->select();
        }
        else {

            $data = $news
                ->Field('id,title,content,recommend,atype,createtime')
                ->where('sendstate = 2')
                ->relation('movies')
                ->select();

            $countnum = count($data);
        }

        $userLike = D('UserMovie')->where("uid = '%d'", $this->userID)->field('mid,likestate')->select();

        foreach($data['movies'] as $key1=>$val1) {

            $data['movies'][$key1]['likestate'] = 0;

            foreach($userLike as $key2=>$val2) {
                if ($val1['id'] == $val2['mid']) {

                    $data['movies'][$key1]['likestate'] = $userLike[$key2]['likestate'];
                }
            }
        }

        $this->retPager($countnum, $data);
    }

    /**
     * @author : NanQi
     * @date   : 20150423 15:31
     *
     * @desc     获取活动信息
     * @param    string id 资讯ID
     * @return   Party 活动详情
     */
    public function party(){
        $party = D('Party');

        $party->validation(array(
            array('id', 'require', '资讯ID不能为空', Model::MUST_VALIDATE, 'regex'),
            array('id', 'number', '资讯ID格式不正确', Model::MUST_VALIDATE, 'regex'),
        ));

        $ret = $party->cache("party_".I('id'), 60*60*24)
            ->relation(true)
            ->where('nid = %d', I('id'))
            ->find();

        $this->retSuccess($ret);
    }

    /**
     * @author : NanQi
     * @date   : 20150424 13:25
     *
     * @desc     喜欢电影
     * @param    int id 电影ID
     * @return   bool
     */
    public function likeMovie(){
        $userMovie = D('UserMovie');

        $userMovie->validation(array(
            array('id', 'require', '电影ID不能为空', Model::MUST_VALIDATE, 'regex'),
            array('id', 'number', '电影ID格式不正确', Model::MUST_VALIDATE, 'regex'),
        ));

        $likeState = $userMovie->where("uid = '%d' and mid = '%d'", $this->userID, I('id'))->getField('likestate');
        if (empty($likeState)) {

            $userMovie->addLikeState($this->userID, I('id'), $userMovie::LIKE_MOVIE);
        }
        elseif ($likeState != $userMovie::LIKE_MOVIE) {

            $userMovie->modifyLikeState($this->userID, I('id'), $userMovie::LIKE_MOVIE);
        }
        else {
            $this->retError(201, '不能重复喜欢');
        }

        S("latestNews_".$this->userID, null);//清空资讯缓存
        S("news_".I('id'), null);//清空资讯缓存
        $this->retSuccess();
    }

    /**
     * @author : NanQi
     * @date   : 20150424 13:25
     *
     * @desc     不喜欢电影
     * @param    int id 电影ID
     * @return   bool
     */
    public function dislikeMovie(){
        $userMovie = D('UserMovie');

        $userMovie->validation(array(
            array('id', 'require', '电影ID不能为空', Model::MUST_VALIDATE, 'regex'),
            array('id', 'number', '电影ID格式不正确', Model::MUST_VALIDATE, 'regex'),
        ));

        $likeState = $userMovie->where("uid = '%d' and mid = '%d'", $this->userID, I('id'))->getField('likestate');
        if (empty($likeState)) {

            $userMovie->addLikeState($this->userID, I('id'), $userMovie::DISLIKE_MOVIE);
        }
        elseif ($likeState != $userMovie::DISLIKE_MOVIE) {

            $userMovie->modifyLikeState($this->userID, I('id'), $userMovie::DISLIKE_MOVIE);
        }
        else {
            $this->retError(201, '不能重复不喜欢');
        }

        S("latestNews_".$this->userID, null);//清空资讯缓存
        S("news_".I('id'), null);//清空资讯缓存
        $this->retSuccess();
    }
}
