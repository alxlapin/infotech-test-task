<?php

declare(strict_types=1);

namespace app\components\Delivery;

class DeliveryPriceCalculationService
{
    public function __construct(private DeliveryPriceCalculationStrategy $strategy)
    {
    }

    public function calculate(int $kilometers): float
    {
        return $this->strategy->calculate($kilometers);
    }
}