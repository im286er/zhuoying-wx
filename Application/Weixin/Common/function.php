<?php

function sae_log($key, $log) {
    \Think\Log::record($key.':'.$log);
    \Think\Log::save();
}

/**
 * 给日志表插入信息
 */
function setLog($msg){
    $data = array(
        'createtime' => time(),
        'logcontent' => $msg
    );
    M("DebugLog")->add($data);
}


function tounix($date) {
    $year  =((int)substr($date,0,4));//年
    $month =((int)substr($date,4,2));//月
    $day   =((int)substr($date,6,2));//天
    $hour  =((int)substr($date,8,2));//时
    $minu  =((int)substr($date,10,2));//分
    $send  =((int)substr($date,12,2));//秒
    $hour=$hour?$hour:0;$minu=$minu?$minu:0;$send=$send?$send:0;$month=$month?$month:0;$day=$day?$day:0;$year=$year?$year:0;
    return  mktime($hour,$minu,$send,$month,$day,$year);
}

function format_money($money) {
    return sprintf("%.2f", $money / 100); 
}

function format_content($input, $length) {

    if (strlen($input) <= $length) {
        return $input;
    }

    $first = sub_str($input, 0, $length);

    if (strlen($input) <= $length * 2) {
        $last = sub_str($input, $length);
    }
    else {
        $last = sub_str($input, $length, $length - 3).'...';
    }

    return "$first<br />$last";
}

function sub_str($str, $start, $length = 0) {
    if (function_exists('mb_substr'))
    {
        $newstr = mb_substr($str, $start, $length, 'utf-8');
    }
    elseif (function_exists('iconv_substr'))
    {
        $newstr = iconv_substr($str, $start, $length, 'utf-8');
    }
    else
    {
        //$newstr = trim_right(substr($str, 0, $length));
        $newstr = substr($str, $start, $length);
    }

    return $newstr;
}
