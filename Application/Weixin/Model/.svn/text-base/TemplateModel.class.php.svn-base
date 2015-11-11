<?php
namespace Weixin\Model;

class TemplateModel extends BaseModel {
    public function new_enroll($touser, $nickname, $activity_name, $orderno, $start_time, $address) {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->access_token; 

        $time = date('Y-m-d H:i', $start_time);

        $data = array(
            'touser' => "$touser",
            'template_id' => 'ZrIaGzrMi792Pl5AOGPyoGhab7USzaint0-4G19b8iw',
            'url' => 'http://weixin.myline.cc/weixin/user/qrcode',
            'topcolor' => "#FF0000",
            'data' => array(
                'first' => array(
                    'value' => "报名成功，检票时点此出示二维码",
                    'color' => '#173177'
                ),
                'keyword1' => array(
                    'value' => $nickname,
                    'color' => '#173177'
                ),
                'keyword2' => array(
                    'value' => $activity_name,
                    'color' => '#173177'
                ),
                'keyword3' => array(
                    'value' => $time,
                    'color' => '#173177'
                ),
                'keyword4' => array(
                    'value' => $orderno,
                    'color' => '#173177'
                ),
                'remark' => array(
                    'value' => "地点：$address",
                    'color' => '#173177'
                )
            )
        );

        $res = $this->https_request($url, json_encode($data));
        return $res;
    }

    public function new_enroll2($touser, $nickname, $activity_name, $orderno, $start_time, $end_time, $address, $aid) {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->access_token; 

        $start_time = date('Y-m-d H:i', $start_time);
        $end_time = date('Y-m-d H:i', $end_time);

        $data = array(
            'touser' => "$touser",
            'template_id' => 'ZrIaGzrMi792Pl5AOGPyoGhab7USzaint0-4G19b8iw',
            'url' => "http://weixin.myline.cc/weixin/user/coupons?aid=$aid",
            'topcolor' => "#FF0000",
            'data' => array(
                'first' => array(
                    'value' => "报名成功，消费时点此出示优惠券",
                    'color' => '#173177'
                ),
                'keyword1' => array(
                    'value' => $nickname,
                    'color' => '#173177'
                ),
                'keyword2' => array(
                    'value' => $activity_name,
                    'color' => '#173177'
                ),
                'keyword3' => array(
                    'value' => "$start_time 至 $end_time",
                    'color' => '#173177'
                ),
                'keyword4' => array(
                    'value' => $orderno,
                    'color' => '#173177'
                ),
                'remark' => array(
                    'value' => "地点：$address",
                    'color' => '#173177'
                )
            )
        );

        $res = $this->https_request($url, json_encode($data));
        return $res;
    }

    public function ribao($touser) {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->access_token; 

        $data = array(
            'touser' => $touser,
            'template_id' => 'MFJSdIs0f5Z07ANF0dxAYlX6N_NuJB2XSYYmPseHpdo',
            'url' => '',
            'topcolor' => "#FF0000",
            'data' => array(
                'first' => array(
                    'value' => '你好，请检查是否忘记写日报',
                    'color' => '#173177'
                ),
                'keyword1' => array(
                    'value' => '填写日报',
                    'color' => '#173177'
                ),
                'keyword2' => array(
                    'value' => '友情提醒',
                    'color' => '#173177'
                ),
                'remark' => array(
                    'value' => '此功能每天19点自动提醒，不想接到提醒，请联系工作人员或取消关注',
                    'color' => '#173177'
                )
            )
        );

        $res = $this->https_request($url, json_encode($data));
        return $res; 
    }

    public function ribao_notify() {
        $openid_list = array(
            'oVe75s2v_gh8E_ed5-MS57NVVXhg',//南琦
            'oVe75s5LiboHTXULg7sJ0pbSD7a4',//徐鸿艳
            'oVe75s0I8B7y8wBDPJwDECjq0m2o',//谢瑞明
            'oVe75szWU7qYi8fxrJwGCRZ31238',//朱晨通
            'oVe75s0UvIzRHzbHD6fHWt1c3i7A',//辛来
        );

        foreach ($openid_list as $openid) {
            $this->ribao($openid);
        }
    }
}