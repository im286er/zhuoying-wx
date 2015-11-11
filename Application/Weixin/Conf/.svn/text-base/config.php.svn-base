<?php

return array(
    'LOG_LEVEL'  =>'EMERG,ALERT,CRIT,ERR,WARN',

    //SMS
    'SMS_ACCOUNT_SID' => 'aaf98f894bc4f9b9014bd8a78cd208e6',
    'SMS_ACCOUNT_TOKEN' => 'f90862219ffe4a8bbc095d0b8033aaaf',

    'SMS_APP_ID' => 'aaf98f894d328b13014d40f6945c0996',

    //微信
    'WX_TOKEN' => 'zhuoyingmyline2015',
    'WX_APPID' => 'wx4e81aacd38a27424',
    'WX_APPSECRET' => 'b9667f93ea98113501240a251767d70f',

    'WX_SSLCERT_PATH' => 'Application/Weixin/cert/apiclient_cert.pem',
    'WX_SSLKEY_PATH' => 'Application/Weixin/cert/apiclient_key.pem',

    'WX_MCHID' => '1247136201',
    'WX_PAY_KEY' => '934f19bb58e149bc880ac27606e04afa',

    //Push
    'PUSH_APPID' => 'hnUCnOkX0b52mK45wbzXQ1',
    'PUSH_APPKEY' => '1JYCjwK4E07wBbawY5dXH2',
    'PUSH_APPSECRET' => 'XVlEJ4G8pN6mlAyKdLiiX6',
    'PUSH_MASTERSECRET' => 'QjMbnjNCYs7WBK3wmYFan5',
    'PUSH_HOST' => 'http://sdk.open.api.igexin.com/apiex.htm',

    'UNKNOWN_TAG' => '未分组',//对于新用户的默认TAG名称
    'PUSH_ALIAS_PREFIX' => 'UID',//别名前缀
    'PUSH_PARTY_PREFIX' => 'PARTY_',//活动前缀

    //通过curl使用HTTP POST方法，此处可修改其超时时间，默认为30秒
    'WX_CURL_TIMEOUT' => 30,
    //异步通知url
    'WX_NOTIFY_URL' => 'http://weixin.myline.cc/weixin/activity/notify',
    //获取access_token过程中的跳转uri，通过跳转将code传入jsapi支付页面
    'WX_JS_API_CALL_URL' => 'http://weixin.myline.cc/weixin/party?showwxpaytitle=1',


    /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__ . '/Public/static',
        '__ADDONS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/Addons',
        '__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/images',
        '__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/css',
        '__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
    ),

    // 开启路由
    'URL_ROUTER_ON'   => true,

    'URL_ROUTE_RULES' => array(
        'activitys/:id' => 'Activity/index',
        'activitys' => 'Home/activity',
        'wishes/:id' => 'Movie/index',
        'wishes' => 'Home/wish',
    ),

    'URL_MODEL'=>2,
    'URL_HTML_SUFFIX' =>'html|shtml',
);