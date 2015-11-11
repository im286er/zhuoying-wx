<?php
/**
 * Author: NanQi
 * Date: 20150511 11:37
 */
namespace Weixin\Logic;
use Think\Upload\Driver\Qiniu\QiniuStorage;

class TestLogic extends BaseLogic {

    function __construct() {

        $config = array(
            'accessKey'=>'hFFFrSwy66-vmtrYdPpLmwt7sFO_AoZFIy2F77f0',
            'secretKey'=>'9SkDzUhWQ6tiQFCLBMesvyo3BLa-ugideVZGTvR6',
            'bucket'=>'space-test',
            'domain'=>'7xjntg.com2.z0.glb.qiniucdn.com'
            );
        $this->qiniu = new QiniuStorage($config);
    }

    public function upload($fileBody) {
        $file = array(
            'name' => 'file',
            'fileName' => 'wx_'.buildOrderNo().'.mp4',
            'fileBody' => $fileBody
        );
        $config = array();
        $result = $this->qiniu->upload($config, $file);

        return $result;
    }
}