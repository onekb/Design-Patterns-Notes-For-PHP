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
