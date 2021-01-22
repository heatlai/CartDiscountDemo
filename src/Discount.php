<?php

namespace Heatlai\CartDiscountDemo;

class Discount
{
    /** @var float $amount 折扣金額 */
    public float $amount;
    /** @var Product[] $products 折扣商品 */
    public array $products;
    /** @var DiscountRule $rule 折扣規則 */
    public DiscountRule $rule;
    /** @var Product[] $freeProducts 贈品 */
    public array $freeProducts;

    public function __construct($amount, $products, $rule, $freeProducts = [])
    {
        $this->amount = $amount;
        $this->products = $products;
        $this->rule = $rule;
        $this->freeProducts = $freeProducts;
    }
}