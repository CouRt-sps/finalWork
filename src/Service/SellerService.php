<?php

namespace App\Service;


use App\Entity\Seller;
use App\Repository\SellerRepository;

class SellerService
{
    private SellerRepository $sellerRepository;

    public function __construct(SellerRepository $sellerRepository)
    {
        $this->sellerRepository = $sellerRepository;
    }

    public function getSellerBySlug(string $slug): Seller
    {
        return $this->sellerRepository->findOneBy(['slug' => $slug]);
    }
    public function getAllWithSearchQuery(?string $search): object
    {
        return $this->sellerRepository->findAllWithSearchQuery($search);
    }
}