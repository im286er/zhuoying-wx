<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Test\Controller;

use Think\Controller;

class MovieController extends LTPtestController {
    
    
    public function test_news(){
        $urlbase = C("HTTP_HOST")."/app/movie/news";

        //验证影讯ID
        $this->assert(40001, send_get($urlbase)->errcode); //id为空
        $this->assert(40002, send_get($urlbase."?id=d")->errcode); //id非整数
        $this->assert_is_true(send_get_success($urlbase."?id=1")); //id为整数

        //验证mfields字符串
        $this->assert(40001,send_get($urlbase."?mfields=id,name22,alias")->errcode);
        $this->assert(40099,send_get($urlbase."?id=1&mfields=id,name22,alias")->errcode);
        $this->assert_is_true(send_get_success($urlbase."?id=1&mfields=id,alias"));
        $this->assert_is_true(send_get_success($urlbase."?id=1&mfields=id,mname,alias"));

        //验证nfields字符串
        $this->assert(40099,send_get($urlbase."?id=1&nfields=id,title22,content")->errcode);
        $this->assert_is_true(send_get_success($urlbase."?id=1&nfields=id,content"));
        $this->assert_is_true(send_get_success($urlbase."?id=1&nfields=id,title,content"));
    }

    public function test_latestnews(){
        $urlbase = C("HTTP_HOST")."/app/movie/latestnews";

        //验证用户ID
        $this->assert_is_true(send_get_success($urlbase)); //id为空
        $this->assert(40002, send_get($urlbase."?id=d")->errcode); //id非整数
        $this->assert_is_true(send_get_success($urlbase."?id=1")); //id为整数

        //验证mfields字符串
        $this->assert(40099,send_get($urlbase."?mfields=id,name22,alias")->errcode);
        $this->assert(40099,send_get($urlbase."?id=1&mfields=id,name22,alias")->errcode);
        $this->assert_is_true(send_get_success($urlbase."?id=1&mfields=id,alias"));
        $this->assert_is_true(send_get_success($urlbase."?id=1&mfields=id,mname,alias"));

        //验证nfields字符串
        $this->assert(40099,send_get($urlbase."?id=1&nfields=id,title22,content")->errcode);
        $this->assert_is_true(send_get_success($urlbase."?id=1&nfields=id,content"));
        $this->assert_is_true(send_get_success($urlbase."?id=1&nfields=id,title,content"));
    }

    public function test_newslist(){
        $urlbase = C("HTTP_HOST")."/app/movie/newslist";

        //验证用户ID
        $this->assert_is_true(send_get_success($urlbase)); //id为空
        $this->assert(40002, send_get($urlbase."?id=d")->errcode); //id非整数
        $this->assert_is_true(send_get_success($urlbase."?id=1")); //id为整数

        //验证mfields字符串
        $this->assert(40099,send_get($urlbase."?mfields=id,name22,alias")->errcode);
        $this->assert(40099,send_get($urlbase."?id=1&mfields=id,name22,alias")->errcode);
        $this->assert_is_true(send_get_success($urlbase."?id=1&mfields=id,alias"));
        $this->assert_is_true(send_get_success($urlbase."?id=1&mfields=id,mname,alias"));

        //验证nfields字符串
        $this->assert(40099,send_get($urlbase."?id=1&nfields=id,title22,content")->errcode);
        $this->assert_is_true(send_get_success($urlbase."?id=1&nfields=id,content"));
        $this->assert_is_true(send_get_success($urlbase."?id=1&nfields=id,title,content"));

        //验证分页
        $this->assert(40002,send_get($urlbase.'?pager={"limit":2,dd"page":1,"order":"id desc"}')->errcode);//pager参数不合法
        //$this->assert(40002,send_get($urlbase.'?pager={"limit":2,dd"page":1,"order":"id desc"}')->errcode);//pager参数排序不合法
        $this->assert_is_true(send_get_success($urlbase.'?pager={"limit":2,"page":1,"order":"id%20desc"}'));//合法的pager
    }
}
