<?php

namespace App\Controller;

use App\Entity\Seller;
use App\Service\CategoryService;
use App\Service\ProductsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SellerController extends AbstractController
{

    private CategoryService $category;
    private ProductsService $productsService;

    public function __construct(CategoryService $category, ProductsService $productsService)
    {
        $this->category = $category;
        $this->productsService = $productsService;
    }
    /**
     * @Route("/seller/{slug}", name="app_seller")
     */
    public function show(Seller $seller, $slug): Response
    {
        $categories = $this->category->getCategory();
        $products = $this->productsService->getProductsBySeller($slug);

        return $this->render('seller/index.html.twig', [
            'categories' => $categories,
            'seller' => $seller,
            'products' => $products,
        ]);
    }
}
