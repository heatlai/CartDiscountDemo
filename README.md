# 便利商店購物車折扣計算 Demo

## How ?

```php
use Heatlai\CartDiscountDemo\Cart;
use Heatlai\CartDiscountDemo\Rules\DiscountRule3;

$discountRules = [
    new DiscountRule3('C0001', 1, 'B0003', 1), // 買 卡拉雞腿堡 1 件 送 蜂蜜牛奶 1 件
];

$cart = new Cart($discountRules);

// buy something
$products = [
    'C0001',
];
foreach ($products as $sku) {
    $cart->addProduct($sku);
}

// print results
$cart->print();
```

## Tests
--testdox : show test name
```shell
./vendor/bin/phpunit --testdox
```
