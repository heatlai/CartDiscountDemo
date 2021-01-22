<?php

namespace Heatlai\CartDiscountDemo;

class Product
{
    public int $index = 0;
    public string $sku = '';
    public string $name = '';
    public float $price = 0;
    public array $tags = [];

    // 假裝是 DB
    private static $datastore = [
        // primaryKey => record
        'A0001' => ['sku' => 'A0001', 'name' => '乖乖(五香)', 'price' => 20, 'tags' => array()],
        'A0002' => ['sku' => 'A0002', 'name' => '孔雀捲心餅(焦糖瑪奇朵)', 'price' => 45, 'tags' => array()],
        'B0001' => ['sku' => 'B0001', 'name' => '老虎牙子', 'price' => 25, 'tags' => array()],
        'B0002' => ['sku' => 'B0002', 'name' => '蠻牛', 'price' => 30, 'tags' => array()],
        'B0003' => ['sku' => 'B0003', 'name' => '蜂蜜牛奶', 'price' => 35, 'tags' => array()],
        'C0001' => ['sku' => 'C0001', 'name' => '卡拉雞腿堡', 'price' => 40, 'tags' => array('4度C')],
        'C0002' => ['sku' => 'C0002', 'name' => '御飯糰(哇沙米鮭魚)', 'price' => 35, 'tags' => array('4度C')],
        'D0001' => ['sku' => 'D0001', 'name' => '舒潔衛生紙', 'price' => 10, 'tags' => array('衛生紙')],
    ];

    public function __construct($attrs)
    {
        foreach ($attrs as $key => $attr) {
            if (property_exists(static::class, $key)) {
                $this->{$key} = $attr;
            }
        }
    }

    public static function findOrFail($sku)
    {
        return isset(static::$datastore[$sku])
            ? new static(static::$datastore[$sku])
            : throw new \Exception('Not Found.');
    }

    public function getTagsString()
    {
        if (! $this->tags) {
            return "";
        }
        return ", Tags: " . collect($this->tags)->map(
                function ($tag) {
                    return "#{$tag}";
                }
            )->join(',');
    }
}