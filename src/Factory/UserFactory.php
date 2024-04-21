<?php

namespace App\Factory;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function create(string $email, string $password, array $roles, string $name = null, string $address = null, string $phone = null): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        $user->setRoles($roles);

        // Optionally set additional information
        if ($name) {
            $user->setName($name);
        }
        if ($address) {
            $user->setAddress($address);
        }
        if ($phone) {
            $user->setPhone($phone);
        }

        return $user;
    }
}
