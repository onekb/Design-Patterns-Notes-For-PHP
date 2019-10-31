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
