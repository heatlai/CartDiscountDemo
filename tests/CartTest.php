<?php

use Heatlai\CartDiscountDemo\Product;
use PHPUnit\Framework\TestCase;
use Heatlai\CartDiscountDemo\Cart;
use Heatlai\CartDiscountDemo\Rules\DiscountRule1;
use Heatlai\CartDiscountDemo\Rules\DiscountRule2;
use Heatlai\CartDiscountDemo\Rules\DiscountRule3;
use Heatlai\CartDiscountDemo\Rules\DiscountRule4;

class CartTest extends TestCase {
    public function testDiscountRule1()
    {
        $discountRules = [
            new DiscountRule1('A0001', 2, 5), // 買 乖乖(五香) 滿 2 件享優惠折扣 單價 5 元
        ];

        $cart = new Cart($discountRules);
        $products = ['A0001', 'A0001', 'A0001',];
        foreach ($products as $sku) {
            $cart->addProduct($sku);
        }
        $cart->discountProcess();

        $this->assertEquals(15, $cart->totalPrice());
    }

    public function testDiscountRule2()
    {
        $discountRules = [
            new DiscountRule2('B0001', 3, 1), // 老虎牙子 買 3 送 1 (內送)
        ];

        $cart = new Cart($discountRules);
        $products = ['B0001', 'B0001', 'B0001','B0001','B0001','B0001','B0001',];
        foreach ($products as $sku) {
            $cart->addProduct($sku);
        }
        $cart->discountProcess();

        $this->assertEquals(125, $cart->totalPrice());
    }

    public function testDiscountRule3()
    {
        $discountRules = [
            new DiscountRule3('C0001', 1, 'B0003', 1), // 買 卡拉雞腿堡 送 蜂蜜牛奶
        ];

        $cart = new Cart($discountRules);
        $products = ['C0001'];
        foreach ($products as $sku) {
            $cart->addProduct($sku);
        }
        $cart->discountProcess();

        $this->assertEquals(true, $cart->getFreeProducts()->filter(function(Product $product){
            return $product->sku === 'B0003';
        })->isNotEmpty());
    }

    public function testDiscountRule4()
    {
        $discountRules = [
            new DiscountRule4('4度C', 'D0001', 1), // 結帳包含 4度C 商品送 衛生紙 2 件
        ];

        $cart = new Cart($discountRules);
        $products = ['C0001', 'C0002'];
        foreach ($products as $sku) {
            $cart->addProduct($sku);
        }
        $cart->discountProcess();

        $cart->print();

        $this->assertEquals(1, $cart->getFreeProducts()->filter(function(Product $product){
            return $product->sku === 'D0001';
        })->count());
    }
}