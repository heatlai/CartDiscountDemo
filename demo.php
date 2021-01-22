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
    new DiscountRule1('A0001', 2, 5), // 買 乖乖(五香) 滿 2 件享優惠折扣 單價 5 元
    new DiscountRule2('A0001', 2, 1), // 乖乖(五香) 買 2 送 1
    new DiscountRule2('B0001', 3, 1), // 老虎牙子 買 3 送 1
    new DiscountRule3('C0001', 1, 'B0003', 1), // 買 卡拉雞腿堡 送 蜂蜜牛奶
    new DiscountRule4('4度C', 'D0001', 1), // 結帳包含 4度C 商品送 衛生紙 1 件
];
$cart = new Cart($discountRules);
foreach ($products as $sku) {
    $cart->addProduct($sku);
}
$cart->print();