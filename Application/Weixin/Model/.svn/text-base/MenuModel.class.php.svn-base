<?php
/**
 * Author: NanQi
 * Date: 20150604 11:09
 */
namespace Weixin\Model;

class MenuModel extends BaseModel {

//    //获取关注者列表
//    public function get_user_list($next_openid = NULL)
//    {
//        $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$this->access_token."&next_openid=".$next_openid;
//        $res = $this->https_request($url);
//        return json_decode($res, true);
//    }
//
//    //获取用户基本信息
//    public function get_user_info($openid)
//    {
//        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$this->access_token."&openid=".$openid."&lang=zh_CN";
//        $res = $this->https_request($url);
//        return json_decode($res, true);
//    }

    //创建菜单
    public function create_menu($data)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this->access_token;
        $res = $this->https_request($url, $data);
        return json_decode($res, true);
    }

//    //发送客服消息，已实现发送文本，其他类型可扩展
//    public function send_custom_message($touser, $type, $data)
//    {
//        $msg = array('touser' =>$touser);
//        switch($type)
//        {
//            case 'text':
//                $msg['msgtype'] = 'text';
//                $msg['text']    = array('content'=> urlencode($data));
//                break;
//        }
//        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$this->access_token;
//        return $this->https_request($url, urldecode(json_encode($msg)));
//    }
//
//    //生成参数二维码
//    public function create_qrcode($scene_type, $scene_id)
//    {
//        switch($scene_type)
//        {
//            case 'QR_LIMIT_SCENE': //永久
//                $data = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": '.$scene_id.'}}}';
//                break;
//            case 'QR_SCENE':       //临时
//                $data = '{"expire_seconds": 1800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": '.$scene_id.'}}}';
//                break;
//        }
//        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$this->access_token;
//        $res = $this->https_request($url, $data);
//        $result = json_decode($res, true);
//        return "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($result["ticket"]);
//    }
//
//    //创建分组
//    public function create_group($name)
//    {
//        $data = '{"group": {"name": "'.$name.'"}}';
//        $url = "https://api.weixin.qq.com/cgi-bin/groups/create?access_token=".$this->access_token;
//        $res = $this->https_request($url, $data);
//        return json_decode($res, true);
//    }
//
//    //移动用户分组
//    public function update_group($openid, $to_groupid)
//    {
//        $data = '{"openid":"'.$openid.'","to_groupid":'.$to_groupid.'}';
//        $url = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=".$this->access_token;
//        $res = $this->https_request($url, $data);
//        return json_decode($res, true);
//    }
//
//    //上传多媒体文件
//    public function upload_media($type, $file)
//    {
//        $data = array("media"  => "@".dirname(__FILE__).'\\'.$file);
//        $url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=".$this->access_token."&type=".$type;
//        $res = $this->https_request($url, $data);
//        return json_decode($res, true);
//    }

}