<?php
/**
 * Created by PhpStorm.
 * User: 元凯
 * Date: 2015/8/12
 * Time: 15:05
 */

namespace Api\Controller;
use Think\Controller;
use Think\Model;

class SiteController extends Controller{
    /**
     * 提供场地
     * @param uid int 用户ID
     * @param upper int 人数上限
     * @param city string 城市名称
     * @param sitename string 场地名称
     * @param money float 人均费用
     * @param phone string 联系方式
     * @param longitude float 经度
     * @param latitude float 纬度
     * @param bargain boolean 是否议价
     * @param status int 状态
     * @param images string 场地照片
     * @return JSON 成功/失败
     */
    public function addSite(){

        $Site=D("Site");
        $Site->validation(array(
            array('uid', 'require', '用户不能为空', Model::MUST_VALIDATE, 'regex'),
            array('upper', 'require', '人数上限不能为空', Model::MUST_VALIDATE, 'regex'),
            array('upper', '1,9999', '人数上限不正确', Model::MUST_VALIDATE, 'between'),
            array('sitename', 'require', '场地名称不能为空', Model::MUST_VALIDATE, 'regex'),
            array('money', 'require', '人均费用不能为空', Model::MUST_VALIDATE, 'regex'),
            array('money', 'number', '人均费用不正确', Model::MUST_VALIDATE, 'regex'),
            array('phone', 'require', '联系方式不能为空', Model::MUST_VALIDATE, 'regex'),
            array('longitude', 'require', '经度不能为空', Model::MUST_VALIDATE, 'regex'),
            array('latitude', 'require', '纬度不能为空', Model::MUST_VALIDATE, 'regex'),
            array('city', 'require', '城市不能为空', Model::MUST_VALIDATE, 'regex'),
            array('usetime', 'require', '使用时间不能为空', Model::MUST_VALIDATE, 'regex'),
            // array('bargain', 'require', '是否议价不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $data=I("");

        $images=$data['images'];

        if(!$images){
            $this->retError("至少添加一张图片!");
        }

        /*if (I('money') == 0) {
            $this->retError("人均费用不能为0");
        }*/

        $flg = $Site->addSite($data);

        if ($flg) {
            $this->retSuccess("场地添加成功!");
        }
        else {
            $this->retSuccess("场地添加失败!");
        }

    }

    public function editSite() {
        $site=D("Site"); 
        $site->validation(array(
            array('id', 'require', '场地ID不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $data=I("");

        $images=$data['images'];

        if(!$images){
            $this->retError("至少添加一张图片!");
        }

        /*if (I('money') == 0) {
            $this->retError("人均费用不能为0");
        }*/

        $flg = $site->editSite($data);

        if ($flg) {
            S('site_base_'.I('id'), $data);

            $this->retSuccess("场地修改成功!");
        }
        else {
            $this->retSuccess("场地修改失败!");
        }
    }
    /**
     * 获取场地列表
     * @param city string 城市名称
     * @return JSON 成功/失败
     */
    public function getSite(){
        $Site=D("Site");

        $map['status'] = array('gt', -1);
        if(I('city')){
            $map['city']=I('city');
        }
        if(I('sitename')){
            $map['sitename']=I('sitename');
        }
        if(I('isbargain')){
            $map['isbargain']=I('isbargain');
        }
        if(I('uid')){
            $map['uid']=I('uid');
        }

        $longitude = I('longitude');
        $latitude = I('latitude');

        $order = "";

        if ($longitude && $latitude) {
            $order = "ACOS(SIN(($latitude * 3.1415) / 180 ) *SIN((latitude * 3.1415) / 180 ) + COS(($latitude * 3.1415) / 180 ) * COS((latitude * 3.1415) / 180 ) *COS(($longitude * 3.1415) / 180 - (longitude * 3.1415) / 180 ) ) * 6380 asc";
        }

        $list=$Site->where($map)->order($order)->relation('images')->select();

        if(!$list){
            $this->retError("没有您要的场地!");
        }

        $this->retSuccess($list);
    }

    public function getSiteDetail(){
        $Site=D("Site");
        $Site->validation(array(
            array('sid', 'require', '场地ID不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $sid=I('sid');

        $list=$Site->relation(true)->find($sid);

        $this->retSuccess($list);
    }
}