<?php

namespace Weixin\Controller;
use Think\Controller;

class IndexController extends Controller{
    /**
     * 调试模式，将错误通过文本消息回复显示
     *
     * @var boolean
     */
    private $debug = true;
    /**
     * 以数组的形式保存微信服务器每次发来的请求
     *
     * @var array
     */
    private $request;
    /**
     * 初始化，判断此次请求是否为验证请求，并以数组形式保存
     *
     * @param string $token 验证信息
     * @param boolean $debug 调试模式，默认为关闭
     */
    public function _initialize(/*$token, $debug = FALSE*/) {

        $token = C('WX_TOKEN');

        if (!$this->validateSignature($token)) {
            \Think\Log::record('签名验证失败');
            exit('签名验证失败');
        }

        if ($this->isValid()) {
            \Think\Log::record(I('echostr'));
            // 网址接入验证
            exit(I('echostr'));
        }

        if (!isset($GLOBALS['HTTP_RAW_POST_DATA'])) {
            \Think\Log::record('缺少数据');
            exit('缺少数据');
        }
        set_error_handler(array(&$this, 'errorHandler'));
        // 设置错误处理函数，将错误通过文本消息回复显示
        $xml = (array) simplexml_load_string($GLOBALS['HTTP_RAW_POST_DATA'], 'SimpleXMLElement', LIBXML_NOCDATA);
        $this->request = array_change_key_case($xml, CASE_LOWER);
        // 将数组键名转换为小写，提高健壮性，减少因大小写不同而出现的问题
    }

    public function index(){
        \Think\Log::record('index');

        $this->run();
    }
    /**
     * 判断此次请求是否为验证请求
     *
     * @return boolean
     */
    private function isValid() {
        return isset($_GET["echostr"]);
    }

    /**
     * 验证此次请求的签名信息
     *
     * @param  string $token 验证信息
     * @return boolean
     */
    private function validateSignature($token) {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 获取本次请求中的参数，不区分大小
     *
     * @param  string $param 参数名，默认为无参
     * @return mixed
     */
    protected function getRequest($param = FALSE) {
        if ($param === FALSE) {
            return $this->request;
        }
        $param = strtolower($param);
        if (isset($this->request[$param])) {
            return $this->request[$param];
        }
        return NULL;
    }
    /**
     * 用户关注时触发，用于子类重写
     *
     * @return void
     */
    protected function onSubscribe() {
        $openid = $this->getRequest('fromusername');


        $reply = D('Config')->field('value')->where("name = 'SUBSCRIBE_REPLY'")->find();
        if ($reply) {
            $this->responseText($reply['value']);
        }
       
        $this->responseText("少年，你好！欢迎关注捉影电影社交平台！小魔仙这厢有礼啦！");
    }
    /**
     * 用户取消关注时触发，用于子类重写
     *
     * @return void
     */
    protected function onUnsubscribe() {}
    /**
     * 收到文本消息时触发，用于子类重写
     *
     * @return void
     */
    protected function onText() {

        $content = $this->getRequest('content');
        $tousername = $this->getRequest('tousername');

        switch ($content) {
            case '1':
                $this->responseImage('MIIUVHHxFxG07mHI4K2Hb-u8xGEwx9zH1-qSh6IeXPI');
                break;
            
            case '电子券':
            case '探鹿':
                $this->responseImage("pa1wyVMWayMj5U2-vywDRpwA0OUMgUw8lLipk0dZ6pU");
                break;

            default:
                # code...
                break;
        }

        $xy = substr($content, 2, 1);

        if (strtolower($xy) == '#') {
            $this->responseText("您的愿望已收到，我们会尽量帮你实现");
        }
        else {
            //$url = 'http://www.tuling123.com/openapi/api?key=a333757f7f2a46f30879caac23fe8b4c&info='.$content.'&userid='.$tousername;
            //$ret = $this->curl($url);
            //$ret = json_decode($ret);

            //$this->responseText($ret->text);

            $reply = D('WeixinReply')->select();
            foreach ($reply as $key => $value) {
                if ($value['key'] == $content) {
                    $this->responseText($value['reply']);
                }
            }
        }
    }
    /**
     * 收到图片消息时触发，用于子类重写
     *
     * @return void
     */
    protected function onImage() {
        $this->responseImage('MIIUVHHxFxG07mHI4K2Hb2ykZxSHoXs00rn5AH467KY');
    }
    /**
     * 收到地理位置消息时触发，用于子类重写
     *
     * @return void
     */
    protected function onLocation() {}
    /**
     * 收到链接消息时触发，用于子类重写
     *
     * @return void
     */
    protected function onLink() {}
    /**
     * 收到自定义菜单消息时触发，用于子类重写
     *
     * @return void
     */
    protected function onClick() {
        $key = $this->getRequest('eventkey');

        if ($key == 'menu_trevi') {

            $this->responseText("嗨喽，欢迎您来到捉影许愿池，成功开启魔法按钮，魔法已生效。快与小影一起，开启您的魔幻之旅吧。\n".
"1、想看电影。在输入框回复DY#+想看的影片名称\n".
"2、想获取电影音乐资讯或下载链接。在输入框回复YY#+音乐描述+想要获取的音乐资讯，如：音乐名称/歌词/主唱/下载地址\n".
"3、想发起观影活动。在输入框回复HD#+活动时间/地点/方式\n".
"4、想在哪里观看电影。在输入框回复CD#+场地描述/场地地址\n".
"5、想了解或购买某一电影的服饰/玩偶/其他附属产品，在输入框回复CP#+电影名称+产品描述\n".
"6、想获取电影中的风景区资讯，在输入框回复FJ#+电影名称+景区描述\n");
        }
    }
    /**
     * 收到地理位置事件消息时触发，用于子类重写
     *
     * @return void
     */
    protected function onEventLocation() {}
    /**
     * 收到语音消息时触发，用于子类重写
     *
     * @return void
     */
    protected function onVoice() {}
    /**
     * 收到视频消息时触发，用于子类重写
     *
     * @return void
     */
    protected function onVideo() {
        $mediaID = $this->getRequest('MediaId');

        $result = $this->send_get('http://weixin.myline.cc/weixin/test/getTempResource?mediaID='.$mediaID);

        if ($result['status'] == 'y') {
            $this->responseText("上传到第二空间成功，请到此地址查看上传文件http://www.my2space.com/room/107.html#/2398");
        }
    }

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

        return json_decode($output, true);
    }

    /**
     * 扫描二维码时触发，用于子类重写
     *
     * @return void
     */
    protected function onScan() {
        $this->responseText('scan');
    }
    /**
     * 收到未知类型消息时触发，用于子类重写
     *
     * @return void
     */
    protected function onUnknown() {}
    /**
     * 回复文本消息
     *
     * @param  string  $content  消息内容
     * @param  integer $funcFlag 默认为0，设为1时星标刚才收到的消息
     * @return void
     */
    protected function responseText($content, $funcFlag = 0) {
        exit(new TextResponse($this->getRequest('fromusername'), $this->getRequest('tousername'), $content, $funcFlag));
    }
    /**
     * 回复音乐消息
     *
     * @param  string  $title       音乐标题
     * @param  string  $description 音乐描述
     * @param  string  $musicUrl    音乐链接
     * @param  string  $hqMusicUrl  高质量音乐链接，Wi-Fi 环境下优先使用
     * @param  integer $funcFlag    默认为0，设为1时星标刚才收到的消息
     * @return void
     */
    protected function responseMusic($title, $description, $musicUrl, $hqMusicUrl, $funcFlag = 0) {
        exit(new MusicResponse($this->getRequest('fromusername'), $this->getRequest('tousername'), $title, $description, $musicUrl, $hqMusicUrl, $funcFlag));
    }
    /**
     * 回复图文消息
     * @param  array   $items    由单条图文消息类型 NewsResponseItem() 组成的数组
     * @param  integer $funcFlag 默认为0，设为1时星标刚才收到的消息
     * @return void
     */
    protected function responseNews($items, $funcFlag = 0) {
        exit(new NewsResponse($this->getRequest('fromusername'), $this->getRequest('tousername'), $items, $funcFlag));
    }

    /**
     * 回复图片消息
     * @param  string   $media_id    媒体ID
     * @param  integer  $funcFlag 默认为0，设为1时星标刚才收到的消息
     * @return void
     */
    protected function responseImage($media_id, $funcFlag = 0) {
        exit(new ImageResponse($this->getRequest('fromusername'), $this->getRequest('tousername'), $media_id, $funcFlag));
    }
    /**
     * 分析消息类型，并分发给对应的函数
     *
     * @return void
     */
    public function run() {
        switch ($this->getRequest('msgtype')) {
            case 'event':
                switch ($this->getRequest('event')) {
                    case 'subscribe':
                        $this->onSubscribe();
                        break;
                    case 'unsubscribe':
                        $this->onUnsubscribe();
                        break;
                    case 'SCAN':
                        $this->onScan();
                        break;
                    case 'LOCATION':
                        $this->onEventLocation();
                        break;
                    case 'CLICK':
                        $this->onClick();
                        break;
                }
                break;
            case 'text':
                $this->onText();
                break;
            case 'image':
                $this->onImage();
                break;
            case 'location':
                $this->onLocation();
                break;
            case 'link':
                $this->onLink();
                break;
            case 'voice':
                $this->onVoice();
                break;
            case 'video':
                $this->onVideo();
                break;
            case 'shortvideo':
                $this->onVideo();
                break;
            default:
                $this->onUnknown();
                break;
        }
    }
    /**
     * 自定义的错误处理函数，将 PHP 错误通过文本消息回复显示
     * @param  int $level   错误代码
     * @param  string $msg  错误内容
     * @param  string $file 产生错误的文件
     * @param  int $line    产生错误的行数
     * @return void
     */
    protected function errorHandler($level, $msg, $file, $line) {
        if ( ! $this->debug) {
            return;
        }
        $error_type = array(
            // E_ERROR             => 'Error',
            E_WARNING           => 'Warning',
            // E_PARSE             => 'Parse Error',
            E_NOTICE            => 'Notice',
            // E_CORE_ERROR        => 'Core Error',
            // E_CORE_WARNING      => 'Core Warning',
            // E_COMPILE_ERROR     => 'Compile Error',
            // E_COMPILE_WARNING   => 'Compile Warning',
            E_USER_ERROR        => 'User Error',
            E_USER_WARNING      => 'User Warning',
            E_USER_NOTICE       => 'User Notice',
            E_STRICT            => 'Strict',
            E_RECOVERABLE_ERROR => 'Recoverable Error',
            E_DEPRECATED        => 'Deprecated',
            E_USER_DEPRECATED   => 'User Deprecated',
        );
        $template = <<<ERR
PHP 报错啦！
%s: %s
File: %s
Line: %s
ERR;
        $this->responseText(sprintf($template,
            $error_type[$level],
            $msg,
            $file,
            $line
        ));
    }

    protected function curl($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}
/**
 * 用于回复的基本消息类型
 */
abstract class WechatResponse {
    protected $toUserName;
    protected $fromUserName;
    protected $funcFlag;
    protected $template;
    public function __construct($toUserName, $fromUserName, $funcFlag) {
        $this->toUserName = $toUserName;
        $this->fromUserName = $fromUserName;
        $this->funcFlag = $funcFlag;
    }
    abstract public function __toString();
}
/**
 * 用于回复的文本消息类型
 */
class TextResponse extends WechatResponse {
    protected $content;
    public function __construct($toUserName, $fromUserName, $content, $funcFlag = 0) {
        parent::__construct($toUserName, $fromUserName, $funcFlag);
        $this->content = $content;
        $this->template = <<<XML
<xml>
  <ToUserName><![CDATA[%s]]></ToUserName>
  <FromUserName><![CDATA[%s]]></FromUserName>
  <CreateTime>%s</CreateTime>
  <MsgType><![CDATA[text]]></MsgType>
  <Content><![CDATA[%s]]></Content>
  <FuncFlag>%s</FuncFlag>
</xml>
XML;
    }
    public function __toString() {
        return sprintf($this->template,
            $this->toUserName,
            $this->fromUserName,
            time(),
            $this->content,
            $this->funcFlag
        );
    }
}
/**
 * 用于回复的音乐消息类型
 */
class MusicResponse extends WechatResponse {
    protected $title;
    protected $description;
    protected $musicUrl;
    protected $hqMusicUrl;
    public function __construct($toUserName, $fromUserName, $title, $description, $musicUrl, $hqMusicUrl, $funcFlag) {
        parent::__construct($toUserName, $fromUserName, $funcFlag);
        $this->title = $title;
        $this->description = $description;
        $this->musicUrl = $musicUrl;
        $this->hqMusicUrl = $hqMusicUrl;
        $this->template = <<<XML
<xml>
  <ToUserName><![CDATA[%s]]></ToUserName>
  <FromUserName><![CDATA[%s]]></FromUserName>
  <CreateTime>%s</CreateTime>
  <MsgType><![CDATA[music]]></MsgType>
  <Music>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
    <MusicUrl><![CDATA[%s]]></MusicUrl>
    <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
  </Music>
  <FuncFlag>%s</FuncFlag>
</xml>
XML;
    }
    public function __toString() {
        return sprintf($this->template,
            $this->toUserName,
            $this->fromUserName,
            time(),
            $this->title,
            $this->description,
            $this->musicUrl,
            $this->hqMusicUrl,
            $this->funcFlag
        );
    }
}
/**
 * 用于回复的图文消息类型
 */
class NewsResponse extends WechatResponse {
    protected $items = array();
    public function __construct($toUserName, $fromUserName, $items, $funcFlag) {
        parent::__construct($toUserName, $fromUserName, $funcFlag);
        $this->items = $items;
        $this->template = <<<XML
<xml>
  <ToUserName><![CDATA[%s]]></ToUserName>
  <FromUserName><![CDATA[%s]]></FromUserName>
  <CreateTime>%s</CreateTime>
  <MsgType><![CDATA[news]]></MsgType>
  <ArticleCount>%s</ArticleCount>
  <Articles>
    %s
  </Articles>
  <FuncFlag>%s</FuncFlag>
</xml>
XML;
    }
    public function __toString() {
        return sprintf($this->template,
            $this->toUserName,
            $this->fromUserName,
            time(),
            count($this->items),
            implode($this->items),
            $this->funcFlag
        );
    }
}

class ImageResponse extends WechatResponse {
    protected $items = array();
    public function __construct($toUserName, $fromUserName, $media_id, $funcFlag) {
        parent::__construct($toUserName, $fromUserName, $funcFlag);
        $this->media_id = $media_id;
        $this->template = <<<XML
<xml>
  <ToUserName><![CDATA[%s]]></ToUserName>
  <FromUserName><![CDATA[%s]]></FromUserName>
  <CreateTime>%s</CreateTime>
  <MsgType><![CDATA[image]]></MsgType>
  <Image>
      <MediaId><![CDATA[%s]]></MediaId>
  </Image>
  <FuncFlag>%s</FuncFlag>
</xml>
XML;
    }
    public function __toString() {
        return sprintf($this->template,
            $this->toUserName,
            $this->fromUserName,
            time(),
            $this->media_id,
            $this->funcFlag
        );
    }
}

/**
 * 单条图文消息类型
 */
class NewsResponseItem {
    protected $title;
    protected $description;
    protected $picUrl;
    protected $url;
    protected $template;
    public function __construct($title, $description, $picUrl, $url) {
        $this->title = $title;
        $this->description = $description;
        $this->picUrl = $picUrl;
        $this->url = $url;
        $this->template = <<<XML
<item>
  <Title><![CDATA[%s]]></Title>
  <Description><![CDATA[%s]]></Description>
  <PicUrl><![CDATA[%s]]></PicUrl>
  <Url><![CDATA[%s]]></Url>
</item>
XML;
    }
    public function __toString() {
        return sprintf($this->template,
            $this->title,
            $this->description,
            $this->picUrl,
            $this->url
        );
    }
}
