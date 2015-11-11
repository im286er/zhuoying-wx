<?php
/**
 * Author: NanQi
 * Date: 20150416 20:53
 */
namespace Test\Controller;

class UserController extends ThinkPHPUnitController {

    protected $_message_render = self::MESSAGE_RENDER_ECHO;

    public function test_sendsmsverify(){

        $urlBase = C("HTTP_HOST")."/app/user/sendsmsverify";

        $newphonenumber = "181".(20000000 + rand(0, 9999999));

        $this->assertEquals(400,send_get($urlBase)->errcode);   //手机号不能为空
        $this->assertEquals(304,send_get($urlBase."?phonenumber=adsf.com")->errcode);  //手机号格式不正确
        $this->assertEqualsTrue(send_get_success($urlBase."?phonenumber=".$newphonenumber));  //手机号格式正确
    }

    public function test_verifysms(){
        $urlBase = C("HTTP_HOST")."/app/user/verifysms";
        $urlSendsmsverify = C("HTTP_HOST")."/app/user/sendsmsverify";

        $newphonenumber = "182".(20000000 + rand(0, 9999999));
        $code = substr($newphonenumber, strlen($newphonenumber) - 4);

        $this->assertEquals(400,send_get($urlBase)->errcode);   //手机号不能为空
        $this->assertEquals(400,send_get($urlBase."?phonenumber=18220512014")->errcode);  //验证码不能为空

        $this->assertEqualsTrue(send_get_success($urlSendsmsverify."?phonenumber=".$newphonenumber));  //发送验证码

        $this->assertEquals(201,send_get($urlBase."?phonenumber=".$newphonenumber."&code=error")->errcode);  //验证码错误
        $this->assertEquals(304,send_get($urlBase."?phonenumber=adsf.com&code=".$code)->errcode);  //手机号格式不正确
        $this->assertEqualsTrue(send_get_success($urlBase."?phonenumber=".$newphonenumber."&code=".$code));  //通过
    }

    public function test_register(){
        $urlBase = C("HTTP_HOST")."/app/user/register";
        $urlVerifyphonenumber = C("HTTP_HOST")."/app/user/sendsmsverify";

        $this->assertEquals(400,send_get($urlBase)->errcode);   //  邮箱和手机号不能同时为空
        $this->assertEquals(400,send_get($urlBase."?phonenumber=18220512014")->errcode);   //  密码不能同时为空
        //$this->assertEquals(40001,send_get($urlBase."?phonenumber=18220512014&password=123456")->errcode);   //  验证码不能同时为空
        $this->assertEquals(400,send_get($urlBase."?phonenumber=18220512014&password=123456")->errcode);   //  registrationid不能同时为空

        $uuid = md5(uniqid(rand(),true));

        $this->assertEquals(401,send_get($urlBase."?phonenumber=18220512014&password=123456&registrationid=".$uuid.$uuid)->errcode);   //  registrationid非法

        $newphonenumber = "183".(20000000 + rand(0, 9999999));
        $code = substr($newphonenumber, strlen($newphonenumber) - 4);

        $this->assertEquals(402,send_get($urlBase."?phonenumber=".$newphonenumber."&password=1235456&registrationid=".$uuid)->errcode);  //短信验证码已过期
        //$this->assertEquals(40002,send_get($urlBase."?email=adsf.com&password=1235456")->errcode);    //邮箱格式不正确

        $this->assertEqualsTrue(send_get_success($urlVerifyphonenumber."?phonenumber=".$newphonenumber));  //获取一次验证码

        $this->assertEqualsTrue(send_get_success($urlBase."?phonenumber=".$newphonenumber."&password=123456&code=".$code."&registrationid=05086381974"));   //通过
    }


    public function test_login(){
        $urlBase = C("HTTP_HOST")."/app/user/login";
        $urlRegister = C("HTTP_HOST")."/app/user/register";
        $urlVerifyphonenumber = C("HTTP_HOST")."/app/user/sendsmsverify";

        $this->assertEquals(400,send_get($urlBase)->errcode);   //参数name、password和token不能同时为空
        $this->assertEquals(400,send_get($urlBase."?name=18220512014")->errcode);   //参数name、password和token不能同时为空
        $this->assertEquals(400,send_get($urlBase."?name=18220512014&password=123456")->errcode);   //参数registrationid不能为空

        $this->assertEquals(304,send_get($urlBase."?name=safasdga&password=123456&registrationid=05086381974")->errcode);   //
        $this->assertEquals(401,send_get($urlBase."?name=18220512014&password=12345&registrationid=05086381974")->errcode); //密码长度错误

        $uuid = md5(uniqid(rand(),true));

        $this->assertEquals(401,send_get($urlBase."?name=18220512014&password=12345&registrationid=".$uuid.$uuid)->errcode);  //registrationid长度错误

        //$userData = send_get($urlBase."?token=".$uuid);
        //$this->assertEquals(1, $userData->data->istempuser); //临时用户

        $newphonenumber = "184".(20000000 + rand(0, 9999999));
        $code = substr($newphonenumber, strlen($newphonenumber) - 4);

        $this->assertEqualsTrue(send_get_success($urlVerifyphonenumber."?phonenumber=".$newphonenumber));  //获取一次验证码
        //$this->assertEquals(40101, send_get($urlRegister."?phonenumber=".$newphonenumber."&password=123456&code=ddfa")->errcode);   //短信验证码错误
        $this->assertEqualsTrue(send_get_success($urlRegister."?phonenumber=".$newphonenumber."&password=123456&code=".$code."&registrationid=05086381974"));   //通过

        $this->assertEqualsTrue(send_get_success($urlBase."?name=".$newphonenumber."&password=123456&registrationid=05086381974"));   //通过
    }

    public function test_modifypassword(){
        $urlBase = C("HTTP_HOST")."/app/user/modifypassword";
        $urlRegister = C("HTTP_HOST")."/app/user/register";
        $urlVerifyphonenumber = C("HTTP_HOST")."/app/user/sendsmsverify";

        $this->assertEquals(400,send_get($urlBase)->errcode);   //参数不能为空
        //$this->assertEquals(40001,send_get($urlBase."?phonenumber=18220512014")->errcode);   //传phonenumber必须传code
        //$this->assertEquals(40001,send_get($urlBase."?phonenumber=18220512014&code=2014")->errcode);   //参数newpassword不能为空
        $this->assertEquals(400,send_get($urlBase."?phonenumber=18220512014")->errcode);   //参数newpassword不能为空
        $this->assertEquals(304,send_get($urlBase."?phonenumber=1850924952&newpassword=123456")->errcode); //使用了非法的phonenumber

        $newphonenumber = "185".(20000000 + rand(0, 9999999));
        $code = substr($newphonenumber, strlen($newphonenumber) - 4);

        $this->assertEquals(201,send_get($urlBase."?phonenumber=".$newphonenumber."&newpassword=123456")->errcode); //该手机号没有对应的帐号信息

        //注册
        $this->assertEqualsTrue(send_get_success($urlVerifyphonenumber."?phonenumber=".$newphonenumber));  //获取一次验证码
        $userID = send_get($urlRegister."?phonenumber=".$newphonenumber."&password=123456&code=".$code."&registrationid=05086381974")->data;

        $this->assertEquals(202, send_get($urlBase."?phonenumber=".$newphonenumber."&newpassword=123456")->errcode); //新密码不能和原密码相同
        //$this->assertEquals(40101, send_get($urlBase."?phonenumber=".$newphonenumber."&code=dddf&newpassword=654321")->errcode); //短信验证码错误
        $this->assertEqualsTrue(send_get_success($urlBase."?phonenumber=".$newphonenumber."&newpassword=654321")); //通过

        $this->assertEquals(200, send_get($urlBase."?oldpassword=123456&newpassword=123456")->errcode); //帐号未登录
        $this->assertError(300, $urlBase."?oldpassword=123456&newpassword=123456&authuserid=ddf");//用户ID格式不正确

        $this->assertEquals(204, send_get($urlBase."?oldpassword=123456&newpassword=123456&authuserid=".$userID)->errcode); //原密码错误
        $this->assertEquals(202, send_get($urlBase."?oldpassword=654321&newpassword=654321&authuserid=".$userID)->errcode); //新密码不能和原密码相同
        $this->assertEqualsTrue(send_get_success($urlBase."?oldpassword=654321&newpassword=123456&authuserid=".$userID)); //通过
    }
}