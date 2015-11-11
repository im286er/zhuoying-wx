<?php
/**
 * Created by PhpStorm.
 * User: 元凯
 * Date: 2015/8/6
 * Time: 13:43
 */
namespace Api\Model;
use Think\Model\RelationModel;
class VersionModel extends RelationModel{
    public function addVersion($vid,$content,$url,$device){
        $data = array(
            'version' => $vid,
            'update_content' => $content,
            'url' => $url,
            'device' => $device,
            'createtime' =>time(),
        );
        if($this->create($data)){
            $this->add();
        }
    }
}