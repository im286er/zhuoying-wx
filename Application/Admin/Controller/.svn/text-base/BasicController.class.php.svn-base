<?php
/**
 * Author: NanQi
 * Date: 20150504 12:59
 */
namespace Admin\Controller;
use Think\Model;
class BasicController extends AdminController {

    protected $type = '';

    function _initialize() {
        $this->type = I('get.type');
        if (empty($this->type)) {
        }

        parent::_initialize();
    }

    public function index(){
        $name = I('title');
        $map['status'] = array('gt', 0);

        if (is_numeric($name)) {
            $map['id|title'] = array(intval($name), array('like', '%' . $name . '%'), '_multi' => true);
        } else {
            $map['title'] = array('like', '%' . (string)$name . '%');
        }
        $list = $this->lists($this->type, $map, 'sort');
        if($list) {
            $this->assign('_list',$list);
        }
        $this->assign('type',$this->type);
        $this->meta_title = '基础设置';
        $this->display();
    }


    public function getList(){

        $map['status'] = array('gt', -1);
        $list = $this->lists($this->type, $map, 'sort');

        $this->ajaxReturn($list);
    }

    public function choose(){

        $this->assign('type',$this->type);
        $this->display();
    }

    public function add($valname = ''){

        $this->assign('type',$this->type);

        if(IS_POST){

            if ($valname == null || strlen($valname) > 50) {
                $this->error('数据验证不合法');
            }

            $model = M($this->type);

            if ($model->create() && $model->add()) {
                $this->success('基础信息添加成功', U('index?type='.$this->type));
            }
            else {
                $this->error('基础信息添加失败');
            }
        } else {
            $this->meta_title = '新增基础信息';
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
                $this->forbid($this->type, $map);
                break;
            case 'resume':
                $this->resume($this->type, $map);
                break;
            case 'delete':
                $this->delete($this->type, $map);
                break;
            default:
                $this->error('参数非法');
        }
    }
}