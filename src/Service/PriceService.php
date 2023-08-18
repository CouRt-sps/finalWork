<?php

namespace App\Service;

use App\Repository\PriceRepository;

class PriceService
{
    /**
     * @var PriceRepository
     */
    private $priceRepository;
    private SellerService $sellerService;

    public function __construct(PriceRepository $priceRepository, SellerService $sellerService)
    {
        $this->priceRepository = $priceRepository;
        $this->sellerService = $sellerService;
    }

    public function getMiddlePrice($productId): string
    {
        return $this->priceRepository->findMiddlePriceProduct($productId);
    }
    public function getMinPrice(int $productId): ?int
    {
        return $this->priceRepository->findMinPriceProduct($productId);
    }
}