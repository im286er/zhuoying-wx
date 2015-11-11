<?php
/**
 * Created by PhpStorm.
 * User: 元凯
 * Date: 2015/8/18
 * Time: 17:00
 */

namespace Api\Model;

use Think\Model\RelationModel;

class DeviceModel extends RelationModel{
    /**
     * @author : NanQi
     * @date   : 20150423 10:18
     *
     * @desc     绑定注册用户,即给注册ID设置用户ID
     * @param    string $deviceid 注册ID
     * @param    string $uid 用户ID
     * @return   bool
     */
    public function bindDevice($deviceid, $uid){
        $data = array(
            'uid' => $uid,
            'deviceid' => $deviceid,
            'is_open' => 1
        );

        if($this->create($data)){
            $this->where("deviceid = '$deviceid' or uid = '$uid'")->delete();
            $this->add();

            S('device_'.$uid, $data);
        } else {

            return $this->retError();
        }
    }
}