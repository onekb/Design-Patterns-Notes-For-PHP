# Design-Patterns-Notes-For-PHP
PHP设计模式笔记

> 本文是为了巩固自己对设计模式的理解，便于我自己按照自己思维去检索我所需要的模式。不建议初学者盲目跟着笔记内容学习。

# 行为型

## 观察者模式

适用于主流程只处理自己的事，在处理前/后通知其观察者做对应操作，在这之前要把观察者先注入进来。类似于钩子的方式。要使用到PHP自带的SPL。

下面为代码示例

```php
<?php

use SplSubject;
use SplObserver;

//被观察者
class User implements SplSubject
{
    protected $observers = NULL;
    public function __construct()
    {
        $this->observers = new SplObjectStorage();
    }

    //加入观察者
    public function attach(SplObserver $observer)
    {
        $this->observers->attach($observer);
    }

    //去掉观察者
    public function detach(SplObserver $observer)
    {
        $this->observers->detach($observer);
    }

    //通知观察者
    public function notify()
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    //业务事件
    public function login()
    {
        //登录操作...
        //登录成功
        $this->notify();
    }
}

//观察者 积分操作
class Integral implements SplObserver
{
    //业务事件
    public function update(SplSubject $subject)
    {
        //业务操作
        //加积分
    }
}

//观察者 日志操作
class Log implements SplObserver
{
    //业务事件
    public function update(SplSubject $subject)
    {
        //业务操作
        //加日志
    }
}

//实例化
$user = new User();
$user->attach(new Integral()); //加入观察者 积分类
$user->attach(new Log()); //加入观察者 日志类

//业务操作 登录
$user->login();

```

---

## 职责链模式

适用于处理某件具体的事情，但是根据事情大小不同找对应的操作。比如『举报』这操作上，从禁言到封号再到报警处理都是一级级从小到大的。

下面为代码示例

```php
<?php

interface report
{
    public function preocess();
}

//版主
class Moderator implements report
{
    protected $level = 'warning'; //等级 警告
    protected $top = 'Admin'; //上级 Admin管理员
    public function preocess($level)
    {
        if ($this->level == $level) {
            //有权限操作
            //禁言操作
        } else
            //权限不够
            //移交处理
            new $this->top($level);
    }
}

//管理员
class Admin implements report
{
    protected $level = 'sanction'; //等级 制裁
    protected $top = 'Police'; //上级 Police警察
    public function preocess($level)
    {
        if ($this->level == $level) {
            //有权限操作
            //封号操作
        } else
            //权限不够
            //移交处理
            new $this->top($level);
    }
}

//警察
class Police implements report
{
    protected $level = 'serious'; //等级 严重
    protected $top = ''; //上级 暂无 如果以后有 可以继续延伸
    public function preocess($level)
    {
        //已经是最大的了 直接处理
        //立案操作
    }
}

//业务操作
//举报丢给最低级的运行，它处理不了再逐步上报
$level = 'serious'; //举报等级
$report = new Moderator($level);#举报serious 严重 #当然也可以用数字，但是我喜欢用字符串

```

---

## 策略模式

跟『工厂模式』有点像。策略是注重行为。工厂是创建后，一阵捣腾，就给你处理好了。策略是创建后，要什么自己操作。

下面为代码示例

```php
<?php

//定义接口
interface PayInterface
{
    public function pay($orderData);
    public function queueWithdrawal($orderData);
}

//微信支付类
class WechatPay implements PayInterface
{
    public function pay($orderData)
    {
        //微信统一支付
        return;
    }

    public function queueWithdrawal($orderData)
    {
        //加队列提醒
        return;
    }
}

//支付宝支付类
class AliPay implements PayInterface
{
    public function pay($orderData)
    {
        //支付宝统一支付
        return;
    }

    public function queueWithdrawal($orderData)
    {
        //加队列提醒
        return;
    }
}

//虚拟 支付类
class Pay
{
    protected $pay;

    public function __construct(PayInterface $obj)
    {
        $this->pay = $obj;
    }

    //调用统一支付
    public function pay($orderData)
    {
        return $this->pay->pay($orderData);
    }

    public function queueWithdrawal($orderData)
    {
        return $this->pay->queueWithdrawal($orderData);
    }
}

//业务操作
$type = 'Wechat'; //使用微信支付
switch ($type) {
    case 'Wechat':
        $obj = new WechatPay();
        break;
    case 'Ali':
        $obj = new AliPay();
        break;
    default:
        throw \Exception('错误类型');
}
$pay = new Pay($obj); //实例化
$orderData = [ //支付信息
    'order_number' => md5(time()),
    'money' => 1
];
$pay->pay($orderData); //调用统一支付
$pay->queueWithdrawal($orderData); //调用队列提醒

```