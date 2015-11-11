<?php

namespace Weixin\Controller;
use Think\Controller;
use Think\Model;

class TemplateController extends Controller{
    public function qixi_tickets($activityid) {
        $activity = D('QixiActivity')->find($activityid);
        if (empty($activity)) {
            $this->retError();
        }

        $template = D('Template');

        $template->finish_enroll($activity['open_id'], $activity['nickname'], $activity['id']);
        $template->qixi_tickets_notice($activity['nickname'], $activity['phone_number'], $activity['city']);
    }
}