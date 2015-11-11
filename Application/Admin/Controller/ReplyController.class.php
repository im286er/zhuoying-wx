<?php
/**
 * Author: NanQi
 * Date: 20150504 09:48
 */
namespace Admin\Controller;
class ReplyController extends AdminController {
    public function index() {
        $name = I('key');
        $map['status'] = array('gt', -1);

        if (is_numeric($name)) {
            $map['id|key'] = array(intval($name), array('like', '%' . $name . '%'), '_multi' => true);
        } else {
            $map['key'] = array('like', '%' . (string)$name . '%');
        }
        $list = $this->lists('WeixinReply', $map, 'createtime desc');
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
            $movie = D('WeixinReply');
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
            $movie = D('WeixinReply');
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
            $info = M('WeixinReply')->field(true)->find($id);
            if(false === $info){
                $this->error('获取配置信息错误');
            }

            $this->assign('info', $info);
            $this->meta_title = '编辑配置';
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
                $this->forbid('WeixinReply', $map);
                break;
            case 'resume':
                $this->resume('WeixinReply', $map);
                break;
            case 'delete':
                $this->delete('WeixinReply', $map);
                break;
            default:
                $this->error('参数非法');
        }
    }
}