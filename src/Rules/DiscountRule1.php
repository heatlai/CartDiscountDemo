<?php

namespace Heatlai\CartDiscountDemo\Rules;

use Heatlai\CartDiscountDemo\Cart;
use Heatlai\CartDiscountDemo\Discount;
use Heatlai\CartDiscountDemo\DiscountRule;
use Heatlai\CartDiscountDemo\Product;
use Generator;

class DiscountRule1 extends DiscountRule
{
    private Product $product;
    private int $minBuyAmount;
    private float $discountPrice;

    public function __construct(string $sku, int $minBuyAmount, float $discountPrice, string $exclusiveTag = null)
    {
        $this->product = Product::findOrFail($sku);
        $this->discountPrice = $discountPrice;
        $this->minBuyAmount = $minBuyAmount;
        $this->name = "買 {$this->product->name} 滿 {$minBuyAmount} 件享優惠折扣 單價 {$discountPrice} 元";
        $this->note = "指定商品滿件折扣價";
        $this->exclusiveTag = $exclusiveTag;
    }

    public function process(Cart $cart): Generator
    {
        $matches = collect();
        foreach ($cart->getVisibleProducts($this->exclusiveTag) as $product) {
            if( $product->sku === $this->product->sku ) {
                $matches->push($product);
            }
        }
        if( $matches->count() >= $this->minBuyAmount ) {
            yield new Discount(
                $amount = $matches->reduce(
                    function ($total, Product $product) {
                        $total += max($product->price - $this->discountPrice, 0);
                        return $total;
                    },
                    0
                ),
                $products = $matches->toArray(),
                $rule = $this,
            );
        }
    }
}