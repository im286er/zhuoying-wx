<?php
namespace Weixin\Model;
use Think\Model;

class BaseModel extends Model {

    protected $autoCheckFields = false;

    protected $appid;
    protected $appsecret;
    protected $access_token;

    //构造函数，获取Access Token
    public function _initialize()
    {
        $this->appid = C('WX_APPID');
        $this->appsecret = C('WX_APPSECRET');

        $this->access_token = S('wx_access_token');

        if (empty($this->access_token)){
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appid."&secret=".$this->appsecret;
            $res = $this->https_request($url);
            $result = json_decode($res, true);
            $this->access_token = $result["access_token"];

            S('wx_access_token', $this->access_token, 7200);

            $date = date("Ymd");

            $count = S("token_count_$date");
            if (!$count) {

                S("token_count_$date", '0', 60 * 60 * 24 * 2);
            }
            else {
                $count++;

                if ($count > 100) {
                    setLog("token_count_$date:$count");
                }

                S("token_count_$date", $count, 60 * 60 * 24 * 2);
            }
        }
    }

    protected function https_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
    
}
