<?php

namespace App\Service;


use App\Entity\Products;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CompareService
{
    private $comparisonList = [];
    private ProductsService $productsService;
    private SessionInterface $session;

    public function __construct(ProductsService $productsService, SessionInterface $session)
    {
        $this->productsService = $productsService;
        $this->session = $session;
    }

    public function addProduct(int $id): void
    {
        $product = $this->productsService->getProductById($id);

        $this->comparisonList = $this->session->get('comparison_list', []);
        if (!in_array($product, $this->comparisonList)) {
            $this->comparisonList[] = $product;
            $this->session->set('comparison_list', $this->comparisonList);
        }
    }

    public function removeProduct(Products $product): void
    {
        $this->comparisonList = $this->session->get('comparison_list', []);
        $key = array_search($product, $this->comparisonList);
        if ($key !== false) {
            unset($this->comparisonList[$key]);
            $this->session->set('comparison_list', $this->comparisonList);
        }
    }

    public function getComparisonList($limit = 3): array
    {
        $comparisonList = $this->session->get('comparison_list', []);

        return array_slice($comparisonList, 0, $limit);
    }

    public function getProductCount(): int
    {
        $comparisonList = $this->session->get('comparison_list', []);

        return count($comparisonList);
    }
}