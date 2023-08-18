<?php

namespace App\Service;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ProductsService
{
    private ProductsRepository $productsRepository;
    private PriceService $priceService;
    private SellerService $sellerService;

    public function __construct(
        ProductsRepository $productsRepository,
        PriceService $priceService,
        SellerService $sellerService
    ) {
        $this->productsRepository = $productsRepository;
        $this->priceService = $priceService;
        $this->sellerService = $sellerService;
    }
    public function getOneLimitedEdition(): Products
    {
        return $this->productsRepository->findOneByLimitedFields();
    }

    public function getDiscountValueProduct($productId): ?float
    {
        $product = $this->productsRepository->find($productId);
        if ($product === null) {
            return null;
        }
        return $this->productsRepository->findDiscountField($productId);
    }

    public function getTopProducts(): array
    {
        $topProducts = $this->productsRepository->findTopProducts();
        foreach ($topProducts as $product) {
            $product = $this->priceService->getMiddlePrice($product->getId()); // получени средней цены пока не используется
        }
        return $topProducts;
    }

    public function getDiscountProducts(): array
    {
        return $this->productsRepository->findDiscountsFields();
    }

    public function getLimitedEdition(): array
    {
        return $this->productsRepository->findByLimitedFields();
    }

    public function getMinPriceByCategory(int $value): float
    {
        return $this->productsRepository->findMinPriceByCategory($value);
    }

    public function getPopularSorting($value): array
    {
        return $this->productsRepository->findByPopularity($value);
    }

    public function getSellerByProductId(string $slug): array
    {
        $product = $this->productsRepository->findOneBy(['slug' => $slug]);
        $prices = $product->getPrices();
        $sellersWithPrices = [];
        foreach ($prices as $price) {
            $seller = $price->getSeller();
            $priceValue = $price->getPrice();

            $sellerName = $seller->getSeller();
            $sellerSlug = $seller->getSlug();

            $sellersWithPrices[] = [
                'sellerName' => $sellerName,
                'priceValue' => $priceValue,
                'sellerSlug' => $sellerSlug,
            ];
        }
        return $sellersWithPrices;
    }

    public function getProductsBySeller(string $slug): array
    {
        $seller = $this->sellerService->getSellerBySlug($slug);
        $sellerId = $seller->getId();
        return $this->productsRepository->findProductsBySeller($sellerId);
    }

    public function getAllWithSearchQuery(?string $search): object
    {
        return $this->productsRepository->findAllWithSearchQuery($search);
    }

    public function getProductById(int $id): ?Products
    {
        return $this->productsRepository->find($id);
    }
}