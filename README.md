# 購物車折扣計算 Demo

## How ?

```php
use Heatlai\CartDiscountDemo\Cart;
use Heatlai\CartDiscountDemo\Rules\DiscountRule2;

$discountRules = [
    new DiscountRule2('A0001', 2, 1), // 乖乖(五香) 買 2 送 1
];

$cart = new Cart($discountRules);

// buy something
$products = [
    'A0001',
    'A0001',
    'A0001',
];
foreach ($products as $sku) {
    $cart->addProduct($sku);
}

// print results
$cart->discountProcess()->print();
```

## Tests
--testdox : show test name
```shell
./vendor/bin/phpunit --testdox
```