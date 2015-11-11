<?php

namespace Api\Model;

use Think\Model;

class MtypeActivityCountModel extends Model {

    public function addActivityCount($subject, $city) {
        $now = time();
        $rows = M()->execute("UPDATE t_mtype_activity_count set activity_total_count = activity_total_count + 1, last_modify_time = $now where FIND_IN_SET(tid, '$subject') and city = '$city'");

        //TODO 这里有问题,如果电影所属主题会有多个,且在这里出现交叉情况,即其中有一部分已添加数据,一部分没有添加,这里则会漏掉其他主题
        if (!$rows) {
            $subList = explode(',', $subject);

            $data = array();

            foreach ($subList as $sid) {
                $data[] = array(
                    'tid' => $sid,
                    'city' => $city,
                    'activity_total_count' => 1,
                    'last_modify_time' => $now,
                );
            }
            
            return $this->addAll($data);
        }

        return $rows;
    }

    public function batchAddActivityCount($mids, $city) {

        foreach ($mids as $mid) {

            $movie = D('Movie')->cache('movie_base_'.$mid, 3600 * 24)->find($mid);

            $subject = $movie['subject'];
            
            $now = time();
            $rows = M()->execute("UPDATE t_mtype_activity_count set activity_total_count = activity_total_count + 1, last_modify_time = $now where FIND_IN_SET(tid, '$subject') and city = '$city'");

            //TODO 这里有问题,如果电影所属主题会有多个,且在这里出现交叉情况,即其中有一部分已添加数据,一部分没有添加,这里则会漏掉其他主题
            if (!$rows) {
                $subList = explode(',', $subject);

                $data = array();

                foreach ($subList as $sid) {
                    $data[] = array(
                        'tid' => $sid,
                        'city' => $city,
                        'activity_total_count' => 1,
                        'last_modify_time' => $now,
                    );
                }
                
                return $this->addAll($data);
            }
        }

        return true;
    }

    public function addUserActivityCount($uid, $city) {
        $now = time();

        $info = M()->query("SELECT id from t_mtype where user_id = '$uid' and category = '1'");
        if (!$info) {
            return false;
        }

        $tid = $info[0]['id'];

        $rows = M()->execute("UPDATE t_mtype_activity_count set activity_total_count = activity_total_count + 1, last_modify_time = $now where tid = '$tid' and city = '$city'");

        if (!$rows) {
            $data = array(
                'tid' => $tid,
                'city' => $city,
                'activity_total_count' => 1,
                'last_modify_time' => $now
            );
            
            return $this->add($data);
        }

        return $rows;
    }

    public function subActivityCount() {
        
    }
}