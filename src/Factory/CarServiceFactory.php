<?php

namespace App\Factory;

use App\Entity\CarService;

class CarServiceFactory
{
    public static function create(): CarService
    {
        return new CarService();
    }
}
