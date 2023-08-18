<?php

namespace App\Controller;

use App\Service\ProductsService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SortingController extends AbstractController
{
    private ProductsService $productsService;

    public function __construct(ProductsService $productsService)
    {
        $this->productsService = $productsService;
    }

    /**
     * @Route("/sorting/{type<desc|asc>}", methods={"POST"}, name="app_sorting_type")
     */
    public function sortingType($type, LoggerInterface $logger)
    {
        $logger->info('Value of $type: ' . $type);
        $type = strtoupper($type);
        $products = $this->productsService->getPopularSorting($type);
        return $this->json(['sorting' => $products]);
    }
}