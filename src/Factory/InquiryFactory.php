<?php

namespace App\Factory;

use App\Entity\Inquiry;
use App\Entity\User;
use App\Entity\Car;

class InquiryFactory
{
    public static function create(User $sender, User $receiver, string $message, ?Car $car = null): Inquiry
    {
        $inquiry = new Inquiry();
        $inquiry->setSender($sender);
        $inquiry->setReceiver($receiver);
        $inquiry->setMessage($message);
        $inquiry->setCar($car);
        
        return $inquiry;
    }
}
