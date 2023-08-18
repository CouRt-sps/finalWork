<?php

namespace App\Service;

use App\Repository\CategoryRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Contracts\Cache\ItemInterface;

class CategoryService
{
    /**
     * @var ObjectManager
     */
    protected $manager;
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var AdapterInterface
     */
    private $cache;
    /**
     * @var ProductsService
     */
    private $productsService;

    public function __construct(CategoryRepository $categoryRepository, AdapterInterface $cache, ProductsService $productsService)
    {
        $this->categoryRepository = $categoryRepository;
        $this->cache = $cache;
        $this->productsService = $productsService;
    }

    public function getCategory()
    {
        return $this->cache->get('category', function (ItemInterface $item) {
            $item->expiresAfter(\DateInterval::createFromDateString('1 day'));
            return $this->categoryRepository->findCategoryActive();
        });
    }
    public function getFavoriteCategories(): array
    {
        return $this->categoryRepository->findFavoriteCategories(3);
    }
    public function getMinPrice(array $favorites): array
    {
        $minPriceCategories = [];

        foreach ($favorites as $category) {
            $minPrice = $this->productsService->getMinPriceByCategory($category->getId());
            $minPriceCategories[] = $minPrice;
        }
        return $minPriceCategories;
    }
    public function getAllWithSearchQuery(?string $search): object
    {
        return $this->categoryRepository->findAllWithSearchQuery($search);
    }
}
