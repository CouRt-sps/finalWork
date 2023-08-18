<?php

namespace App\Service;

use App\Repository\DiscountsRepository;

class DiscountsService
{
    private DiscountsRepository $discountsRepository;

    public function __construct(DiscountsRepository $discountsRepository)
    {
        $this->discountsRepository = $discountsRepository;
    }

    public function getDiscounts(): array
    {
        return $this->discountsRepository->findAll();
    }
    public function getAllWithSearchQuery(?string $search): object
    {
        return $this->discountsRepository->findAllWithSearchQuery($search);
    }
}