<?php

namespace Heatlai\CartDiscountDemo\Rules;

use Heatlai\CartDiscountDemo\Cart;
use Heatlai\CartDiscountDemo\Discount;
use Heatlai\CartDiscountDemo\DiscountRule;
use Heatlai\CartDiscountDemo\Product;
use Generator;

class DiscountRule4 extends DiscountRule
{
    private Product $giftProduct;
    private int $giftAmount;
    private string $targetTag;

    public function __construct(string $targetTag, string $giftSku, int $giftAmount, string $exclusiveTag = null)
    {
        $this->giftProduct = Product::findOrFail($giftSku);
        $this->giftAmount = $giftAmount;
        $this->name = "結帳包含 {$targetTag} 標籤商品送 {$this->giftProduct->name} {$giftAmount} 件";
        $this->note = "結帳包含A送B";
        $this->exclusiveTag = $exclusiveTag;
        $this->targetTag = $targetTag;
    }

    public function process(Cart $cart): Generator
    {
        $matches = $cart->getVisibleProducts($this->exclusiveTag)->filter(function (Product $product) {
                return collect($product->tags)->contains($this->targetTag);
            });
        if( $matches->isNotEmpty() ) {
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
        }
    }
}