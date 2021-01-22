<?php

namespace Heatlai\CartDiscountDemo\Rules;

use Heatlai\CartDiscountDemo\Cart;
use Heatlai\CartDiscountDemo\Discount;
use Heatlai\CartDiscountDemo\DiscountRule;
use Heatlai\CartDiscountDemo\Product;
use Generator;

class DiscountRule2 extends DiscountRule
{
    private Product $product;
    private int $buyAmount;
    private int $freeAmount;

    public function __construct(string $sku, int $buyAmount, int $freeAmount, string $exclusiveTag = null)
    {
        $this->product = Product::findOrFail($sku);
        $this->buyAmount = $buyAmount;
        $this->freeAmount = $freeAmount;
        $this->name = "{$this->product->name} 買 {$buyAmount} 送 {$freeAmount}";
        $this->note = "指定商品買n件送y件(贈品包含在購買數量n件內)";
        $this->exclusiveTag = $exclusiveTag;
    }

    public function process(Cart $cart): Generator
    {
        $matches = collect();
        foreach ($cart->getVisibleProducts($this->exclusiveTag) as $product) {
            if( $product->sku === $this->product->sku ) {
                $matches->push($product);
            }

            if( $matches->count() === $this->buyAmount ) {
                /** @var Product $product */
                $product = $matches->first();
                yield new Discount(
                    $amount = $product->price,
                    $products = $matches->toArray(),
                    $rule = $this,
                );

                // reset matches
                $matches = collect();
            }
        }
    }
}