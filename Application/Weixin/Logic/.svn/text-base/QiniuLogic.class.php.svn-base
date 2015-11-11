<?php

namespace Weixin\Logic;
use Think\Upload\Driver\Qiniu\QiniuStorage;

class QiniuLogic extends BaseLogic {

    function __construct() {

        $config = array(
            'accessKey'=>'hFFFrSwy66-vmtrYdPpLmwt7sFO_AoZFIy2F77f0',
            'secretKey'=>'9SkDzUhWQ6tiQFCLBMesvyo3BLa-ugideVZGTvR6',
            'bucket'=>'zhuoying-normal',
            'domain'=>'7xl1ts.com2.z0.glb.qiniucdn.com'
        );

        $this->qiniu = new QiniuStorage($config);
    }

    public function upload($fileBody) {
        $file = array(
            'name' => 'file',
            'fileName' => 'wx_'.buildOrderNo().'.jpg',
            'fileBody' => $fileBody
        );
        $config = array();
        $result = $this->qiniu->upload($config, $file);

        if ($result) {
            $result['downLink'] = $this->qiniu->downLink($result['key']);
        }

        return $result;
    }
}