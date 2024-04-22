<?php

namespace App\Factory;

use App\Entity\Financing;
use App\Entity\Car;
use App\Entity\User;

class FinancingFactory
{
    public static function create(Car $car, User $user, float $amount, float $interestRate, int $term): Financing
    {
        $financing = new Financing();
        $financing->setCar($car);
        $financing->setUser($user);
        $financing->setAmount($amount);
        $financing->setInterestRate($interestRate);
        $financing->setTerm($term);
        $financing->setStatus('pending'); // Default status

        return $financing;
    }
}
