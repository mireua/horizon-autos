<?php

namespace App\Factory;

use App\Entity\Sale;

class SaleFactory
{
    public static function createSale(): Sale
    {
        return new Sale();
    }
}
