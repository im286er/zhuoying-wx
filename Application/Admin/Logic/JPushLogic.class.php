<?php
/**
 * Author: NanQi
 * Date: 20150417 17:21
 */
namespace Admin\Logic;
require_once 'vendor/autoload.php';
use Think\Model;
use JPush\JPushClient;
use JPush\Model as M;

Class JPushLogic extends Model{
    protected $autoCheckFields = false;

    private $client = null;

    public function __construct() {
        $this->client = new JPushClient(C("JPUSH_APP_KEY"), C("JPUSH_MASTER_SECRET"));
    }

    public function pushNotificationByTags($tags, $msg, $title = null, $extras = null) {
        $result = $this->client->push()
            ->setPlatform(M\all)
            ->setAudience(M\audience(M\tag(explode(',', $tags))))
            ->setNotification(M\notification($msg,
                M\android($msg, $title, null, $extras),
                M\ios($msg, null, null, null, $extras),
                M\winphone($msg, $title, null, $extras)))
            ->send();

        return $result->isOk;
    }

    public function pushMessageByTags($tags, $msg_content, $title = null, $content_type = null, $extras = null){
        $result = $this->client->push()
            ->setPlatform(M\all)
            ->setAudience(M\audience(M\tag(explode(',', $tags))))
            ->setMessage(M\message($msg_content, $title, $content_type, $extras))
            ->send();

        return $result->isOk;
    }

    public function pushNotificationByAlias($alias, $msg, $title = null, $extras = null) {
        $result = $this->client->push()
            ->setPlatform(M\all)
            ->setAudience(M\audience(M\alias(explode(',', $alias))))
            ->setNotification(M\notification($msg,
                M\android($msg, $title, null, $extras),
                M\ios($msg, null, null, null, $extras),
                M\winphone($msg, $title, null, $extras)))
            ->send();

        return $result->isOk;
    }

    public function pushMessageByAlias($alias, $msg_content, $title = null, $content_type = null, $extras = null){

        $result = $this->client->push()
            ->setPlatform(M\all)
            ->setAudience(M\audience(M\alias(explode(',', $alias))))
            ->setMessage(M\message($msg_content, $title, $content_type, $extras))
            ->send();

        return $result->isOk;
    }

    public function pushNotificationAll($msg, $title = null, $extras = null){
        $result = $this->client->push()
            ->setPlatform(M\all)
            ->setAudience(M\all)
            ->setNotification(M\notification($msg,
                M\android($msg, $title, null, $extras),
                M\ios($msg, null, null, null, $extras),
                M\winphone($msg, $title, null, $extras)))
            ->send();
        return $result->isOk;
    }

    public function getDeviceTagAlias($registrationId){
        return $this->client->getDeviceTagAlias($registrationId);
    }

    public function updateDeviceTagAlias($registrationId, $alias = null, $addTags = null, $removeTags = null){
        return $this->client->updateDeviceTagAlias($registrationId, $alias, $addTags, $removeTags)->isOk;
    }

    public function setAlias($registrationId, $alias){
        return $this->client->updateDeviceTagAlias($registrationId, $alias, null, null)->isOk;
    }

    public function AddTags($registrationId, $tags){
        return $this->client->updateDeviceTagAlias($registrationId, null, $tags, null)->isOk;
    }

    public function RemoveTags($registrationId, $tags){
        return $this->client->updateDeviceTagAlias($registrationId, null, null, $tags)->isOk;
    }

    public function removeAlias($registrationId) {
        return $this->client->removeDeviceAlias($registrationId)->isOk;
    }
}