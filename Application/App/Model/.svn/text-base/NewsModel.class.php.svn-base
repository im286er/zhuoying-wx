<?php

namespace App\Model;
use Think\Model\RelationModel;

class NewsModel extends RelationModel {
    protected $_link = array(
        'movies' => array(
            'mapping_type'          =>  self::MANY_TO_MANY,
            'class_name'            =>  'movie',
            'mapping_name'          =>  'movies',
            'foreign_key'           =>  'nid',
            'relation_foreign_key'  =>  'mid',
            'mapping_fields'        => 'id,mname,alias,ratingstar,publishdate,duration,director,actor,area,mtype,techtype,synopsis,pstate,poster,promo,cntlike,cntdislike',
            //'relation_table'    =>  't_news_movie'
        ),
    );

    public function setMappingFields($fields){
        $this->_link['movies']['mapping_fields'] = $fields;
    }
}
