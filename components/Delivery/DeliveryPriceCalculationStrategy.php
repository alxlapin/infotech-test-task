<?php

declare(strict_types=1);

namespace app\components\Delivery;

interface DeliveryPriceCalculationStrategy
{
    public function calculate(int $distance): float;
}