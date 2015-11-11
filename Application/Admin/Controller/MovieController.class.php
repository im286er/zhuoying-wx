<?php
/**
 * Author: NanQi
 * Date: 20150504 09:48
 */
namespace Admin\Controller;
class MovieController extends AdminController {
    public function index() {
        $name = I('title');
        $map['status'] = array('gt', -1);

        if (is_numeric($name)) {
            $map['id|title'] = array(intval($name), array('like', '%' . $name . '%'), '_multi' => true);
        } else {
            $map['title'] = array('like', '%' . (string)$name . '%');
        }
        $list = $this->lists('Movie', $map, null, 'id,title,genres');
        int_to_string($list, array(
            'pstate' => array(
                0 => '正在上映',
                1 => '即将上映',
                2 => '老电影',
                3 => '通知',
                4 => '拍摄中',
                5 => '前期制作',
                6 => '后期制作',
            )
        ));
        $this->assign('_list', $list);
        $this->display();
    }

    /**
     * @author : NanQi
     * @date   : 20150504 17:36
     *
     * @desc     新建电影
     */
    public function add(){
        if(IS_POST){
            $movie = D('Movie');
            $data = $movie->create();
            if($data){
                if($movie->add()){
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($movie->getError());
            }
        } else {
            $this->meta_title = '新增配置';
            $this->assign('info',null);
            $this->display('edit');
        }
    }

    /**
     * @author : NanQi
     * @date   : 20150504 17:35
     *
     * @desc     编辑电影
     * @param    $id
     */

    public function edit($id = 0){
        if(IS_POST){
            $movie = D('Movie');
            $data = $movie->create();
            if($data){
                if($movie->save()!== false){
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($movie->getError());
            }
        } else {
            $info = M('Movie')->field(true)->find($id);
            if(false === $info){
                $this->error('获取电影信息错误');
            }

            $this->assign('info', $info);
            $this->meta_title = '编辑电影信息';
            $this->display();
        }
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
                $this->forbid('Movie', $map);
                break;
            case 'resume':
                $this->resume('Movie', $map);
                break;
            case 'delete':
                $this->delete('Movie', $map);
                break;
            default:
                $this->error('参数非法');
        }
    }

    public function getList(){

        $data = M('Movie')->field('id,mname')->select();

        $this->ajaxReturn($data);
    }

    public function classify() {
        if(IS_POST){
            $id = array_unique((array)I('id',0));
            $id = is_array($id) ? implode(',',$id) : $id;
            if (empty($id)) {
                $this->error('请选择要归类的主题!');
            }

            $mid = I('mid');

            $data = array(
                'last_modify_time'=>time(),
                'subject'=>$id
            );

            $flg = D('Movie')->where('id = %d', $mid)->save($data);
            if ($flg) {
                $this->success('归类成功');
            }
        } 

        $list = D('mtype')->where("url <> '' and status > 0")->order('category desc, sort')->select();
        $this->assign('_list', $list);
        $movie = D('Movie');
        $info = $movie->field('id,title,large_images')->where("wish_count = (select max(wish_count) from t_movie where subject = '' or last_modify_time = (select min(last_modify_time) from t_movie))")->find();
        $this->assign('info', $info);
        $this->display();
    }
}