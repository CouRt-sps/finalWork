<?php

namespace App\Controller;

use App\Service\CategoryService;
use App\Service\PriceService;
use App\Service\ProductsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @var CategoryService
     */
    private $categoryService;
    /**
     * @var ProductsService
     */
    private $productsService;
    /**
     * @var PriceService
     */
    private $priceService;

    public function __construct(
        CategoryService $categoryService,
        ProductsService $productsService,
        PriceService $priceService
    ) {
        $this->categoryService = $categoryService;
        $this->productsService = $productsService;
        $this->priceService = $priceService;
    }

    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(): Response
    {
        $categories = $this->categoryService->getCategory();
        $favorites = $this->categoryService->getFavoriteCategories();
        $products = $this->productsService->getOneLimitedEdition();
        $valuePrices = $this->priceService->getMinPrice($products->getId());
        $discount = $this->productsService->getDiscountValueProduct($products->getId());
        if ($discount == 0) {
            $discount = null;
        }
        $topProducts = $this->productsService->getTopProducts();
        $limitedEditions = $this->productsService->getLimitedEdition();
        $minPriceCategory = $this->categoryService->getMinPrice($favorites);
        $hotOffers = $this->productsService->getDiscountProducts();

        return $this->render('homepage/homepage.html.twig', [
            'categories' => $categories,
            'favorites' => $favorites,
            'products' => $products,
            'valuePrices' => $valuePrices,
            'discount' => $discount,
            'topProducts' => $topProducts,
            'limitedEditions' => $limitedEditions,
            'minPriceCategory' => $minPriceCategory,
            'hotOffers' => $hotOffers,
        ]);
    }

    /**
     * @Route("/about", name="app_homepage_about")
     */
    public function about(): Response
    {
        $categories = $this->categoryService->getCategory();

        return $this->render('homepage/about.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/contacts", name="app_homepage_contacts")
     */
    public function contacts(): Response
    {
        $categories = $this->categoryService->getCategory();

        return $this->render('homepage/contacts.html.twig', [
            'categories' => $categories,
        ]);
    }
}