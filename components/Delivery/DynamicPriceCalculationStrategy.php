<?php

declare(strict_types=1);

namespace app\components\Delivery;

use InvalidArgumentException;

class DynamicPriceCalculationStrategy implements DeliveryPriceCalculationStrategy
{
    /**
     * @var array<int, float>
     */
    private array $distancePrices = [];

    public function calculate(int $distance): float
    {
        if ($this->distancePrices === []) {
            throw new EmptyDistancePricesException();
        }

        /** @var array<int, float> $reversedDistancePrices */
        $reversedDistancePrices = array_reverse($this->distancePrices, true);
        $result = 0;
        foreach ($reversedDistancePrices as $startDistancePoint => $price) {
            if ($distance < $startDistancePoint) {
                continue;
            }

            $result += ($distance - $startDistancePoint) * $price;

            $distance = $startDistancePoint;
        }

        return $result;
    }

    /**
     * @param int $startDistancePoint позиция, с начала которой действует цена.
     * @param float $price
     * @return void
     */
    public function setDistancePrice(int $startDistancePoint, float $price): void
    {
        if ($this->distancePrices === [] && $startDistancePoint !== 0) {
            throw new InvalidArgumentException('Distance prices must start from 0');
        }

        if ($startDistancePoint < 0 || $price < 0) {
            throw new InvalidArgumentException('Start point and price must be >= 0');
        }

        $prevStartPoint = array_key_last($this->distancePrices);
        if ($startDistancePoint <= $prevStartPoint && $prevStartPoint !== null) {
            throw new InvalidArgumentException('Start point must be greated than previous start point');
        }

        $this->distancePrices[$startDistancePoint] = $price;
    }
}