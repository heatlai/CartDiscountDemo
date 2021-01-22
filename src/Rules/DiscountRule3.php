<?php

namespace Heatlai\CartDiscountDemo\Rules;

use Heatlai\CartDiscountDemo\Cart;
use Heatlai\CartDiscountDemo\Discount;
use Heatlai\CartDiscountDemo\DiscountRule;
use Heatlai\CartDiscountDemo\Product;
use Generator;

class DiscountRule3 extends DiscountRule
{
    private Product $product;
    private Product $giftProduct;
    private int $buyAmount;
    private int $giftAmount;

    public function __construct(string $buySku, int $buyAmount, string $giftSku, int $giftAmount, string $exclusiveTag = null)
    {
        $this->product = Product::findOrFail($buySku);
        $this->giftProduct = Product::findOrFail($giftSku);
        $this->buyAmount = $buyAmount;
        $this->giftAmount = $giftAmount;
        $this->name = "買 {$this->product->name} {$buyAmount} 件送 {$this->giftProduct->name} {$giftAmount} 件";
        $this->note = "買A送B";
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
                $freeProducts = [];
                foreach (range(1, $this->giftAmount) as $i) {
                    $freeProducts[] = $this->giftProduct;
                }
                yield new Discount(
                    $amount = 0,
                    $products = $matches->toArray(),
                    $rule = $this,
                    $freeProducts,
                );

                // reset matches
                $matches = collect();
            }
        }
    }
}