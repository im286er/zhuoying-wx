<?php

namespace Weixin\Model;

class UserModel extends BaseModel {
    
    function createOauthUrlForCode($redirectUrl, $scope = 'snsapi_base')
    {
        $redirectUrl = urlencode($redirectUrl);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appid."&redirect_uri=".$redirectUrl."&response_type=code&scope=".$scope."&state=nanqi#wechat_redirect";
    }
  
    public function access_token($code) {
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->appid."&secret=".$this->appsecret."&code=".$code."&grant_type=authorization_code";
        $res = $this->https_request($url);
        return json_decode($res); 
    }

    public function refresh_token($refresh_token) {
        $url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=".$this->appid."&grant_type=refresh_token&refresh_token=".$refresh_token;
        $res = $this->https_request($url);
        return json_decode($res); 
    }

    public function check_token($token, $open_id) {
        $url = "https://api.weixin.qq.com/sns/auth?access_token=".$token."&openid=".$open_id;
        $res = $this->https_request($url);
        return json_decode($res); 
    }

    public function snsapi_userinfo($token, $open_id) {
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$token."&openid=".$open_id."&lang=zh_CN";

        $res = $this->https_request($url);
        return json_decode($res, true); 
    }

    public function public_userinfo($open_id) {
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$this->access_token."&openid=".$open_id."&lang=zh_CN";

        $res = $this->https_request($url);
        return json_decode($res, true); 
    }

    public function get_userinfo_web($code) {
        $res_token = $this->access_token($code);

        $res_check = $this->check_token($res_token->access_token, $res_token->openid);
        if ($res_check->errcode != 0) {
            $res_token = $this->refresh_token($res_token->refresh_token);
        }

        $userinfo = $this->snsapi_userinfo($res_token->access_token, $res_token->openid);
        return $userinfo;
    }

    public function get_userinfo_public($code) {
        $res_token = $this->access_token($code);

        $userinfo = $this->public_userinfo($res_token->openid);

        return $userinfo; 
    }
}