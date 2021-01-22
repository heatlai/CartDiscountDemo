<?php

namespace Heatlai\CartDiscountDemo;

use Generator;

abstract class DiscountRule
{
    public string $name = '';
    public string $note = '';
    public ?string $exclusiveTag = null;

    abstract public function process(Cart $cart): Generator;
}