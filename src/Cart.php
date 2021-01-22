<?php

namespace Heatlai\CartDiscountDemo;

use Tightenco\Collect\Support\Collection;

class Cart {

    /** @var DiscountRule[] $discountRule */
    public array $discountRules = [];
    /** * @var Product[] $products */
    public array $products = [];
    /** @var Discount[] $discounts */
    public array $discounts = [];
    protected int $productIndex = 0;
    public array $skus = [];

    public function __construct($discountRules)
    {
        $this->discountRules = $discountRules;
    }

    public function addProduct($sku)
    {
        $this->skus[] = $sku;
        $this->resetProducts();
        $this->discountProcess();
    }

    public function getVisibleProducts(string $exclusiveTag = null): Collection
    {
        if ( $exclusiveTag === null || $exclusiveTag === '') {
            return collect($this->products);
        }

        return collect($this->products)->filter(function (Product $product) use ($exclusiveTag) {
            return ! collect($product->tags)->contains($exclusiveTag);
        });
    }

    public function getDiscountRules(): Collection
    {
        return collect($this->discountRules);
    }

    public function getDiscounts(): Collection
    {
        return collect($this->discounts);
    }

    protected function resetProducts()
    {
        // reset products state
        $this->products = [];
        $index = 0;
        foreach ($this->skus as $skuId) {
            $p = Product::findOrFail($skuId);
            $p->index = ++$index;
            $this->products[] = $p;
        }
    }

    protected function discountProcess()
    {
        // reset
        $this->discounts = [];

        // 跑折扣規則
        foreach ($this->getDiscountRules() as $discountRule) {
            /** @var Discount[] $discounts */
            $discounts = iterator_to_array($discountRule->process($this));
            $this->discounts = array_merge($this->discounts, $discounts);

            // 把 排除tag 加到 product 上面 方便後面折扣規則 filter 可套用規則的商品
            if ( $discountRule->exclusiveTag )
            {
                foreach ($discounts as $discount) {
                    foreach ($discount->products as $product) {
                        $product->tags[] = $discountRule->exclusiveTag;
                    }
                }
            }
        }
        return $this;
    }

    public function getPurchaseProducts(): Collection
    {
        return collect($this->products);
    }

    public function getFreeProducts(): Collection
    {
        return collect($this->discounts)->pluck('freeProducts')->flatten(1);
    }

    /**
     * 原始金額
     * @return mixed
     */
    public function originalTotalPrice()
    {
        return array_reduce($this->products, function ($total, Product $product) {
            $total += $product->price;
            return $total;
        }, 0);
    }

    /**
     * 折扣金額
     * @return mixed
     */
    public function discountAmount()
    {
        return array_reduce($this->discounts, function ($total, Discount $discount) {
            $total += $discount->amount;
            return $total;
        }, 0);
    }

    /**
     * 結帳金額
     * @return mixed
     */
    public function totalPrice()
    {
        return $this->originalTotalPrice() - $this->discountAmount();
    }

    public function print()
    {
        $p = static function($str) {
            echo $str.PHP_EOL;
        };

        $p("購買商品:");
        $p("----------------------------------------------------");
        foreach ($this->getPurchaseProducts() as $product) {
            $p(" - {$product->index}, [{$product->sku}] {$product->name} \${$product->price}");
        }
        $p("活動:");
        $p("----------------------------------------------------");
        foreach ($this->discounts as $discount) {
            if( $discount->amount > 0 || $discount->freeProducts ) {
                $p(" - {$discount->rule->name}, {$discount->rule->note}");
                foreach ($discount->products as $product) {
                    $p("   * 符合商品: {$product->index}, [{$product->sku}] {$product->name} {$product->getTagsString()}");
                }
            }
            if( $discount->amount > 0 ) {
                $p("   => 折扣 \${$discount->amount}");
            }
            if( $discount->freeProducts ) {
                foreach ($discount->freeProducts as $product) {
                    $p("   => 贈品 [{$product->sku}] {$product->name}");
                }
            }
        }
        $p("----------------------------------------------------");
        $p("原始金額: $".$this->originalTotalPrice());
        $p("折扣金額: $".$this->discountAmount());
        $p("結帳金額: $".$this->totalPrice());
    }
}