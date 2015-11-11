<?php
/**
 * Author: NanQi
 * Date: 20150422 13:59
 */
namespace Api\Controller;
use Think\Controller;
use Think\Model;
use Think\Upload\Driver\Qiniu\QiniuStorage;

class CronController extends Controller {

    /**
     * @author : NanQi
     * @date   : 20150428 17:22
     *
     * @desc     每天需要执行的一个任务，做系统优化和一些缓存的建立
     * @return   bool
     */
    public function optimization() {
        
    }

    

    function string_format() {       
        $args = func_get_args();     
        if (count($args) == 0) { return '';}     
        if (count($args) == 1) { return $args[0]; }     
        $str = array_shift($args);
        $GLOBALS['OBJ'] = $args;
        $str = preg_replace_callback(
                '/\\\?{([^{}]+)}/', 
                function ($matches) {
                    list($matche, $name) = $matches;
                    if ($matche[0] === '\\') {
                        return substr($matche, 1);
                    }
                    $obj = $GLOBALS['OBJ'];
                    return isset($obj[$name]) ? $obj[$name] : $matche;
                },
                $str
         ); 
        $GLOBALS['OBJ'] = NULL;
        return $str;
    }

    public function buildActivity() {
        $movie = M()->query("SELECT t1.id
FROM t_movie AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM t_movie)-(SELECT MIN(id) FROM t_movie))+(SELECT MIN(id) FROM t_movie)) AS id) AS t2
WHERE t1.id >= t2.id
ORDER BY t1.id LIMIT 1000");

        $config = array(
            'accessKey'=>'hFFFrSwy66-vmtrYdPpLmwt7sFO_AoZFIy2F77f0',
            'secretKey'=>'9SkDzUhWQ6tiQFCLBMesvyo3BLa-ugideVZGTvR6',
            'bucket'=>'mylineapp',
            'domain'=>'7xii7q.com2.z0.glb.qiniucdn.com'
        );
        $qiniu = new QiniuStorage($config);
        $list = $qiniu->getList();

        $list = array_filter($list['items'], function($item) {
            return $item['mimeType'] == 'image/jpeg';
        });

        $sql_format = "INSERT INTO `t_activity` (id, `title`, `city`, `mid`, `uid`, `sid`, `anumber`, `snumber`, `floor`, `upper`, `phone`, `money`, `intro`, `starttime`, `endtime`, `createtime`, `last_modify_time`, `astatus`) VALUES
 ({13}, '测试活动{0}', '西安', '{1}', '{2}', '{3}', 0, 0, {4}, {5}, '{6}', {7}, '测试活动的简介{0}', {8}, {9}, {10}, {11}, {12});\n";

        $sql_format_content = "INSERT INTO `t_activity_content` (`aid`, `picture`) VALUES ({0}, '{1}');\n";

        $sql = '';

        for ($i=1000000; $i < 1000000 + 1000; $i++) { 
            $ran1000 = mt_rand(0, 1000);
            $ran13 = mt_rand(1, 13);
            $ran4 = mt_rand(1, 4);
            $ran6 = mt_rand(1, 6);
            $sql .= $this->string_format($sql_format, $i, $movie[$ran1000]['id'], $ran13, $ran4, $ran4, $ran4 + $ran13, 18220512014 + $i, 3000 + $i, time() + (3600 * 240 / $ran4), time() + (3600 * 240 / $ran4) + 3600 * 3, time(), time(), $ran6, $i + 1);

            for ($j=0; $j < $ran6; $j++) { 
                $ran = mt_rand(1, count($list));

                $sql .= $this->string_format($sql_format_content, $i, $qiniu->downLink($list[$ran]['key']));
            }
        }

        $ret = array('sql' => $sql);
        $this->ajaxReturn($ret);
    }

    public function buildAvatar() {
        $config = array(
            'accessKey'=>'hFFFrSwy66-vmtrYdPpLmwt7sFO_AoZFIy2F77f0',
            'secretKey'=>'9SkDzUhWQ6tiQFCLBMesvyo3BLa-ugideVZGTvR6',
            'bucket'=>'mylineapp',
            'domain'=>'7xii7q.com2.z0.glb.qiniucdn.com'
        );
        $qiniu = new QiniuStorage($config);
        $list = $qiniu->getList(array('prefix' => 'avatar_'));

        $list = array_filter($list['items'], function($item) {
            return $item['mimeType'] == 'image/jpeg';
        });

        $sql_format = "update t_user set avatar = '{0}' where id = {1};\n";

        for ($i=0; $i < 13; $i++) { 
            $ran = mt_rand(1, count($list));
            $sql .= $this->string_format($sql_format, $qiniu->downLink($list[$ran]['key']), $i + 1);
        }

        dump($sql);
    }

    public function buildWish() {
        $movie = M()->query("SELECT id FROM t_movie WHERE (STATUS > -1 AND wish_total_count >0 )");

        $sql_format = "INSERT INTO `t_user_wish` (`uid`, `mid`, `createtime`) VALUES ({0}, '{1}', {2});\n";

        for ($i=0; $i < count($movie); $i++) { 
            $sql .= $this->string_format($sql_format, 22, $movie[$i]['id'], time());
        }

        echo ($sql);
    }

    public function buildSite() {

        $sql_format = "INSERT INTO `t_site` (`id`, `uid`, `sitename`, `address`, `sitetype`, `upper`, `city`, `money`, `phone`, `latitude`, `longitude`, `isbargain`, `intro`, `usetime`, `createtime`, `last_modify_time`, `status`) VALUES ({0}, {1}, '{2}', '{3}', '{4}', {5}, '西安', {6}, '{7}', '{8}', '{9}', 1, '{10}', '{11}', {12}, {13}, 1);\n";

        $config = array(
            'accessKey'=>'hFFFrSwy66-vmtrYdPpLmwt7sFO_AoZFIy2F77f0',
            'secretKey'=>'9SkDzUhWQ6tiQFCLBMesvyo3BLa-ugideVZGTvR6',
            'bucket'=>'mylineapp',
            'domain'=>'7xii7q.com2.z0.glb.qiniucdn.com'
        );
        $qiniu = new QiniuStorage($config);
        $list = $qiniu->getList();

        $list = array_filter($list['items'], function($item) {
            return $item['mimeType'] == 'image/jpeg';
        });

        $sql_format_content = "INSERT INTO `t_site_images` (`sid`, `url`) VALUES ({0}, '{1}');\n";

        for ($i=1000000; $i < 1000000 + 100; $i++) { 
            $ran1000 = mt_rand(0, 1000);
            $ran13 = mt_rand(1, 13);
            $ran4 = mt_rand(1, 4);
            $ran6 = mt_rand(1, 6);

            $latitude = 34 + mt_rand(1, 100000) / 100000;
            $longitude = 108 + mt_rand(1, 100000) / 100000;

            $sql .= $this->string_format($sql_format, $i, $ran13, '测试场地'.$i, '测试地址'.$i, '测试类型'.$ran4, 20 + $ran13, 3000 + $i, 18220512014 + $i, $latitude, $longitude, '测试简介'.$i, '0600,2200,12345', time(), time());

            for ($j=0; $j < $ran6; $j++) { 
                $ran = mt_rand(1, count($list));

                $sql .= $this->string_format($sql_format_content, $i, $qiniu->downLink($list[$ran]['key']));
            }
        }

        $ret = array('sql' => $sql);
        $this->ajaxReturn($ret);
    }

    public function show_db_config() {
        dump(SAE_MYSQL_HOST_M);
        dump(SAE_MYSQL_HOST_S);
        dump(SAE_MYSQL_PORT);
        dump(SAE_MYSQL_DB);
        dump(SAE_MYSQL_USER);
        dump(SAE_MYSQL_PASS);
    }

    public function schedule5() {

        $this->modify_activity_status();
    }

    public function get_page_index() {
        dump(S('movie_page_index'));
    } 

    /**
    *热映中的电影
    */
    public function intheatermovie() {
        //从豆瓣上获取 热映的电影数据            
        $count_40_limit = 40;
        $douban_data = $this->curl_post("https://api.douban.com/v2/movie/in_theaters?apikey=0c0723ad61b8a67024156b082529f350");        
        $count_40_limit = $count_40_limit -1;

        if($douban_data){              
            //去掉重复的数据
            $movie = M('movie');
            $dbmidList = $movie->field('id')->select();

            $subjects = $douban_data['subjects'];
            $idExist = false;
            foreach ($subjects as $subject) {
                $idx = $subject['id'];

                foreach ($dbmidList as $mid) {
                        $db_movie_id = $mid['id'];

                    if($db_movie_id == $idx){
                        //跳出本循环
                        $idExist = true;
                        break;
                    }else{
                        $idExist = false;
                    }
                }                

                if(!$idExist && $count_40_limit>0){
                    //找出从豆瓣上拿到的subject,以及其属性

                    //按照条目ID 获取条目详情
                    $m_detail_url = 'http://api.douban.com/v2/movie/subject/'.$subject['id'].'?apikey=0c0723ad61b8a67024156b082529f350';
                    $m_detail = $this->curl_post($m_detail_url);
                    $count_40_limit = $count_40_limit -1;


                    //数据组装
                    $m_param['id']=$subject['id'];
                    $m_param['title']=$subject['title'];
                    $m_param['original_title']=$subject['original_title'];
                    $m_param['aka']=implode(",",$m_detail['aka']);
                    $m_param['mobile_url']=$subject['alt'];
                    $m_param['rating']=$subject['rating']['average'];
                    $m_param['small_images']=$subject['images']['small'];
                    $m_param['medium_images']=$subject['images']['medium'];
                            //Urls from douban
                            //https://img1.doubanio.com/view/movie_poster_cover/lpst/public/p2276494952.jpg                   
                            //https://img2.doubanio.com/view/movie_poster_cover/lpst/public/p2266145079.jpg
                            //https://img3.doubanio.com/view/movie_poster_cover/lpst/public/p2274029145.jpg
                            //https://img4.douban.com/view/movie_poster_cover/lpst/public/p494268647.jpg

                            //manlian large_image uir is  (we deal it this way (img1 img2 img3)-->img3  img4 -->img4)
                            //http://poster3.myline.cc/view/movie_poster_cover/lpst/public/p2266823371.jpg
                            $mlUrl = null;
                            $ori_big = $subject['images']['large'];   

                            $douban_url_img1 = 's://img1.doubanio.com';
                            $douban_url_img2 = 's://img2.doubanio.com';
                            $douban_url_img3 = 's://img3.doubanio.com';
                            $manlian_rul = '://poster3.myline.cc';

                            $douban_url_img4 = 's://img4.douban.com';
                            $manlian_rul_4 = '://poster4.myline.cc';   

                            $pos = strpos($ori_big, $douban_url_img1);
                            if($pos>0){
                            $mlUrl =str_replace($douban_url_img1,$manlian_rul,$ori_big);
                            }

                            $pos = strpos($ori_big, $douban_url_img2);                     
                            if($pos>0){
                            $mlUrl =str_replace($douban_url_img2,$manlian_rul,$ori_big);
                            }

                            $pos = strpos($ori_big, $douban_url_img3);
                            if($pos>0){                         
                            $mlUrl =str_replace($douban_url_img3,$manlian_rul,$ori_big);
                            }

                            $pos = strpos($ori_big, $douban_url_img4);
                            if($pos>0){                             
                            $mlUrl =str_replace($douban_url_img4,$manlian_rul_4,$ori_big);
                            }   
                    if($mlUrl){
                    $m_param['large_images']=$mlUrl;
                    }else{
                    $m_param['large_images']=$ori_big;
                    }
                    $m_param['subtype']=$subject['subtype'];
                            $str_directors = '';
                            foreach ($subject['directors'] as $value) {
                                $str_directors.=$value['name'].',';
                            }
                            $str_directors = substr($str_directors,0,strlen($str_directors)-1);                            
                    $m_param['directors']= $str_directors;
                            $str = '';
                            foreach ($subject['casts'] as $value) {
                                $str.=$value['name'].',';
                            }
                            $str = substr($str,0,strlen($str)-1); 
                    $m_param['casts']=$str;
                    // $m_param['writers']=$subject[''];                            //manlian data, no need to fill
                    $m_param['year']=$subject['year'];
                    $m_param['genres']=implode(",",$subject['genres']);
                    // $m_param['subject']=$subject[''];                            //manlian data, no need to fill
                    $m_param['countries']=implode(",",$m_detail['countries']);   
                    //         $str_20len = '';
                    //         if($m_detail['summary']){
                    //             if(strlen($m_detail['summary'])>80){
                    //                 $ori_str = $m_detail['summary'];
                    //                 $str_20len = (mb_substr($ori_str,0,80).'...');       //this will cause 30s exception, i do not konw why
                    //             }else{
                    //                 $str_20len = $m_detail['summary'];
                    //             }  
                    //         }
                    // $m_param['intro']=$str_20len;    
                            $str_summary = $m_detail['summary'];                     //lots summary end with ©豆瓣, so we delete it
                            $str_atdouban = '©豆瓣';
                            $pos = strpos($str_summary,$str_atdouban);
                            if($pos>0){
                                $str_summary =str_replace($str_atdouban,' ',$m_detail['summary']);
                            }        
                    $m_param['summary']=$str_summary;
                    $m_param['wish_count']=$m_detail['wish_count'];
                    $m_param['last_modify_time']=''.microtime(true);
                    //$m_param['status']=1;                                           //manlian data, no need to fill
                    // $m_param['talk_count']=$subject[''];                           //manlian data, no need to fill
                    // $m_param['activity_total_count']=$subject[''];                 //manlian data, no need to fill
                    // $m_param['activity_incomplete_count']=$subject[''];            //manlian data, no need to fill
                    //$m_param['wish_total_count']=$m_detail[''];                     //manlian data, no need to fill            
                    // $m_param['wish_incomplete_count']=$subject[''];                //manlian data, no need to fill
                    
                    //DB operation
                    $movie->add($m_param);

                    // dump('one db operation finished');   
                }else{
                    //在数据库中找到了 对于的记录，可以更新记录
                    // dump('one db item already exist, we need do nothing this time '); 
                }  
            }          

            M()->execute("UPDATE t_movie set intro = CONCAT(left(summary, 20), '...') where intro = ''");
        }else{
            // dump("没有从豆瓣上获取到热映电影");
         }    
    } 

    // /**
    // *即将播放的电影
    // */
    // public function schedule_incominginmovie_douban() {
    //     //从豆瓣上获取 即将上映的电影数据
    //      $summary = $this->curl_post("https://api.douban.com/v2/movie/coming_soon?apikey=0c0723ad61b8a67024156b082529f350");
    //      dump($summary);
    // }    

    public function schedule1() {
        $page_index = S('movie_page_index');
        if (!$page_index) {
            $page_index = 1;
        }
        $movieidList = D('movie')->where("summary =  ''")->field('id')->page($page_index.',39')->select();

        //api summery
        foreach ($movieidList as $movie) {
            $movie_id = $movie['id'];
            dump($movie_id);

            $summary = $this->curl_post("https://api.douban.com/v2/movie/subject/$movie_id?apikey=0c0723ad61b8a67024156b082529f350");
            //update database

            D('movie')->where("id = '$movie_id'")->setField('summary', $summary);
        }

        $page_index++;

        S('movie_page_index', $page_index);
    }
    /**
     * 发起HTTPS请求
     */
    function curl_post($url) {
        //初始化curl
        $ch = curl_init();
        //参数设置
        $res= curl_setopt ($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt ($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        $result = curl_exec ($ch);
        curl_close($ch);
        $res = json_decode($result, true);
        return $res;
    }


    function modify_activity_status() {
        $activityList = D('Activity')->field('id,astatus,last_modify_time,anumber,floor,starttime,endtime')
                                     ->where("astatus >= 1 and astatus <= 5 and atype = 0")
                                     ->select();

        $map = array(
            array(
                //场地审核中 超过半小时过期
                'conditions' => function($activity) {
                    return $activity['astatus'] == 1 && time() >= $activity['last_modify_time'] + 3600 / 2;
                },
                'status' => '0'
            ),
            array(
                //活动到点，一切正常，活动状态修改为开始
                'conditions' => function($activity) {
                    return ($activity['astatus'] == 2 || $activity['astatus'] == 3) 
                        && $activity['anumber'] >= $activity['floor'] 
                        && time() >= $activity['starttime'];
                },
                'status' => '4'
            ),
            array(
                //活动到点，未满足活动下限人数，活动流产
                'conditions' => function($activity) {
                    return $activity['astatus'] == 2 
                        && $activity['anumber'] < $activity['floor'] 
                        && time() >= $activity['starttime'];
                },
                'status' => '-3',
                'do' => function($activity) {
                    $aid = $activity['id'];
                    $list = D('Order')->field("orderno,uid,orderprice")->where("aid = '$aid' and paystatus = 1")->select();

                    if (count($list) > 0) {

                        //修改所有账单状态为取消
                        $orderValues = '';

                        array_walk($list, function($a) use (&$orderValues) {
                            $orderValues .= "('".$a['orderno']."', '-1'),";
                        });

                        $orderValues = substr($orderValues, 0, strlen($orderValues) - 1);

                        $sql = "INSERT into t_order (orderno,paystatus) values $orderValues on duplicate key update paystatus=values(paystatus)";

                        setLog('schedule5:modify_order:'.$sql);

                        $flg = M()->execute($sql);

                        //给已付款的账户增加对应金额
                        $userValues = '';

                        array_walk($list, function($a) use (&$userValues) {
                            $userValues .= '('.$a['uid'].', '.$a['orderprice'].'),';
                        });

                        $userValues = substr($userValues, 0, strlen($userValues) - 1);

                        $sql = "INSERT into t_user (id,amount) values $userValues on duplicate key update amount=amount+values(amount)";

                        setLog('schedule5:modify_amount:'.$sql);

                        $flg = M()->execute($sql); 
                    }
                }
            ),
            array(
                //活动到点，但是活动还在筹备中，直接流产
                'conditions' => function($activity) {
                    return ($activity['astatus'] == 0 || $activity['astatus'] == 1)
                        && time() >= $activity['starttime'];
                },
                'status' => '-3'
            ),
            array(
                //活动时间到，活动状态修改为结束
                'conditions' => function($activity) {
                    return $activity['astatus'] == 4
                        && time() >= $activity['endtime'];
                },
                'status' => '5',
                'do' => function($activity) {
                    
                }
            ),
            array(
                //活动结束后48小时，自动到账，活动状态修改为关闭
                'conditions' => function($activity) {
                    return $activity['astatus'] == 5
                        && time() >= $activity['endtime'] + 3600 * 48;
                },
                'status' => '6',
                'do' => function($activity) {

                }
            ),
            array(
                //活动到点前24个小时，且活动成立，给所有参加者和组织者发送推送消息
                'conditions' => function($activity) {
                    return ($activity['astatus'] == 2 || $activity['astatus'] == 3) 
                        && $activity['anumber'] >= $activity['floor'] 
                        && $activity['isreminder'] == '1'
                        && time() >= $activity['starttime'] - 3600 * 24;
                },
                'do' => function($activity) {

                    D('Push', 'Logic')->reminderUser($activity['uid'], 5, '你发起的['.$activity['title'].']活动还有一天就要开始'); 

                    $aid = $activity['id'];

                    $list = M()->query("SELECT t1.deviceid as cid from t_device t1, t_activity_user t2 where t1.uid = t2.uid and t2.aid = $aid");

                    $cids = array();
                    foreach ($list as $value) {
                        $cids[] = $value['uid'];
                    }

                    $sendData = array();
                    $sendData['t'] = 2;
                    $sendData['v'] = 5;
                    $sendData['d'] = array(
                        'content' => '你参与的['.$activity['title'].']活动还有一天就要开始',
                        'sendtime' => time(),
                    );

                    D('Push', 'Logic')->pushMessageToList($cids, '', json_encode($sendData));

                    D('Activity')->where('id = %d', $aid)->setField('isreminder', '0');
                }
            ),
        );

        array_walk($map, function($matche) use ($activityList) {
            $conditions = $matche['conditions'];
            $status = $matche['status'];
            $do = $matche['do'];

            $activityList_filter = array_filter($activityList, $conditions);
            if (count($activityList_filter) > 0) {

                if ($status) {
                    $values = '';

                    array_walk($activityList_filter, function($a) use (&$values, $status) {
                        $values .= '('.$a['id'].', '.$status.'),';
                    });

                    $values = substr($values, 0, strlen($values) - 1);

                    $sql = "INSERT into t_activity (id,astatus) values $values on duplicate key update astatus=values(astatus)";

                    setLog('schedule5:modify_status:'.$sql);

                    $flg = M()->execute($sql);

                    S('activity_status', null);
                }

                if ($do) {
                    array_walk($activityList_filter, function($a) use ($do) {
                        $do($a);
                    });
                }
            }
        });
 
    }
}