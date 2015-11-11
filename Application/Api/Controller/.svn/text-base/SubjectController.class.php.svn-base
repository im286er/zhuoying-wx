<?php

namespace Api\Controller;
use Think\Controller;
use Think\Model;

class SubjectController extends Controller{
    //获取主题列表
    public function get_list() {
        $uid = I('uid');
        if (!$uid) $uid = 0;

        $city = I('city');
        if (!$city) $city = '无城市';

        $list = M()->query("SELECT mt.id, mt.title, mt.subtitle, mt.url, count(ma.id) as activity_count FROM t_mtype mt left join t_mtype_activity ma on mt.id = ma.tid where mt.category = 2 AND mt.status > 0 AND mt.url <> '' group by mt.id, mt.title, mt.subtitle, mt.url
                            UNION
                            SELECT t1.id, t1.title, t1.subtitle, t1.url, case when t2.activity_total_count is NULL then '0' else t2.activity_total_count end activity_count FROM (
                            SELECT id, title, subtitle, url FROM t_mtype WHERE category = 1 AND status > 0 AND url <> '' AND user_id in (select uid from t_site where city = '$city')
                            UNION
                            SELECT id, title, subtitle, url FROM t_mtype t1 WHERE EXISTS (SELECT tid FROM t_user_mtype t2 WHERE t2.uid = $uid AND t2.tid = t1.id) AND status > 0 AND category = 0 AND url <> ''
                            UNION
                            SELECT id, title, subtitle, url FROM t_mtype t1 WHERE NOT EXISTS (SELECT tid FROM t_user_mtype t2 WHERE t2.uid = $uid AND t2.tid = t1.id) AND status > 0 AND category = 0 AND url <> '') t1 LEFT JOIN 
                            t_mtype_activity_count t2 ON t1.id = t2.tid AND t2.city = '$city'
                            LIMIT 10");

        $this->retSuccess($list);
    }
}