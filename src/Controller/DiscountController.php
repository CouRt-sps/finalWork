<?php

namespace App\Controller;

use App\Service\CategoryService;
use App\Service\DiscountsService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DiscountController extends AbstractController
{
    private CategoryService $categoryService;
    private DiscountsService $discountsService;

    public function __construct(CategoryService $categoryService, DiscountsService $discountsService)
    {
        $this->categoryService = $categoryService;
        $this->discountsService = $discountsService;
    }

    /**
     * @Route("/discount", name="app_discount")
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $categories = $this->categoryService->getCategory();
        $pagination = $paginator->paginate(
            $this->discountsService->getDiscounts(),
            $request->query->getInt('page', 1),
            9
        );


        return $this->render('discount/index.html.twig', [
            'categories' => $categories,
            'pagination' => $pagination,
        ]);
    }
}
