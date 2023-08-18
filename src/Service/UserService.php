<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class UserService
{

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserById(int $id): User
    {
        return $this->userRepository->findOneBy(['id' => $id]);
    }
}