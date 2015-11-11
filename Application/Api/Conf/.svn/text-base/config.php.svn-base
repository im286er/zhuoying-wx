<?php

return array(
    
    // 开启路由
    'URL_ROUTER_ON'   => true,

    'URL_ROUTE_RULES' => array(
        //restful
        //'v1/movies/getMovieList/:id' => 'Movie/getMovieList',
        'v1/movie/getMovieList' => 'Wish/get_list_by_movie_title',


        //心愿
        'v1/wish/addWish' => 'Wish/addWish',
        'v1/wish/getWishList' => 'Wish/getWishList',
        'v1/wish/getMyWish' => 'Wish/getMyWishList',
        'v1/movie/get_info' => 'movie/get_info',
        'v1/wish/test' => 'Wish/test',

        'v1/wish/get_list' => 'Wish/get_list',
        'v1/wish/get_list_by_movie_title' => 'Wish/get_list_by_movie_title',

        //用户
        'v1/user/login' => 'user/login',
        'v1/user/userBind' => 'user/userBind',
        'v1/user/register' => 'user/register',
        'v1/user/modifyPass' => 'user/modifyPass',
        'v1/user/sendvertifycode' => 'user/sendvertifycode',
        'v1/user/getvertifycode' => 'user/getvertifycode',
        'v1/user/send_vertify_code_by_bind' => 'user/send_vertify_code_by_bind',
        'v1/user/loginBythird' => 'user/loginBythird',
        'v1/user/editUser' => 'user/editUser',
        'v1/user/existPhone' => 'user/existPhone',
        'v1/user/exists_site' => 'user/exists_site',
        'v1/user/exists_password' => 'user/exists_password',
        'v1/user/get_userinfo' => 'user/get_userinfo',
        'v1/user/auth_host' => 'user/auth_host',
        'v1/user/auth_site' => 'user/auth_site',
        'v1/user/get_auth_site' => 'user/get_auth_site',
        'v1/user/set_push_toggle' => 'user/set_push_toggle',

        //活动
        'v1/activity/launch' => 'activity/launch',
        'v1/activity/join' => 'activity/join',
        'v1/activity/cancel' => 'activity/cancel',
        'v1/activity/activityDetail' => 'activity/activityDetail',
        'v1/activity/checkin' => 'activity/checkin',
        'v1/activity/checkin_verify_code' => 'activity/checkin_verify_code',
        'v1/activity/get_verify_code' => 'activity/get_verify_code',

        'v1/activity/get_list_by_movie_title' => 'activity/get_list_by_movie_title',
        'v1/activity/get_list_by_subject' => 'activity/get_list_by_subject',
        'v1/activity/get_list_by_movie' => 'activity/get_list_by_movie',
        'v1/activity/get_list_by_city' => 'activity/get_list_by_city',
        'v1/activity/get_list_by_weixin' => 'activity/get_list_by_weixin',
        'v1/activity/get_list_applying' => 'activity/get_list_applying',
        'v1/activity/get_list_applied' => 'activity/get_list_applied',
        'v1/activity/get_list_history' => 'activity/get_list_history',
        'v1/activity/get_list_my_host_progress' => 'activity/get_list_my_host_progress',
        'v1/activity/get_list_my_host_complete' => 'activity/get_list_my_host_complete',
        'v1/activity/get_list_my_join_progress' => 'activity/get_list_my_join_progress',
        'v1/activity/get_list_my_join_complete' => 'activity/get_list_my_join_complete',

        'v1/activity/exists_my_progress_activity' => 'activity/exists_my_progress_activity',

        //消息
        'v1/message/getsysmsg' => 'message/getsysmsg',
        'v1/message/getusermsg' => 'message/getusermsg',
        'v1/message/gethistorymsg' => 'message/gethistorymsg',
        'v1/message/send_message' => 'message/send_message',
        'v1/message/delete_user_message' => 'message/delete_user_message',
        'v1/message/delete_system_message' => 'message/delete_system_message',

        //应用
        'v1/app/uploadImage' => 'app/uploadImage',
        'v1/app/payment' => 'app/payment',
        'v1/app/notify' => 'app/notify',
        'v1/app/wx_notify' => 'app/wx_notify',
        'v1/app/feedback' => 'app/feedback',

        //场地
        'v1/activity/addsite' => 'site/addsite',
        'v1/activity/getsite' => 'site/getsite',
        'v1/activity/editSite' => 'site/editSite',
        'v1/activity/getsitedetail' => 'site/getsitedetail',

        //审核
        'v1/activity/site_apply_consent' => 'activity/site_apply_consent',
        'v1/activity/site_apply_refusal' => 'activity/site_apply_refusal',

        //资金账户
        'v1/account/statement' => 'account/statement',
        'v1/account/purse' => 'account/purse',
        'v1/account/bank_list' => 'account/bank_list',
        'v1/account/add_bank' => 'account/add_bank',
        'v1/account/delete_bank' => 'account/delete_bank',
        'v1/account/cash_list' => 'account/cash_list',
        'v1/account/cash' => 'account/cash',

        //评论
        'v1/comment/add' => 'comment/add',
        'v1/comment/get_list' => 'comment/get_list',

        //更新
        'v1/app/update' => 'version/update',
        'v1/version/addversion' => 'version/addVersion',

        'v1/basic/get_bank_type_list' => 'basic/get_bank_type_list',

        //主题
        'v1/subject/get_list' => 'subject/get_list',

        //cron
        'v1/cron/schedule5' => 'cron/schedule5',
        //'v1/cron/incomingmovie' => 'cron/schedule_incominginmovie_douban',
        'v1/cron/intheatermovie' => 'cron/intheatermovie',

    ),
    //API接口异常处理不使用错误页面
    //'UN_SHOW_ERROR_PAGE' => true,

    'ALIPAY_PARAMS' =>array(
        'partner' => '2088911678898748',
        'seller_id' => 'me@myline.cc',
        'notify_url' => urlencode('http://zhuodianying.sinaapp.com/Api/v1/app/notify'),
    ),

    'URL_MODEL'=>2,
    'URL_HTML_SUFFIX' =>'html|shtml',

     /* URL配置 */
    'ERROR_PAGE' =>'/Public/404.html',

    //缓存ID
    'SCHEDULE5_ACTIVITY_LIST' => 'SCHEDULE5_ACTIVITY_LIST',

);