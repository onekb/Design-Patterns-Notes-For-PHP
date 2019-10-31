<?php

//部件
interface Component
{
    public function operation(): string;
}

//具体部件 人
class People implements Component
{
    public function operation(): string
    {
        return "裸体 人";
    }
}

//基础装饰 衣服
class Clothes implements Component
{
    protected $component;

    public function __construct(Component $component)
    {
        $this->component = $component;
    }

    public function operation(): string
    {
        return $this->component->operation();
    }
}

//具体装饰 T恤
class TShirtClothes extends Clothes
{
    public function operation(): string
    {
        return "T恤(" . parent::operation() . ")";
    }
}

//具体装饰 外套
class CoatClothes extends Clothes
{
    public function operation(): string
    {
        return "外套(" . parent::operation() . ")";
    }
}

//客户端
function clientCode(Component $component)
{
    // 业务代码

    echo "你穿着的是: " . $component->operation();

    // 业务代码
}

//简单调用 没有装饰器
//实例化
$simple = new People;
clientCode($simple);

//装饰器调用
$simple = new People;
$tShirtClothes = new TShirtClothes($simple);
$coatClothes = new CoatClothes($tShirtClothes);
clientCode($coatClothes);
