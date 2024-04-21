<?php

namespace App\Factory;

use App\Entity\Car;

class CarFactory
{
    public function create(string $make, string $model, int $year, string $engine, float $price, string $vin, string $status, ?string $description = null): Car
    {
        $car = new Car();
        $car->setMake($make);
        $car->setModel($model);
        $car->setYear($year);
        $car->setEngine($engine);
        $car->setPrice($price);
        $car->setVin($vin);
        $car->setStatus($status);
        $car->setDescription($description);
        
        return $car;
    }
}
