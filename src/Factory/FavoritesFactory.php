<?php

namespace App\Factory;

use App\Entity\Car;
use App\Entity\Favorites;
use App\Entity\User;

class FavoritesFactory
{
    public function create(Car $car, User $user): Favorites
    {
        $favoriteCar = new Favorites();
        $favoriteCar->setCar($car);
        $favoriteCar->setUser($user);

        return $favoriteCar;
    }
}
