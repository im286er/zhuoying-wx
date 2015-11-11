<?php
/**
 * Author: NanQi
 * Date: 20150604 11:19
 */
namespace Weixin\Model;

class ResourceModel extends BaseModel {

    public function getTempResource($mediaID){
        $url = "http://api.weixin.qq.com/cgi-bin/media/get?access_token=".$this->access_token."&media_id=".$mediaID;
        $res = $this->https_request($url);
        return $res;
    }
}