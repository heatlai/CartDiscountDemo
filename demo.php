<?php
require __DIR__ . '/vendor/autoload.php';

use Heatlai\CartDiscountDemo\Cart;
use Heatlai\CartDiscountDemo\Rules\DiscountRule1;
use Heatlai\CartDiscountDemo\Rules\DiscountRule2;
use Heatlai\CartDiscountDemo\Rules\DiscountRule3;
use Heatlai\CartDiscountDemo\Rules\DiscountRule4;

$products = [
    'A0001',
    'A0001',
    'A0001',
    'A0001',
    'A0001',
    'B0001',
    'B0001',
    'B0001',
    'B0001',
    'B0001',
    'B0001',
    'B0001',
    'C0001',
];

$discountRules = [
    new DiscountRule1('A0001', 5, 10), // 買 乖乖(五香) 滿 5 件 單價改為 10 元
    new DiscountRule2('B0001', 3, 1), // 老虎牙子 買 3 送 1 (內送)
    new DiscountRule3('C0001', 1, 'B0003', 1), // 買 卡拉雞腿堡 1 件 送 蜂蜜牛奶 1 件
    new DiscountRule4('4度C', 'D0001', 1), // 結帳包含 4度C 商品送 衛生紙 1 件
];
$cart = new Cart($discountRules);
foreach ($products as $sku) {
    $cart->addProduct($sku);
}
$cart->print();