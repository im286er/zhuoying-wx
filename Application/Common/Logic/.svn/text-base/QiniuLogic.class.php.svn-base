<?php
/**
 * Author: NanQi
 * Date: 20150512 14:47
 */
namespace Common\Logic;

use Think\Upload\Driver\Qiniu\QiniuStorage;

class QiniuLogic extends BaseLogic {
    protected $qiniu;

    public function _initialize(){
        $config = array(
            'accessKey'=>'hFFFrSwy66-vmtrYdPpLmwt7sFO_AoZFIy2F77f0',
            'secretKey'=>'9SkDzUhWQ6tiQFCLBMesvyo3BLa-ugideVZGTvR6',
            'bucket'=>'mylineapp',
            'domain'=>'7xii7q.com2.z0.glb.qiniucdn.com'
        );
        $this->qiniu = new QiniuStorage($config);
        parent:: _initialize();
    }

    public function upload($fileName, $fileContent){
        $file = array(
            'name'=>'file',
            'fileName'=>$fileName,
            'fileBody'=>base64_decode($fileContent),
        );
        $config = array();
        $result = $this->qiniu->upload($config, $file);
        if($result){


            return $this->qiniu->downLink($result['key']);
        }else{

            $this->retError($this->qiniu->error.'-'.$this->qiniu->errorStr);
        }
    }
}