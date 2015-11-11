<?php

namespace Api\Model;

use Think\Model;

class MovieActivityCountModel extends Model {

    public function addActivityCount($mid, $city) {
        $now = time();
        $row = M()->execute("UPDATE t_movie_activity_count set activity_total_count = activity_total_count + 1, activity_incomplete_count = activity_incomplete_count + 1, last_modify_time = $now where mid = '$mid' and city = '$city'");

        if (!$row) {
            $data = array(
                'mid' => $mid,
                'city' => $city,
                'activity_total_count' => 1,
                'activity_incomplete_count' => 1,
                'last_modify_time' => $now,
            );

            if ($this->create($data)) {
                return $this->add();
            }
        }

        return $row;
    }

    public function batchAddActivityCount($mids, $city) {
        $now = time();

        foreach ($mids as $mid) {

            $row = M()->execute("UPDATE t_movie_activity_count set activity_total_count = activity_total_count + 1, activity_incomplete_count = activity_incomplete_count + 1, last_modify_time = $now where mid = '$mid' and city = '$city'");

            if (!$row) {
                $data = array(
                    'mid' => $mid,
                    'city' => $city,
                    'activity_total_count' => 1,
                    'activity_incomplete_count' => 1,
                    'last_modify_time' => $now,
                );

                if ($this->create($data)) {
                    $this->add();
                }
            }
        }

        return true;

        // $midList = implode(',', array_unique($mids));

        // $now = time();
        // $row = M()->execute("UPDATE t_movie_activity_count set activity_total_count = activity_total_count + 1, activity_incomplete_count = activity_incomplete_count + 1, last_modify_time = $now where mid in ($midList) and city = '$city'");

        // if (count($row) < count($mids)) {
        //     foreach ($mids as $mid) {
        //         $data = array(
        //             'mid' => $mid,
        //             'city' => $city,
        //             'activity_total_count' => 1,
        //             'activity_incomplete_count' => 1,
        //             'last_modify_time' => $now,
        //         );

        //         if ($this->create($data)) {
        //             return $this->add();
        //         }
        //     }
        // }
    }

    public function subActivityCount() {
        
    }
}