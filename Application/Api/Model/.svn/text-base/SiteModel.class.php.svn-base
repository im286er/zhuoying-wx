<?php
/**
 * Created by PhpStorm.
 * User: 元凯
 * Date: 2015/8/12
 * Time: 15:05
 */

namespace Api\Model;

use Think\Model\RelationModel;

class SiteModel extends RelationModel
{
    protected $_link = array(
        'images' => array(
            'mapping_type' => self::HAS_MANY,
            'class_name' => 'SiteImages',
            'foreign_key' => 'sid',
            'mapping_fields' => 'url',
            'parent_key' => 'id',
        ),
        'user' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'user',
            'foreign_key' => 'uid',
            'as_fields' => 'avatar,nickname',
        )
    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('status', 1, self::MODEL_INSERT, 'string'),
        array('createtime', 'time', self::MODEL_INSERT, 'function'),
        array('last_modify_time', 'time', self::MODEL_BOTH, 'function'),
    );

    public function addSite($data) {

        if ($this->create($data)) {
            $sid=$this->add();

            $content=$data["images"];

            for ($i=0; $i < count($content); $i++) { 
                $content[$i]['sid'] = $sid;
            }

            D("SiteImages")->addAll($content);

            return $sid;
        }

        return false;
    }

    public function editSite($data) {

        if ($this->create($data)) {

            $flg = $this->save();

            $content=$data["images"];

            D('SiteImages')->where("sid = %d", $data['id'])->delete();

            for ($i=0; $i < count($content); $i++) { 
                $content[$i]['sid'] = $data['id'];
            }

            D("SiteImages")->addAll($content);

            return $flg;
        }

        return false;
    }
}