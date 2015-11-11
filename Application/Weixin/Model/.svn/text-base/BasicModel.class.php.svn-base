<?php
/**
 * Author: NanQi
 * Date: 20150604 11:19
 */
namespace Weixin\Model;

class BasicModel extends BaseModel {

    public function getMediaList(){
        $url = "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=".$this->access_token;

        $data = array(
            'type' => 'news',
            'offset' => 0,
            'count' => 20,
        );

        $res = $this->https_request($url, json_encode($data));
        return $res;
    }

    public function getImageList() {
       $url = "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=".$this->access_token;

        $data = array(
            'type' => 'image',
            'offset' => 0,
            'count' => 20,
        );

        $res = $this->https_request($url, json_encode($data));
        return $res; 
    }
}