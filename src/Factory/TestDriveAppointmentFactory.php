<?php

namespace App\Factory;

use App\Entity\TestDriveAppointment;
use App\Entity\Car;
use App\Entity\User; // Update from Customer to User
use DateTimeInterface;

class TestDriveAppointmentFactory
{
    public function create(Car $car, User $user, DateTimeInterface $scheduledTime, string $status): TestDriveAppointment
    {
        $appointment = new TestDriveAppointment();
        $appointment->setCar($car);
        $appointment->setUser($user); // Update method to setUser()
        $appointment->setScheduledTime($scheduledTime);
        $appointment->setStatus($status);

        return $appointment;
    }
}
