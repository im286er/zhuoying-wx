<?php
/**
 * Created by PhpStorm.
 * User: 元凯
 * Date: 2015/8/12
 * Time: 9:52
 */
namespace Api\Model;

use Think\Model\RelationModel;

class ActivityModel extends RelationModel {

    protected $_link = array(
        'content' => array(
            'mapping_type' => self::HAS_MANY,
            'class_name' => 'ActivityContent',
            'foreign_key' => 'aid',
            'mapping_fields' => 'picture, text',
            'parent_key' => 'id',
        ),

        'site' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'site',
            'foreign_key' => 'sid',
            'as_fields' => 'sitename,latitude,longitude,address,address_location,upper:site_upper',
        ),

        'host_user' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'user',
            'foreign_key' => 'uid',
            'as_fields' => 'avatar,nickname',
        ),
    );

    /* 自动完成规则 */
    protected $_auto = array(
        // array('astatus', 1, self::MODEL_INSERT, 'string'),
        array('createtime', 'time', self::MODEL_INSERT, 'function'),
        array('last_modify_time', 'time', self::MODEL_BOTH, 'function'),
    );


    public function addActivity($data) {
        $data['astatus'] = 1;

        $sid = $data['sid'];

        $site = D('Site')->cache('site_base_'.$sid)->find($sid);
        $uid = $data['uid'];
        if ($site['uid'] == $uid) {
            $data['astatus'] = 2;
        }

        //24小时外的活动需要提醒
        if (time() < $data['starttime'] - 3600 * 24) {
            $data['isreminder'] = '1'; 
        }

        if ($data['money'] <= 100) {

        }
        else if ($data['money'] <= 5000) {
            $data['money'] = $data['money'] + 100;
        }
        else {
            $data['money'] = $data['money'] + 200;
        }
        
        if ($this->create($data)) {
            $aid=$this->add();

            $content=$data["content"];

            for ($i=0; $i < count($content); $i++) { 
                $content[$i]['aid'] = $aid;
            }

            D("ActivityContent")->addAll($content);

            $city = $data['city'];

            //大型活动往关系表中插入数据
            $mids = $data['mids'];
            if ($mids && $data['atype'] == '2') {

                $midList = array();
                $allData = array();

                for ($i=0; $i < count($mids); $i++) { 
                    $allData[] = array(
                        'aid' => $aid,
                        'mid' => $mids[$i],
                    );

                    $midList[] = $mids[$i];
                }

                D("MovieActivity")->addAll($allData);

                D('MovieActivityCount')->batchAddActivityCount($midList, $city);
                
                D('MtypeActivityCount')->batchAddActivityCount($midList, $city);
            }
            else {
                $mid = $data['mid'];

                if ($mid) {
                    D("MovieActivity")->add(array(
                        'aid' => $aid,
                        'mid' => $mid,
                    ));

                    $movie = D('Movie')->cache('movie_base_'.$mid, 3600 * 24)->find($mid);

                    D('MovieActivityCount')->addActivityCount($mid, $city);

                    $subject = $movie['subject'];

                    D('MtypeActivityCount')->addActivityCount($subject, $city);
                }
                else {

                    D('MtypeActivityCount')->addUserActivityCount($uid, $city);
                }
            }

            if ($site['uid'] != $uid) {
                D('Push', 'Logic')->reminderUser($site['uid'], 1, '有一个新的活动申请你的场地，请注意查看');
            }

            return $aid;
        }

        $this->retError('添加活动失败');
    }

    public function addLargeActivity($data) {
        
    }

    public function editActivity($data) {
        $data['astatus'] = 1;

        $sid = $data['sid'];

        $site = D('Site')->cache('site_base_'.$sid)->find($sid);
        if ($site['uid'] == $data['uid']) {
            $data['astatus'] = 2;
        }

        //24小时外的活动需要提醒
        if (time() < $data['starttime'] - 3600 * 24) {
            $data['isreminder'] = '1'; 
        }

        if ($data['money'] <= 100) {

        }
        else if ($data['money'] <= 5000) {
            $data['money'] = $data['money'] + 100;
        }
        else {
            $data['money'] = $data['money'] + 200;
        }

        if ($this->create($data)) {

            $flg = $this->save();

            $content=$data["content"];

            D('ActivityContent')->where("aid = %d", $data['id'])->delete();

            for ($i=0; $i < count($content); $i++) { 
                $content[$i]['aid'] = $data['id'];
            }

            D("ActivityContent")->addAll($content);

            return $flg;
        }

        $this->retError('修改活动失败');
    }
}