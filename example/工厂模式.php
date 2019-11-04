<?php
//抽象模板消息类
abstract class TemplateMessage
{
    //获取发送主体
    abstract public function getObject(): WechatConnector;

    //发送
    public function send($content): void
    {
        //获取对象
        $object = $this->getObject();
        //组装结构体
        $structureBody = $object->assemblyStructure($content);
        //创建数据
        $object->createData($structureBody);
        //发送内容
        $object->send();
    }
}

//公众号消息 继承 抽象模板消息类
class MpMessage extends TemplateMessage
{
    private $account; //账号信息
    public function __construct(array $account)
    {
        $this->account = $account;
    }

    public function getObject(): WechatConnector
    {
        //创建公众号
        return new Mp($this->account);
    }
}

//小程序消息 继承 抽象模板消息类
class MiniMessage extends TemplateMessage
{
    private $account; //账号信息
    public function __construct(array $account)
    {
        $this->account = $account;
    }

    public function getObject(): WechatConnector
    {
        //创建小程序
        return new Mini($this->account);
    }
}

//微信接口
interface WechatConnector
{
    //生成结构体
    public function assemblyStructure(array $content): string;
    //创建数据
    public function createData(): void;
    //发送模板消息
    public function send(): void;
}

//公众号类 使用微信接口
class Mp implements WechatConnector
{
    private $account; //账号信息
    private $structureBody; //模板消息结构体
    public function __construct($account)
    {
        $this->account = $account;
        //执行创建类
        echo "创建公众号类\n";
    }

    //生成结构体
    public function assemblyStructure(array $content): string
    {
        $content['type'] = '公众号类型';
        echo "生成公众号结构体\n";
        return $this->structureBody = json_encode($content);
    }

    //创建新数据
    public function createData(): void
    {
        //存数据库操作
        echo "数据存入数据库\n";
    }

    //发送模板消息
    public function send(): void
    {
        echo "发送公众号模板消息{$this->structureBody}\n";
    }
}

class Mini implements WechatConnector
{
    private $account; //账号信息
    private $structureBody; //模板消息结构体
    public function __construct($account)
    {
        $this->account = $account;
        //执行创建类
        echo "创建小程序类\n";
    }
    public function assemblyStructure(array $content): string
    {
        $content['type'] = '小程序类型';
        echo "生成小程序结构体\n";
        return $this->structureBody = json_encode($content);
    }
    public function createData(): void
    {
        //存数据库操作
        echo "数据存入数据库\n";
    }

    public function send(): void
    {
        echo "发送小程序模板消息{$this->structureBody}\n";
    }
}

function clientCode(TemplateMessage $creator, string $openid)
{
    $creator->send([
        'openid' => $openid,
        'message' => [
            'text1' => '内容1',
            'text2' => '内容2',
        ]
    ]);
}

$account = [
    'appid' => '4321dcba...',
    'secretKey' => 'poiu0987'
];
$openid = 'abcd1234...';
clientCode(new MpMessage($account), $openid);
clientCode(new MiniMessage($account), $openid);
