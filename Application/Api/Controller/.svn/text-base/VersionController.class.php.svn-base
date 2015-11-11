<?php
/**
 * Created by PhpStorm.
 * User: 元凯
 * Date: 2015/8/6
 * Time: 13:43
 */
namespace Api\Controller;
use Think\Controller;
use Think\Model;
header( 'Access-Control-Allow-Origin:*' );
class VersionController extends Controller{
    public function update(){
        $device=I('device');

        M()->validation(array(
            array('device', 'require', '设备类型不能为空', Model::MUST_VALIDATE, 'regex'),
            array('version', 'require', '当前版本号不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $version=M("Version")->where("device='%s'",$device)->order('createtime desc, version desc')->find();

        $data = array(
            'version' => $version['version']
        );

        $curVersion = I('version');

        if (version_compare($version['version'], $curVersion) > 0) {
            $data['url'] = $version['url'];
        }
        
        $this->retSuccess($data);
    }

    public function addVersion(){

        //$this->retSuccess(I("vid"));

        $Version=D("version");

        /*$Version->validation(array(
            array('vid', 'require', '版本号不能为空', Model::MUST_VALIDATE, 'regex'),
            array('content', 'require', '更新内容不能为空', Model::MUST_VALIDATE, 'regex'),
        ));*/

        $vid=I('vid');
        $device=I('device');
        $content=I('content');
        $url=I('url');
        /*$cnt=$Version->where("version=%d",$vid)->find();
        if($cnt){
            $this->retError("该版本已存在");
        }*/

        if($device==3){
            $Version->addVersion($vid,$content,$url,"android");
            $Version->addVersion($vid,$content,$url,"ios");
        }elseif($device==1){
            $Version->addVersion($vid,$content,$url,"android");
        }elseif($device==2){
            $Version->addVersion($vid,$content,$url,"ios");
        }

        $this->retSuccess("版本添加成功!");
    }
}