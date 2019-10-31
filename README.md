# Design-Patterns-Notes-For-PHP
PHP设计模式笔记

> 本文是为了巩固自己对设计模式的理解，便于我自己按照自己思维去检索我所需要的模式。不建议初学者盲目跟着笔记内容学习。

# 行为型
## 观察者模式

适用于主流程只处理自己的事，在处理前/后通知其观察者做对应操作，在这之前要把观察者先注入进来。
<br/>
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