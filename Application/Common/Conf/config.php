<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
 * 系统配文件
 * 所有系统级别的配置
 */
return array(
    /* 模块相关配置 */
    'AUTOLOAD_NAMESPACE' => array('Addons' => ONETHINK_ADDON_PATH), //扩展模块列表
    'DEFAULT_MODULE'     => 'Home',
    'MODULE_DENY_LIST'   => array('Common','User','Install'),
    //'MODULE_ALLOW_LIST'  => array('Home','Admin'),

    /* 系统数据加密设置 */
    'DATA_AUTH_KEY' => 'A.Tf&OkEauLcYSsX10:#`|em$W+*,K3In~)=5FR"', //默认数据加密KEY

    /* 用户相关设置 */
    'USER_MAX_CACHE'     => 1000, //最大缓存用户数
    'USER_ADMINISTRATOR' => 1, //管理员用户ID

    /* URL配置 */
    'URL_CASE_INSENSITIVE' => true, //默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_MODEL'            => 3, //URL模式
    'VAR_URL_PARAMS'       => '', // PATHINFO URL参数变量
    'URL_PATHINFO_DEPR'    => '/', //PATHINFO URL分割符

    /* 全局过滤配置 */
    'DEFAULT_FILTER' => '', //全局过滤函数

    /* 文档模型配置 (文档模型核心配置，请勿更改) */
    'DOCUMENT_MODEL_TYPE' => array(2 => '主题', 1 => '目录', 3 => '段落'),

    'LOG_LEVEL'  =>'EMERG,ALERT,CRIT,ERR,WARN,SQL',

    //----some config for push----
    //SMS
    'SMS_ACCOUNT_SID' => 'aaf98f894bc4f9b9014bd8a78cd208e6',
    'SMS_ACCOUNT_TOKEN' => 'f90862219ffe4a8bbc095d0b8033aaaf',

    'SMS_APP_ID' => 'aaf98f894d328b13014d40f6945c0996',

    //Push
    'PUSH_APPID' => 'hnUCnOkX0b52mK45wbzXQ1',
    'PUSH_APPKEY' => '1JYCjwK4E07wBbawY5dXH2',
    'PUSH_APPSECRET' => 'XVlEJ4G8pN6mlAyKdLiiX6',
    'PUSH_MASTERSECRET' => 'QjMbnjNCYs7WBK3wmYFan5',
    'PUSH_HOST' => 'http://sdk.open.api.igexin.com/apiex.htm',

    'UNKNOWN_TAG' => '未分组',//对于新用户的默认TAG名称
    'PUSH_ALIAS_PREFIX' => 'UID',//别名前缀
    'PUSH_PARTY_PREFIX' => 'PARTY_',//活动前缀    
);
