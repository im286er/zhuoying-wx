<?php
/**
 * Author: NanQi
 * Date: 20150428 09:59
 */
namespace Admin\Controller;
class CinemaController extends AdminController {
    public function Index(){
        $name         =   I('cname');
        $map['status'] = array('gt', -1);

        if(is_numeric($name)){
            $map['id|cname'] =   array(intval($name),array('cname','%'.$name.'%'),'_multi'=>true);
        }else{
            $map['cname']    =   array('like', '%'.(string)$name.'%');
        }
        $list   = $this->lists('Cinema', $map);
        $this->assign('_list', $list);
        $this->display();
    }

    /**
     * @author : NanQi
     * @date   : 20150504 17:36
     *
     * @desc     新建影院
     */
    public function add(){
        if(IS_POST){
            $Cinema = D('Cinema');
            $data = $Cinema->create();
            if($data){
                if($Cinema->add()){
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($Cinema->getError());
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
     * @desc     编辑影院
     * @param    $id
     */

    public function edit($id = 0){
        if(IS_POST){
            $Cinema = D('Cinema');
            $data = $Cinema->create();
            if($data){
                if($Cinema->save()!== false){
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($Cinema->getError());
            }
        } else {
            $info = M('Cinema')->field(true)->find($id);
            if(false === $info){
                $this->error('获取影院信息错误');
            }

            $this->assign('info', $info);
            $this->meta_title = '编辑影院信息';
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
                $this->forbid('Cinema', $map);
                break;
            case 'resume':
                $this->resume('Cinema', $map);
                break;
            case 'delete':
                $this->delete('Cinema', $map);
                break;
            default:
                $this->error('参数非法');
        }
    }

    public function Choose(){
        $this->display();
    }

    public function getList(){

        $data = M('Cinema')->field('id,cname')->select();

        $this->ajaxReturn($data);
    }
}