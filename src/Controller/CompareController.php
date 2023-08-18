<?php

namespace App\Controller;

use App\Service\CategoryService;
use App\Service\CompareService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompareController extends AbstractController
{
    private CategoryService $categoryService;
    private CompareService $compareService;

    public function __construct(CategoryService $categoryService, CompareService $compareService)
    {
        $this->categoryService = $categoryService;
        $this->compareService = $compareService;
    }

    /**
     * @Route("/compare", name="app_compare")
     */
    public function index(): Response
    {
        $categories = $this->categoryService->getCategory();

        $compareLists = $this->compareService->getComparisonList();

        return $this->render('compare/index.html.twig', [
            'categories' => $categories,
            'compareLists' => $compareLists,
        ]);
    }
    /**
     * @Route("/add-to-comparison/{id}", name="app_add_to_comparison")
     */
    public function addToComparison(int $id): Response
    {
        $this->compareService->addProduct($id);

        return $this->redirectToRoute('app_catalog');
    }
}
