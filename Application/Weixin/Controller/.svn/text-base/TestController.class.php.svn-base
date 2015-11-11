<?php
/**
 * Author: NanQi
 * Date: 20150605 15:54
 */
namespace Weixin\Controller;
use Think\Controller;

class TestController extends Controller{

    public function mediaList(){
        $medias = D('Basic')->getMediaList();

        echo $medias;
    }

    public function imageList() {
        $medias = D('Basic')->getImageList();

        echo $medias; 
    }

    public function getTempResource($mediaID) {
        $media = D('Resource')->getTempResource($mediaID);

        $result = D('Test', 'Logic')->upload($media);
        if($result){
            $key = $result['key'];

            $url = 'http://www.my2space.com/Test/addResource';

            $data = array(
                'user_id' => '107',
                'parent_id' => '2398',
                'is_folder' => '0',
                'sort' => '',
                'rname' => $key,
                'rdesc' => '',
                'rsize' => 1,
                'mime_type' => 'video/mp4',
                'rext' => 'mp4',
                'key_orignal' => $key,
                'key_preview' => $key,
                'key_thumb' => $key.'?vframe/jpg/offset/1/w/55/h/55',
                'is_processing' => 0,
                'persistent_id' => '',
                'bucket' => 'space-test',
                'charges' => 0,
                'create_time' => time()
            );

            $para = json_encode($data);

            $result = $this->curl_post($url, $data);
            echo $result;
        }
    }

    public function send_new_enroll() {
        $template = D('Template');

        $activity = D('QixiActivity')->find('100040');

        $res = $template->new_enroll('oVe75s2v_gh8E_ed5-MS57NVVXhg', $activity['nickname'], $activity['phone_number'], $activity['city']);

        dump($res);
    }

    public function send_ribao_notify() {
        $res = D('Template')->ribao_notify();
    }

    public function tounix(){
        dump(tounix('20150613010101'));
    }

    public function build() {
        $no = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 15), 1))), 0, 10);
        dump($no);
    }

    public function send() {
        $res = D('Redpacket', 'Pay')->send();
        dump($res);
    }

    /**
     * 发起HTTPS请求
     */
    function curl_post($url,$data,$header = null,$post=1) {
        //初始化curl
        $ch = curl_init();
        //参数设置
        $res= curl_setopt ($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt ($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, $post);
        if($post)
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        $result = curl_exec ($ch);
        //连接失败
        if($result == FALSE){
            $result = "error";
        }

        curl_close($ch);
        return $result;
    }
}