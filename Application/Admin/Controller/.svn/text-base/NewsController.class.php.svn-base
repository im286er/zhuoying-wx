<?php
/**
 * Author: NanQi
 * Date: 20150421 20:04
 */
namespace Admin\Controller;
class NewsController extends AdminController {
    public function index(){
        $title            =   I('title');
        $map['sendstate'] = array('neq', 2);
        $map['status']    = array('gt', -1);

        if(is_numeric($title)){
            $map['id|title'] =   array(intval($title),array('like','%'.$title.'%'),'_multi'=>true);
        }else{
            $map['title']    =   array('like', '%'.(string)$title.'%');
        }
        $list   = $this->lists('News', $map, 'sendstate desc, createtime desc');
        int_to_string($list, array(
            'atype' => array(
                0 => '电影沙龙',
                1 => '正常院线电影',
            ),
            'sendstate' => array(
                0 => '编辑',
                1 => '待发布',
                2 => '已发布',
            )
        ));
        $this->assign('_list', $list);
        $this->display();
    }

    public function published(){
        $title            =   I('title');
        $map['sendstate'] = array('eq', 2);
        $map['status']    = array('gt', -1);

        if(is_numeric($title)){
            $map['id|title'] =   array(intval($title),array('like','%'.$title.'%'),'_multi'=>true);
        }else{
            $map['title']    =   array('like', '%'.(string)$title.'%');
        }
        $list   = $this->lists('News', $map, 'sendstate desc, createtime desc');
        int_to_string($list, array(
            'atype' => array(
                0 => '电影沙龙',
                1 => '正常院线电影',
            ),
            'sendstate' => array(
                0 => '编辑',
                1 => '待发布',
                2 => '已发布',
            )
        ));
        $this->assign('_list', $list);
        $this->display();
    }

    public function changeStatus($method=null){
        $id = array_unique((array)I('id',0));
        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map['id'] =   array('in',$id);
        switch ( strtolower($method) ){
            case 'forbid':
                $this->forbid('News', $map);
                break;
            case 'resume':
                $this->resume('News', $map);
                break;
            case 'delete':
                $this->delete('News', $map);
                break;
            case 'ready':
                $this->ready('News', $map);
                break;
            case 'undo':
                $this->undo('News', $map);
                break;
            case 'publish':
                $this->publish('News', $map);
                break;
            default:
                $this->error('参数非法');
        }
    }



    private function ready($model , $where = array()){
        $data['sendstate']         =   1;
        $this->editRow($model, $data, $where, array('success'=>'准备成功！', 'error'=>'准备失败！'));
    }

    private function undo($model , $where = array()){
        $data['sendstate']         =   0;
        $this->editRow($model, $data, $where, array('success'=>'撤回成功！', 'error'=>'撤回失败！'));
    }

    private function publish($model , $where = array()){
        $data['sendstate']         =   2;
        $data['createtime']        =   time();
        $news = D("News")->field('id,pushcontent')->find(I('id'));

        $ret = D('JPush', 'Logic')->pushNotificationByTags(C('UNKNOWN_TAG'), $news['pushcontent'],
            null, array(
                "redirect" => "recommend",
                "recommend_id" => $news['id'],
            ));

        S('movietime', null);

        $this->editRow($model, $data, $where, array('success'=>'发布成功！', 'error'=>'发布失败！'));
    }


    /**
     * @author : Comer
     * @date   : 2015/4/22 17:40
     *
     * @desc     添加影讯
     * @param
     * @return   null
     */

    public function add(){
        if(IS_POST){
            $news = D('News');
            $data = $news->create();
            if($data){
                $id = $news->add();
                if($id){
                    $this->success('新增成功', U('addParty?nid='.$id));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($news->getError());
            }
        } else {
            //$this->assign('info',array('id'=>null));
            $this->meta_title = '新增资讯';
            $this->display('edit');
        }
    }

    /**
     * @author : Comer
     * @date   : 2015/4/23 13:02
     *
     * @desc     编辑影讯
     * @param    $id
     * @return   null
     */

    public function edit($id = 0){
        if(IS_POST){
            $news = D('News');
            $data = $news->create();
            if($data){
                if($news->save()!== false){
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($news->getError());
            }
        } else {
            $info = M('News')->field(true)->find($id);
            if(false === $info){
                $this->error('获取资讯信息错误');
            }

            $this->assign('info', $info);
            $this->assign('nid', $id);
            $this->meta_title = '编辑资讯';
            $this->display();
        }
    }

    public function addParty($nid){
        if(IS_POST){
            $party = D('Party');
            $data = $party->create();

            $fid = M('Fares')->add(array(
                'price' => I('post.price'),
            ));

            $data['fid'] = $fid;

            M()->startTrans();

            if($data){
                $id = $party->add($data);
                $id = M('Socialcircle')->add(array(
                    'pid' => $id,
                    'openstatus' => 0,
                ));
                if($id){
                    $this->success('新增成功', U('editMovies?nid='.$nid));


                    M()->commit();
                } else {
                    M()->rollback();
                    $this->error('新增失败');
                }
            } else {
                $this->error($party->getError());
            }
        } else {
            $this->assign('nid', $nid);
            $this->meta_title = '新增活动';
            $this->display('editParty');
        }
    }

    public function editParty($nid){
        if(IS_POST){
            $party = D('Party');
            $data = $party->create();
            $data['fares'] = array(
                'price' => I('post.price'),
            );
            if($data){
                if($party->relation('fares')->where("nid = %d", $nid)->save($data)!== false){
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($party->getError());
            }
        } else {
            $info = D('Party')->relation(true)->where('nid = %d', $nid)->find();
            if(false === $info){
                $this->error('获取活动信息错误');
            }

            $this->assign('info', $info);
            $this->assign('nid', $nid);
            $this->meta_title = '编辑活动';
            $this->display();
        }
    }

    public function editMovies($nid){
        $list = D('NewsMovie')->relation('movie')->where('nid = %d', $nid)->select();
        if(false === $list){
            $this->error('获取电影信息错误');
        }

        $this->assign('_list', $list);
        $this->assign('nid', $nid);
        $this->meta_title = '编辑电影';
        $this->display();
    }

    public function addNewsMovie($nid){
        if (IS_POST) {
            $movie = D('NewsMovie');
            $data = $movie->create();
            if($data){

                //验证同一个资讯下面不能有相同的电影
                $cnt = $movie->where("nid = %d and mid = %d", $nid, $data['mid'])->count();
                if ($cnt) {
                    $this->error('该资讯下已经有相同电影');
                }

                if($movie->where("nid = %d", $nid)->add()!== false){
                    $this->success('添加成功', U('editMovies?nid='.$nid));
                } else {
                    $this->error('添加失败');
                }
            } else {
                $this->error($movie->getError());
            }
        }
        else {
            $this->assign('nid', $nid);
            $this->meta_title = '资讯添加电影';
            $this->display();
        }
    }

    public function deleteNewsMovie($nid){
        $id = array_unique((array)I('id',0));
        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map['id'] =   array('in',$id);

        if(D('NewsMovie')->where($map)->delete()!== false){
            $this->success('添加成功', U('editMovies?nid='.$nid));
        } else {
            $this->error('添加失败');
        }
    }
}