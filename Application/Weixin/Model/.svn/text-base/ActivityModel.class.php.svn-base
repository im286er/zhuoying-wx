<?php
/**
 * Created by PhpStorm.
 * User: 元凯
 * Date: 2015/8/12
 * Time: 9:52
 */
namespace Weixin\Model;

use Think\Model\RelationModel;

class ActivityModel extends RelationModel {

    protected $_link = array(
        'content' => array(
            'mapping_type' => self::HAS_MANY,
            'class_name' => 'ActivityContent',
            'foreign_key' => 'aid',
            'mapping_fields' => 'picture',
            'parent_key' => 'id',
        ),

        'site' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'site',
            'foreign_key' => 'sid',
            'as_fields' => 'latitude,longitude,sitename,address,upper:site_upper',
        ),

        'movie' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'movie',
            'foreign_key' => 'mid',
            'as_fields' => 'title:movie_title',
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
        array('astatus', 1, self::MODEL_INSERT, 'string'),
        array('createtime', 'time', self::MODEL_INSERT, 'function'),
        array('last_modify_time', 'time', self::MODEL_BOTH, 'function'),
    );


    public function addActivity($data) {

        //24小时外的活动需要提醒
        if (time() < $data['starttime'] - 3600 * 24) {
            $data['isreminder'] = '1'; 
        }
        
        if ($this->create($data)) {
            $aid=$this->add();

            $content=$data["content"];

            for ($i=0; $i < count($content); $i++) { 
                $content[$i]['aid'] = $aid;
            }

            D("ActivityContent")->addAll($content);

            $mid = $data['mid'];

            $movie = D('Movie')->cache('movie_base_'.$mid, 3600 * 24)->find($mid);

            $city = $data['city'];

            D('MovieActivityCount')->addActivityCount($mid, $city);

            //M()->execute("update t_movie set activity_total_count = activity_total_count + 1, activity_incomplete_count = activity_incomplete_count + 1 where id = $mid");

            $subject = $movie['subject'];

            D('MtypeActivityCount')->addActivityCount($subject, $city);

            //M()->execute("update t_mtype set activity_count = activity_count + 1 where FIND_IN_SET(id, '$subject')");

            return $aid;
        }

        $this->retError('添加活动失败');
    }

    public function editActivity($data) {
        $data['astatus'] = 1;

        //24小时外的活动需要提醒
        if (time() < $data['starttime'] - 3600 * 24) {
            $data['isreminder'] = '1'; 
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