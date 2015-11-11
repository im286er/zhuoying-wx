<?php
/**
 * Author: NanQi
 * Date: 20150414 16:03
 */

function send_get($url) {
    //初始化
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    //执行并获取HTML文档内容
    $output = curl_exec($ch);
    //释放curl句柄
    curl_close($ch);

    return json_decode($output);
}

function send_get_success($url) {
    return send_get($url)->success;
}

function send_post($url, $post_data) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // post数据
    curl_setopt($ch, CURLOPT_POST, 1);
    // post的变量
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    $output = curl_exec($ch);
    curl_close($ch);

    return json_decode($output)->success;
}

function send_post_success($url, $post_data) {
    return send_post($url, $post_data)->success;
}

function showurl($url){
    echo $url;
}