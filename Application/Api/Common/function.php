<?php

/**
 * @author : Comer
 * @date   : 2015/7/24 17:27
 * @desc   :
 */

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


function array_sort($arr, $keys, $type='asc') { 
    $keysvalue = $new_array = array();

    foreach ($arr as $k=>$v){
        $keysvalue[$k] = $v[$keys];
    }

    if($type == 'asc'){
        asort($keysvalue);
    }else{
        arsort($keysvalue);
    }
    reset($keysvalue);

    foreach ($keysvalue as $k=>$v){
        $new_array[] = $arr[$k];
    }

    return $new_array; 
} 


/** 手机验证码生成函数
 *  考虑移动和电信的数字黑名单
 */
function get_mobile_code() {
    $forbidden_num = "1989:10086:12590:1259:10010:10001:10000:";
    do
    {
        $mobile_code = substr(microtime(), 2, 6);
    }
    while (preg_match($mobile_code.':', $forbidden_num));
    return $mobile_code;
}

function delete_data($table_name, $id) {
    $data = array(
        'status' => '-1',
    );

    $row = M($table_name)->where("id = '$id'")->save($data);

    return $row;
}