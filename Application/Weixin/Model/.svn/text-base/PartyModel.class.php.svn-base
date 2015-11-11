<?php
/**
 * Author: NanQi
 * Date: 20150423 15:32
 */
namespace Weixin\Model;
use Think\Model\RelationModel;

class PartyModel extends RelationModel {
    protected $_link = array(
//        'news' => array(
//            'mapping_type'      =>  self::HAS_ONE,
//            'class_name'        =>  'news',
//            'mapping_name'      =>  'news',
//            'foreign_key'       =>  'id',
//            'mapping_key'       =>  'nid',
//            'mapping_fields'    =>  'title,recommend',
//            'as_fields'         =>  'title,recommend',
//        ),

        'fares' => array(
            'mapping_type'      =>  self::HAS_ONE,
            'class_name'        =>  'fares',
            'mapping_name'      =>  'fares',
            'foreign_key'       =>  'id',
            'mapping_key'       =>  'fid',
            'mapping_fields'    =>  'price',
            'as_fields'         =>  'price',
        ),

        'cinema' => array(
            'mapping_type'      =>  self::HAS_ONE,
            'class_name'        =>  'cinema',
            'mapping_name'      =>  'cinema',
            'foreign_key'       =>  'id',
            'mapping_key'       =>  'cid',
            'mapping_fields'    =>  'cname,address',
            'as_fields'         =>  'cname,address',
        ),

        'movies' => array(
            'mapping_type'          =>  self::MANY_TO_MANY,
            'mapping_key'           =>  'nid',
            'class_name'            =>  'movie',
            'mapping_name'          =>  'movies',
            'foreign_key'           =>  'nid',
            'relation_foreign_key'  =>  'mid',
            'relation_table'        =>  't_news_movie',
            'mapping_fields'        =>  'mname,starttime,endtime,poster',
        ),
    );

    /**
     * @author : NanQi
     * @date   : 20150428 13:45
     *
     * @desc     获取所有详细信息
     * @param    Int pid 活动ID
     * @return   model
     */
    public function getFullInfo($pid){

        //TODO 这里暂时使用数据库查询,但是多表关联会影响效率,应该对高频率调用增加缓存或使用NOSQL存储
        return $this->cache('party_getfullinfo_'.$pid)->relation(true)->find($pid);
    }

    /**
     * @author : NanQi
     * @date   : 20150428 14:46
     *
     * @desc     获取活动剩余名额
     * @param    int $pid 活动ID
     * @return  int 剩余名额
     */
    public function getRemainCount($pid){

        $party = $this->find($pid);
        return (int)$party['cntremain'];
    }

    /**
     * @author : NanQi
     * @date   : 20150428 14:58
     *
     * @desc     设置剩余名额
     * @param    int $pid 活动ID
     * @param    int $count 剩余名额
     * @return   bool
     */
    public function setRemainCount($pid, $count){
        
        $data = array(
            'id' => $pid,
            'cntremain' => $count,
        );

        if($this->create($data)) {

            return $this->save();
        } else {

            return $this->retError();
        }
    }
}