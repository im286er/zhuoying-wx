<?php
/**
 * Created by PhpStorm.
 * User: 元凯
 * Date: 2015/8/12
 * Time: 9:44
 */

namespace Api\Controller;
use Think\Controller;
use Think\Model;

class ActivityController extends Controller{
    /**
     * 发起活动
     * @param uid new/hot 排序
     * @param mid int 页面数量
     * @param sid int 页面序号
     * @param floor int 下限人数
     * @param upper int 上限人数
     * @param money float 价格
     * @param phone int 联系方式
     * @param intro int 活动介绍
     * @param city string 城市
     * @param title string 活动名称
     * @param content array 内容(文字,图片)
     * @return JSON 成功/失败
     */
    public function launch(){
        $atype = I('atype');
        $data=I("");

        $content=I('content');

        if(!$content){
            $this->retError("至少添加一张活动图片");
        }

        $activity=D("Activity");

        $id = I('id');

        if ($atype == '1') {
            M()->validation(array(   
                array('uid', 'require', '用户ID不能为空', Model::MUST_VALIDATE, 'regex'),
                array('uid', 'integer', '用户ID格式不正确', Model::MUST_VALIDATE, 'regex'),
                array('title', 'require', '活动名称不能为空', Model::MUST_VALIDATE, 'regex'),
                // array('sid', 'require', '场地不能为空', Model::MUST_VALIDATE, 'regex'),
                array('money', 'require', '人均费用不能为空', Model::MUST_VALIDATE, 'regex'),
                array('money', 'number', '人均费用不正确', Model::MUST_VALIDATE, 'regex'),
                array('money_hour', 'require', '小时计费不能为空', Model::MUST_VALIDATE, 'regex'),
                array('money_hour', 'number', '小时计费不正确', Model::MUST_VALIDATE, 'regex'),
                array('phone', 'require', '联系方式不能为空', Model::MUST_VALIDATE, 'regex'),
                array('starttime', 'require', '活动开始时间不能为空', Model::MUST_VALIDATE, 'regex'),
                array('endtime', 'require', '活动结束时间不能为空', Model::MUST_VALIDATE, 'regex'),
                // array('intro', 'require', '活动介绍不能为空', Model::MUST_VALIDATE, 'regex'),
                array('city', 'require', '城市不能为空', Model::MUST_VALIDATE, 'regex'),
            ));

            $uid = I('uid');

            $user = D('User')->cache('user_base_'.$uid, 3600 * 24)->find($uid);
            if ($user['role'] != '2') {
                $this->retError('只有认证了场地提供者的用户才可以发起此类活动');
            }

            $sid = I('sid');
            if ($sid) {
                $site = D('Site')->cache('site_base_'.$sid)->find($sid);
                if ($user['id'] != $site['uid']) {
                    $this->retError('此类活动不能更换场地');
                }
            }
            else {
                $site = D('Site')->where("uid = '$uid'")->find();
                if (!$site) {
                    $this->retError("该用户还没有创建场地");
                }
                else {
                    $data['sid'] = $site['id'];
                }
            }
        }
        elseif ($atype == '2') {
            M()->validation(array(   
                array('uid', 'require', '用户ID不能为空', Model::MUST_VALIDATE, 'regex'),
                array('uid', 'integer', '用户ID格式不正确', Model::MUST_VALIDATE, 'regex'),
                array('title', 'require', '活动名称不能为空', Model::MUST_VALIDATE, 'regex'),
                array('movie_title_list', 'require', '电影标题不能为空', Model::MUST_VALIDATE, 'regex'),
                array('sid', 'require', '场地不能为空', Model::MUST_VALIDATE, 'regex'),
                array('sid', 'integer', '场地格式不正确', Model::MUST_VALIDATE, 'regex'),
                array('money', 'require', '人均费用不能为空', Model::MUST_VALIDATE, 'regex'),
                array('money', 'number', '人均费用不正确', Model::MUST_VALIDATE, 'regex'),
               
                array('phone', 'require', '联系方式不能为空', Model::MUST_VALIDATE, 'regex'),
                array('starttime', 'require', '活动开始时间不能为空', Model::MUST_VALIDATE, 'regex'),
                array('endtime', 'require', '活动结束时间不能为空', Model::MUST_VALIDATE, 'regex'),
                // array('intro', 'require', '活动介绍不能为空', Model::MUST_VALIDATE, 'regex'),
                array('city', 'require', '城市不能为空', Model::MUST_VALIDATE, 'regex'),
            ));

            $mids = I('mids');

            if(!$mids){
                $this->retError("至少添加一个电影");
            }

            // if (!$id) {
            //     $activity->addLargeActivity($data);
            // }
        }
        else {
            M()->validation(array(   
                array('uid', 'require', '用户ID不能为空', Model::MUST_VALIDATE, 'regex'),
                array('uid', 'integer', '用户ID格式不正确', Model::MUST_VALIDATE, 'regex'),
                array('mid', 'require', '心愿电影不能为空', Model::MUST_VALIDATE, 'regex'),
                array('mid', 'integer', '心愿电影格式不正确', Model::MUST_VALIDATE, 'regex'),
                array('title', 'require', '活动名称不能为空', Model::MUST_VALIDATE, 'regex'),
                array('movie_title_list', 'require', '电影标题不能为空', Model::MUST_VALIDATE, 'regex'),
                array('sid', 'require', '场地不能为空', Model::MUST_VALIDATE, 'regex'),
                array('sid', 'integer', '场地格式不正确', Model::MUST_VALIDATE, 'regex'),
                array('floor', 'require', '成团人数不能为空', Model::MUST_VALIDATE, 'regex'),
                array('floor', '1,9999', '成团人数不正确', Model::MUST_VALIDATE, 'between'),
                array('upper', 'require', '人数上限不能为空', Model::MUST_VALIDATE, 'regex'),
                array('upper', '1,9999', '人数上限不正确', Model::MUST_VALIDATE, 'between'),
                array('money', 'require', '人均费用不能为空', Model::MUST_VALIDATE, 'regex'),
                array('money', 'number', '人均费用不正确', Model::MUST_VALIDATE, 'regex'),
                array('phone', 'require', '联系方式不能为空', Model::MUST_VALIDATE, 'regex'),
                array('starttime', 'require', '活动开始时间不能为空', Model::MUST_VALIDATE, 'regex'),
                array('endtime', 'require', '活动结束时间不能为空', Model::MUST_VALIDATE, 'regex'),
                // array('intro', 'require', '活动介绍不能为空', Model::MUST_VALIDATE, 'regex'),
                array('city', 'require', '城市不能为空', Model::MUST_VALIDATE, 'regex'),
            ));

            if (I('upper') < I('floor')) {
                $this->retError("人数上限必须大于下限");
            }

            if (I('endtime') < I('starttime')) {
                $this->retError("活动结束时间必须大于开始时间");
            }
        }

        if ($id) {
            $info = D('Activity')->find($id);
            if (!$info) {
                $this->retError('找不到编辑的活动');
            }

            if ($info['atype'] != $data['atype']) {
                $this->retError('活动类型不能修改');
            }

            if ($info['astatus'] == 0 || ($info['astatus'] == 2 && $info['anumber'] == 0)) {

                $rows = $activity->editActivity($data);
            }
            else {

                $this->retError('此状态下不能编辑活动');
            }
        }
        else {
            $aid = $activity->addActivity($data);
        }

        $this->retSuccess($aid);
    }

    //加入活动
    public function join() {
        M()->validation(array(
            array('uid', 'require', '用户不能为空', Model::MUST_VALIDATE, 'regex'),
            array('uid', 'integer', '用户ID格式不正确', Model::MUST_VALIDATE, 'regex'),
            array('aid', 'require', '活动不能为空', Model::MUST_VALIDATE, 'regex'),
            array('aid', 'integer', '活动格式不正确', Model::MUST_VALIDATE, 'regex'),
        ));

        $aid = I('aid');
        $uid = I('uid');

        $activity = D('Activity')->find($aid);

        if ($activity['atype'] != '2' && $activity['anumber'] >= $activity['upper']) {
            $this->retError('活动报名人数已满');
        }

        if ($activity['astatus'] != 2 && $activity['astatus'] != 4) {
            if ($activity['astatus'] == '3' && $activity['atype'] == '2') {
                //排除大型活动人满情况
            }
            else {
                $this->retError('活动状态错误');
            }
        }

        if ($activity['money'] != 0) {
            $this->retError('人均费用必须为0');
        }

        $cnt = D('ActivityUser')->where("aid = '$aid' and uid = '$uid'")->count();
        if ($cnt) {
            $this->retError('不能重复报名');
        }

        D("ActivityUser")->addJoin($uid, $aid);

        $this->retSuccess();
    }

    /**
     * 获取活动列表
     * @param aid string 城市
     * @return JSON 列表
     */
    public function activityDetail(){

        M()->validation(array(
            array('aid', 'require', '活动ID不能为空', Model::MUST_VALIDATE, 'regex'),
            // array('uid', 'require', '用户ID不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $aid = I('aid');

        $activity = D('activity')->relation(true)->find($aid);

        if (!$activity) {
            $this->retError('没有此活动');
        }

        $movie_list = M()->query("SELECT mid, title from t_movie_activity ma, t_movie m where ma.mid = m.id and ma.aid = '$aid'");

        $activity['movies'] = $movie_list;

        $mid = $activity['mid'];

        $activity['isCheck'] = 0;
        $uid = I('uid');
        if ($uid) {
            $cnt = D('ActivityUser')->where("aid = '$aid' and uid = '$uid'")->count();
            if ($cnt) {
                $activity['isCheck'] = 1;
            }
        }

        $same_activitys = M()->query("SELECT t1.*, t2.picture FROM (
            SELECT a.id as aid, a.uid, a.title, a.money, a.starttime, a.phone, u.nickname, u.avatar, a.movie_title_list as movie_name, s.sitename, s.address, s.latitude, s.longitude, a.astatus
            FROM t_activity a, t_user u, t_site s
            where a.uid = u.id and a.sid = s.id and a.id <> $aid and a.astatus = 2 and a.id in (select aid from t_movie_activity ma, t_movie m where ma.mid = m.id and m.id='$mid' ) limit 5
            ) t1 LEFT JOIN (select aid, min(picture) as picture from t_activity_content group by aid) t2 on t1.aid = t2.aid");

        $join_users = M()->query("select t1.id, avatar, nickname from t_user t1, t_activity_user t2 where t1.id = t2.uid and t2.aid = $aid");


        $activity['same_activitys'] = $same_activitys;
        $activity['join_users'] = $join_users;

        $this->retSuccess($activity);
    }

    public function checkin(){
        M()->validation(array(
            array('uid', 'require', '用户ID不能为空', Model::MUST_VALIDATE, 'regex'),
            array('aid', 'require', '活动ID不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $uid=I('uid');
        $aid=I('aid');

        $cnt=D("ActivityUser")->where("uid=%d AND aid=%d",$uid,$aid)->find();
        if(!$cnt){
            $this->retError("该用户还未报名这个活动!");
        }

        if($cnt['issignin']==1){
            $this->retError("该用户已经签到过了!");
        }

        $data=array(
            'issignin' => 1,
            'signintime' =>time(),
        );

        D("ActivityUser")->where("uid=%d AND aid=%d",$uid,$aid)->save($data);

        $info=D("activity")->where("id=%d",$aid)->field("uid,money,mid")->find();

        if ($info['money']) {
            D("user")->where("id=%d",$info['uid'])->setInc('amount',$info['money']);
        }

        $mid = $info['mid'];
        
        D("activity")->where("id=%d", $aid)->setInc('snumber');
        D('UserWish')->where("uid = '$uid' and mid = '$mid'")->setField('iscomplete', '1');

        D('Push', 'Logic')->reminderUser($uid, 6, '你参加的['.$info['title'].']活动已成功签到'); 

        $this->retSuccess("签到成功!");
    }

    public function checkinVerifyCode(){
        M()->validation(array(
            array('code', 'require', '验证码不能为空', Model::MUST_VALIDATE, 'regex'),
            array('aid', 'require', '活动ID不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $code = I('code');
        $aid = I('aid');

        $order = D('Order')->where("verify_code = '$code' and is_verify = 0 and aid = '$aid'")->find();
        if (!$order) {
            $this->retError('验证码错误');
        }

        $uid = $order['uid'];

        $cnt=D("ActivityUser")->where("uid=%d AND aid=%d",$uid,$aid)->find();
        if(!$cnt){
            $this->retError("该用户还未报名这个活动!");
        }

        if($cnt['issignin']==1){
            $this->retError("该用户已经签到过了!");
        }

        $data=array(
            'issignin' => 1,
            'signintime' =>time(),
        );

        D("ActivityUser")->where("uid=%d AND aid=%d",$uid,$aid)->save($data);

        $activity = D('Activity')->cache("activity_base_$aid")->find($aid);

        $data = array(
            'verify_time' => time()
        );

        D("Order")->where("uid=%d AND aid=%d",$uid,$aid)->save($data);

        if ($order['orderprice']) {
            D("user")->where("id=%d",$activity['uid'])->setInc('amount',$order['orderprice']);
        }

        D('Push', 'Logic')->reminderUser($uid, 6, '你参加的['.$activity['title'].']活动已成功签到'); 

        $this->retSuccess("签到成功!");
    }

    //获取验证码
    public function get_verify_code() {
        M()->validation(array(
            array('uid', 'require', '用户ID不能为空', Model::MUST_VALIDATE, 'regex'),
            array('aid', 'require', '活动ID不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $uid = I('uid');
        $aid = I('aid');

        $order = D('Order')->where("uid = '$uid' and aid = '$aid'")->find();
        if (!$order) {
            $this->retError('未找到该记录');
        }

        $this->retSuccess($order['verify_code']);
    }

    public function cancel() {
        M()->validation(array(
            array('aid', 'require', '活动ID不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $aid = I('aid');

        $activity = D('Activity')->field('anumber,floor,astatus')->find($aid);
        if ($activity['anumber'] < $activity['floor'] && ($activity['astatus'] == 0 || $activity['astatus'] == 2)) {

        }
        else {
            $this->retError('活动状态不允许取消');
        }


        $flg = D('Activity')->where("id = '$aid'")->setField('astatus', '-2');
        if (!$flg) {
            $this->retError('取消活动出错，请稍候再试');
        }

        $list = D('Order')->field("orderno,uid,orderprice")->where("aid = '$aid' and paystatus = 1")->select();

        if (count($list) > 0) {

            //修改所有账单状态为取消
            $orderValues = '';

            array_walk($list, function($a) use (&$orderValues) {
                $orderValues .= "('".$a['orderno']."', '-1'),";
            });

            $orderValues = substr($orderValues, 0, strlen($orderValues) - 1);

            $sql = "insert into t_order (orderno,paystatus) values $orderValues on duplicate key update paystatus=values(paystatus)";

            setLog('schedule5:modify_order:'.$sql);

            $flg = M()->execute($sql);

            //给已付款的账户增加对应金额
            $userValues = '';

            array_walk($list, function($a) use (&$userValues) {
                $userValues .= '('.$a['uid'].', '.$a['orderprice'].'),';
            });

            $userValues = substr($userValues, 0, strlen($userValues) - 1);

            $sql = "insert into t_user (id,amount) values $userValues on duplicate key update amount=amount+values(amount)";

            setLog('schedule5:modify_amount:'.$sql);

            $flg = M()->execute($sql); 
        }

        $this->retSuccess();
    }

    function sql_get_list($where) {

        $sql = "SELECT t1.*, t2.picture FROM (
            SELECT a.id as aid, a.uid, a.sid, a.title, a.money, a.money_hour, a.starttime, a.endtime, u.nickname, u.avatar, a.movie_title_list as movie_name, s.address, s.latitude, s.longitude, a.astatus, a.atype
            FROM t_activity a, t_user u, t_site s
            where a.uid = u.id and a.sid = s.id and $where
        ) t1 LEFT JOIN (select aid, picture from t_activity_content group by aid) t2 on t1.aid = t2.aid";

        return $sql;
    }

    /**
     * 根据电影名称获取活动列表
     */
    public function get_list_by_movie_title() {

        M()->validation(array(
            array('city', 'require', '城市不能为空', Model::MUST_VALIDATE, 'regex'),
            array('title', 'require', '关键字不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $city = I('city');
        $title = I('title');

        $sql = $this->sql_get_list("a.movie_title_list like '%$title%' and a.city = '$city'");

        $list = M()->query($sql);

        $this->retSuccess($list);
    }

    /**
     * 根据主题获取活动列表
     */
    public function get_list_by_subject() {

        M()->validation(array(
            array('city', 'require', '城市不能为空', Model::MUST_VALIDATE, 'regex'),
            array('sid', 'require', '主题不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $city = I('city');
        $sid = I('sid');

        $longitude = I('longitude');
        $latitude = I('latitude');

        $mtype = D('Mtype')->find($sid);
        if ($mtype['category'] == '0') {
            $where = "a.id in (select aid from t_movie_activity ma, t_movie m where ma.mid = m.id and FIND_IN_SET($sid, m.subject)) and a.city = '$city'";
        }
        elseif ($mtype['category'] == '1') {
            $uid = $mtype['user_id'];
            $where = "a.uid = '$uid' and a.city = '$city'";
        }
        elseif ($mtype['category'] == '2') {
            $uid = $mtype['user_id'];
            $where = "a.id in (select aid from t_mtype_activity where tid = '$sid') and a.city = '$city'";
        }

        if ($longitude && $latitude) {
            $where .= " order by ACOS(SIN(($latitude * 3.1415) / 180 ) *SIN((latitude * 3.1415) / 180 ) + COS(($latitude * 3.1415) / 180 ) * COS((latitude * 3.1415) / 180 ) *COS(($longitude * 3.1415) / 180 - (longitude * 3.1415) / 180 ) ) * 6380 asc";
        }

        $sql = $this->sql_get_list($where);

        $list = M()->query($sql);

        $this->retSuccess($list);
    }

    /**
     * 根据电影获取活动列表
     */
    public function get_list_by_movie() {
        M()->validation(array(
            array('city', 'require', '城市不能为空', Model::MUST_VALIDATE, 'regex'),
            array('mid', 'require', '电影不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $city = I('city');
        $mid = I('mid');

        $sql = $this->sql_get_list("(a.id in (select aid from t_movie_activity ma, t_movie m where ma.mid = m.id and m.id=$mid ) or a.mid = '$mid') and a.city = '$city' order by a.last_modify_time desc");

        $list = M()->query($sql);

        $this->retSuccess($list);
    }

    /**
     * 根据城市获取活动列表
     */
    public function get_list_by_city() {
        M()->validation(array(
            array('city', 'require', '城市不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $city = I('city');

        $longitude = I('longitude');
        $latitude = I('latitude');

        if ($longitude && $latitude) {
            // $offset = 0.045;

            // $where = "a.city = '$city' ".'and latitude > '.($latitude - $offset).' and latitude < '.($latitude + $offset).' and longitude > '.($longitude - $offset).' and longitude < '.($longitude + $offset);
            $where = "a.city = '$city'";

            $sql = "SELECT t1.*, t2.picture FROM (
                    SELECT a.id as aid, a.uid, a.mid, a.sid, a.title, a.movie_title_list as movie_name, a.money, a.money_hour, a.starttime, u.nickname, u.avatar, s.address, s.latitude, s.longitude, a.astatus, a.atype, ACOS(SIN(($latitude * 3.1415) / 180 ) *SIN((latitude * 3.1415) / 180 ) + COS(($latitude * 3.1415) / 180 ) * COS((latitude * 3.1415) / 180 ) *COS(($longitude * 3.1415) / 180 - (longitude * 3.1415) / 180 ) ) * 6380000 as distance
                    FROM t_activity a, t_user u, t_site s
                    where a.uid = u.id and a.sid = s.id and $where order by distance
                ) t1 LEFT JOIN (select aid, min(picture) as picture from t_activity_content group by aid) t2 on t1.aid = t2.aid";
        }
        else {

            $sql = $this->sql_get_list("a.city = '$city'");
        }

        $list = M()->query($sql);

        $this->retSuccess($list); 
    }

    /**
     * 根据城市获取活动列表
     */
    public function get_list_by_weixin() {
        M()->validation(array(
            array('city', 'require', '城市不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $city = I('city');

        $sql = $this->sql_get_list("a.city = '$city' order by a.last_modify_time desc");

        $list = M()->query($sql);

        $this->retSuccess($list); 
    }


    /**
     * 获取正在申请场地的活动列表
     */
    public function get_list_applying() {
        M()->validation(array(
            array('city', 'require', '城市不能为空', Model::MUST_VALIDATE, 'regex'),
            array('sid', 'require', '场地不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $city = I('city');
        $sid = I('sid');

        $where = "a.sid = $sid and a.city = '$city' and a.astatus = 1 order by a.last_modify_time desc";

        //这里不用sql_get_list是因为只有这里需要查询apply_intro字段,其他没有区别
        $sql = "SELECT t1.*, t2.picture FROM (
            SELECT a.id as aid, a.uid, a.sid, a.title, a.money, a.starttime, u.nickname, u.avatar, a.apply_intro, a.movie_title_list as movie_name, s.address, s.latitude, s.longitude, a.astatus, a.atype
            FROM t_activity a, t_user u, t_site s
            where a.uid = u.id and a.sid = s.id and $where
        ) t1 LEFT JOIN (select aid, min(picture) as picture from t_activity_content group by aid) t2 on t1.aid = t2.aid";

        $list = M()->query($sql);

        $this->retSuccess($list); 
    }

    /**
     * 获取申请完成场地的活动列表
     */
    public function get_list_applied() {
        M()->validation(array(
            array('city', 'require', '城市不能为空', Model::MUST_VALIDATE, 'regex'),
            array('sid', 'require', '场地不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $city = I('city');
        $sid = I('sid');

        $sql = $this->sql_get_list("a.sid = $sid and a.city = '$city' and a.astatus >= 2 and a.astatus <= 5 order by a.last_modify_time desc");

        $list = M()->query($sql);

        $this->retSuccess($list); 
    }

    /**
     * 获取场地的活动列表历史
     */
    public function get_list_history() {
        M()->validation(array(
            array('city', 'require', '城市不能为空', Model::MUST_VALIDATE, 'regex'),
            array('sid', 'require', '场地不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $city = I('city');
        $sid = I('sid');

        $sql = $this->sql_get_list("a.sid = $sid and a.city = '$city' and a.astatus = 6 order by a.last_modify_time desc");

        $list = M()->query($sql);

        $this->retSuccess($list); 
    }

    //同意场地申请
    public function site_apply_consent() {
        $activity = D("Activity");

        $activity->validation(array(
            array('aid', 'require', '活动ID不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $info = $activity->find(I('aid'));
        if (!$info) {
            $this->retError('活动ID错误');
        }

        if ($info['astatus'] != 1) {
            $this->retError('活动状态错误');
        }

        $data = array(
            'id' => I('aid'),
            'astatus' => 2
        );

        $flg = $activity->create($data);
        if ($flg) {
            $flg = $activity->save();

            if ($flg) {
                D('Push', 'Logic')->reminderUser($info['uid'], 2, '你申请的场地已经通过，小伙伴们可以报名参加了');

                $this->retSuccess();
            }
        }

        $this->retError();
    }

    //拒绝场地申请
    public function site_apply_refusal() {
        $activity = D("Activity");

        $activity->validation(array(
            array('aid', 'require', '活动ID不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $info = $activity->find(I('aid'));
        if (!$info) {
            $this->retError('活动ID错误');
        }

        if ($info['astatus'] != 1) {
            $this->retError('活动状态错误');
        }

        $data = array(
            'id' => I('aid'),
            'astatus' => 0
        );

        $flg = $activity->create($data);
        if ($flg) {
            $flg = $activity->save();

            if ($flg) {
                $content = I('content');

                $data = array(
                    'uid' => $info['uid'],
                    'title' => '场地申请被拒绝',
                    'content' => '你的活动['.$info['title']."]所申请的场地被拒绝，请重新修改活动并提交场地申请，拒绝原因：\n$content",
                    'createtime' => time()
                );
                $sysmsg = D('Sysmsg');

                if ($sysmsg->create($data)) {
                    $sysmsg->add();
                }

                D('Push', 'Logic')->reminderUser($info['uid'], 2, '你申请的场地被拒绝，请重新修改活动并提交场地申请');

                $this->retSuccess();
            }
        }

        $this->retError(); 
    }

    /**
     * 获取我的活动列表-正在举行
     */
    public function get_list_my_host_progress(){

        M()->validation(array(
            array('uid', 'require', '用户ID不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $uid=I('uid');

        $list=M()->query("SELECT a.uid,a.id as aid,a.title,a.floor,a.upper,u.avatar,s.sitename,s.address,s.address_location,a.phone as phonenumber,a.anumber,a.snumber,a.movie_title_list as movie_title,u.nickname, a.starttime, a.endtime, a.astatus, a.atype, a.money, 
            case when a.astatus > 1 and a.astatus < 5 and a.anumber > 0 and a.anumber > a.snumber and a.anumber >= a.floor then '1' else '0' END enable_signin,
            case when (a.astatus = 0 or a.astatus = 2) and a.anumber < a.floor then '1' else '0' END enable_cancel
            from t_activity a,t_user u,t_site s
            where a.uid= $uid AND a.uid=u.id AND a.sid=s.id AND (a.astatus >= 0 and a.astatus < 6)
            order by a.last_modify_time desc");

        $this->retSuccess($list);
    }

    /**
     * 获取我的活动列表-历史
     */
    public function get_list_my_host_complete(){

        M()->validation(array(
            array('uid', 'require', '用户ID不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $uid=I('uid');

        $list=M()->query("SELECT a.uid,a.id as aid,a.title,a.floor,a.upper,u.avatar,s.sitename,s.address,s.address_location,a.phone as phonenumber,a.anumber,a.snumber,a.movie_title_list as movie_title,u.nickname, a.starttime, a.endtime, a.astatus, a.atype, a.money, case when a.astatus > 1 and a.astatus < 5 and a.anumber >= a.floor then '1' else '0' END enable_signin
                            from t_activity a,t_user u,t_site s
                            where a.uid= $uid AND a.uid=u.id AND a.sid=s.id AND (a.astatus >= 6 or a.astatus < 0)
                            order by a.last_modify_time desc");

        $this->retSuccess($list);
    }

    /**
     * 获取我参加的活动列表-正在举行
     */
    public function get_list_my_join_progress(){

        M()->validation(array(
            array('uid', 'require', '用户ID不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $uid=I('uid');

        $list=M()->query("SELECT a.uid,a.id as aid,a.title,a.floor,a.upper,u.avatar,s.sitename,s.address,s.address_location,a.phone as phonenumber,a.anumber,a.snumber,a.movie_title_list as movie_title,u.nickname, a.starttime, a.endtime, a.astatus, a.atype, a.money, au.createtime as jointime, au.issignin
                                from t_activity_user au,t_activity a,t_user u,t_site s
                                where au.uid=$uid AND au.aid=a.id AND a.uid=u.id AND a.sid=s.id and (a.astatus >= 2 and a.astatus < 6)
                                order by a.last_modify_time desc");
        $this->retSuccess($list);
    }

    /**
     * 获取我参加的活动列表-历史
     */
    public function get_list_my_join_complete(){

        M()->validation(array(
            array('uid', 'require', '用户ID不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $uid=I('uid');

        $list=M()->query("SELECT a.uid,a.id as aid,a.title,a.floor,a.upper,u.avatar,s.sitename,s.address,s.address_location,a.phone as phonenumber,a.anumber,a.snumber,a.movie_title_list as movie_title,u.nickname, a.starttime, a.endtime, a.astatus, a.atype, a.money, au.createtime as jointime, au.issignin
                                from t_activity_user au,t_activity a,t_user u,t_site s
                                where au.uid= $uid AND au.aid=a.id AND a.uid=u.id AND a.sid=s.id and (a.astatus >= 6 or a.astatus < 0)
                                order by a.last_modify_time desc");

        $this->retSuccess($list);
    }

    //是否存在我举办的正在进行的活动
    public function exists_my_progress_activity() {
        M()->validation(array(
            array('uid', 'require', '用户ID不能为空', Model::MUST_VALIDATE, 'regex'),
        ));
        
        $uid = I('uid');

        $list = M()->query("SELECT count(1) as cnt from t_activity where uid= $uid AND (astatus >= 0 and astatus < 6)");

        if ($list && $list[0]['cnt'] > 0) {
            $this->retSuccess();
        }

        $this->retError();
    }
}