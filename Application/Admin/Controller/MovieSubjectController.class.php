<?php
/**
 * Author: NanQi
 * Date: 20150504 09:48
 */
namespace Admin\Controller;
class MovieSubjectController extends AdminController {

    public function index() {
        $name = I('val');
        $map['status'] = array('gt', -1);

        if (is_numeric($name)) {
            $map['id|val'] = array(intval($name), array('like', '%' . $name . '%'), '_multi' => true);
        } else {
            $map['val'] = array('like', '%' . (string)$name . '%');
        }
        $list = $this->lists('mtype', $map, 'category desc, url desc, title desc, subtitle desc');
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
            $movie = D('mtype');
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
            $this->meta_title = '新增电影主题';
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
            $movie = D('mtype');
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
            $info = M('mtype')->field(true)->find($id);
            if(false === $info){
                $this->error('获取电影主题信息错误');
            }

            $this->assign('info', $info);
            $this->meta_title = '编辑电影主题';
            $this->display();
        }
    }

    public function divide($id = 0) {
        if(IS_POST){
            $aid = array_unique((array)I('aid',0));

            $dataList = array();

            foreach ($aid as $value) {
                $data['aid'] = $value;
                $data['tid'] = $id;
                $data['createtime'] = time();

                $dataList[] = $data;
            }
            $model = M('MtypeActivity');
            $flg = $model->addAll($dataList);
            if($flg){
                $this->success('划分成功', U('index'));
            } else {
                $this->error($model->getError());
            }
        } else {
            $info = M('mtype')->field(true)->find($id);
            if(false === $info){
                $this->error('获取电影主题信息错误');
            }

            $title = I('title');
            if ($title) {
                $where = " and a.title like '%$title%'";
            }

            $list = M()->query("SELECT a.id as aid, a.uid, a.sid, a.title, a.money, a.money_hour, a.starttime, a.endtime, u.nickname, u.avatar, a.movie_title_list as movie_name, s.address, s.latitude, s.longitude, a.astatus, a.atype
            FROM t_activity a, t_user u, t_site s
            where a.uid = u.id and a.sid = s.id $where");

            int_to_string($list, array(
                'astatus' => array(
                    '0' => '筹备中',
                    '1' => '审核场地',
                    '2' => '报名中',
                    '3' => '报名已满',
                    '4' => '开始',
                    '5' => '结束',
                    '6' => '关闭',
                    '-1' => '删除',
                    '-2' => '已取消',
                    '-3' => '已流产',
                ),
                'atype' => array (
                    '0' => '单选电影',
                    '1' => '自助活动',
                    '2' => '大型活动',
                ),
            ));

            $this->assign('_list', $list);
            $this->assign('info', $info);
            $this->meta_title = '划分活动';
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
                $this->forbid('mtype', $map);
                break;
            case 'resume':
                $this->resume('mtype', $map);
                break;
            case 'delete':
                $this->delete('mtype', $map);
                break;
            default:
                $this->error('参数非法');
        }
    }
}